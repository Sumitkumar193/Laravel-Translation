<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctors = Doctor::with('translation')
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
        $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'website' => 'required|url',
            'speciality' => 'required|string',
            'bio' => 'required|string',
            'education' => 'required|string',
            'experience' => 'required|string',
        ]);

        $doctor = Doctor::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website
        ]);

        $doctor->translation()->create([
            'en' => [
                'name' => $request->name,
                'speciality' => $request->speciality,
                'bio' => $request->bio,
                'education' => $request->education,
                'experience' => $request->experience
            ],
        ]);

        return response()->json($doctor);
    }

    /**
     * Display the specified resource.
     *
     * @param  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(Doctor $doctor)
    {
        return response()->json($doctor->load('translation'));
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
        $request->validate([
            'name' => 'string',
            'phone' => 'string',
            'email' => 'email',
            'website' => 'url',
            'speciality' => 'string',
            'bio' => 'string',
            'education' => 'string',
            'experience' => 'string',
        ]);

        $doctor->update($request->only(['phone', 'email', 'website']));

        $doctor->translation()->update([
            'en' => [
                'name' => $request->name,
                'speciality' => $request->speciality,
                'bio' => $request->bio,
                'education' => $request->education,
                'experience' => $request->experience
            ],
        ]);

        return response()->json($doctor->load('translation'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Doctor $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->translation()->delete();
        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted successfully']);
    }
}
