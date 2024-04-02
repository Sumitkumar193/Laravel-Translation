<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Doctor;
use App\Models\DoctorExp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public $defaultLanguage = config('app.locale') ?? 'en'; 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctors = Doctor::with(['experience'])
            ->when($request->has('search') && $request->search !== '', function ($query) use ($request) {
                $query->whereHas('translation', function ($query) use ($request) {
                    $query->where('en->name', 'like', '%' . $request->search . '%');
                });
            });

        return response()->json($doctors->paginate(10));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'required|string',
                'phone' => 'required|string',
                'email' => 'required|email',
                'website' => 'required|url',
                'speciality' => 'required|string',
                'bio' => 'required|string',
                'education' => 'required|string',
                'experience' => 'required|string',
                'hospital_name' => 'required|string',
                'designation' => 'required|string',
                'from' => 'required|string',
                'to' => 'required|string',
            ]);
    
            $doctor = Doctor::create([
                'phone' => $request->phone,
                'email' => $request->email,
                'website' => $request->website
            ]);
    
            $doctor->translation()->create([
                $this->defaultLanguage => [
                    'name' => $request->name,
                    'speciality' => $request->speciality,
                    'bio' => $request->bio,
                    'education' => $request->education,
                    'experience' => $request->experience
                ],
            ]);

            $exp = [
                'hospital_name' => $request->hospital_name,
                'designation' => $request->designation,
                'from' => $request->from,
                'to' => $request->to,
                'doctor_id' => $doctor->id,
            ];
            
            $docExp = DoctorExp::create($exp);
            $docExp->translation()->create([
                $this->defaultLanguage => [
                    'hospital_name' => $request->hospital_name,
                    'designation' => $request->designation,
                ],
            ]);

            DB::commit();
            return response()->json($doctor);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        return response()->json($doctor->load(['experience']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Doctor $doctor)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'name' => 'string',
                'phone' => 'string',
                'email' => 'email',
                'website' => 'url',
                'speciality' => 'string',
                'bio' => 'string',
                'education' => 'string',
                'experience' => 'string',
                'hospital_name' => 'string',
                'designation' => 'string',
                'from' => 'string',
                'to' => 'string',
            ]);
    
            $doctor->update($request->only(['phone', 'email', 'website']));
    
            $doctor->translation()->update([
                $this->defaultLanguage => [
                    'name' => $request->name,
                    'speciality' => $request->speciality,
                    'bio' => $request->bio,
                    'education' => $request->education,
                    'experience' => $request->experience
                ],
            ]);

            $doctor->experience()->update([
                'hospital_name' => $request->hospital_name,
                'designation' => $request->designation,
                'from' => $request->from,
                'to' => $request->to,
            ]);

            $doctor->experience->translation()->update([
                $this->defaultLanguage => [
                    'hospital_name' => $request->hospital_name,
                    'designation' => $request->designation,
                ],
            ]);
            DB::commit();
            return response()->json($doctor->load('translation'));
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        try {
            $doctor->translation()->delete();
            $doctor->delete();
            return response()->json(['message' => 'Doctor deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
