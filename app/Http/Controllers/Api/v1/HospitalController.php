<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalController extends Controller
{
    public $defaultLanguage = config('app.locale') ?? 'en'; 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $hospitals = Hospital::with('translation')
            ->when($request->has('search') && $request->search !== '', function ($query) use ($request) {
                $query->whereHas('translation', function ($query) use ($request) {
                    $query->where('en->name', 'like', '%' . $request->search . '%');
                });
            });

        return response()->json($hospitals->paginate(25));
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
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'website' => 'required|url',
            'description' => 'required|string',
            'speciality' => 'required|string',
        ]);

        $hospital = Hospital::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'website' => $request->website
        ]);

        $hospital->translation()->create([
            $this->defaultLanguage => [
                'name' => $request->name,
                'address' => $request->address,
                'description' => $request->description,
                'speciality' => $request->speciality
            ],
        ]);

        return response()->json(['message' => 'Hospital created successfully', 'hospital' => $hospital->load('translation')], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Hospital $hospital
     * @return \Illuminate\Http\Response
     */
    public function show(Hospital $hospital)
    {
        return response()->json($hospital->load('translation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Hospital $hospital
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hospital $hospital)
    {
        $request->validate([
            'name' => 'string',
            'address' => 'string',
            'phone' => 'string',
            'email' => 'email',
            'website' => 'url',
            'description' => 'string',
            'speciality' => 'string',
        ]);

        $hospital_data = array_filter($request->only('phone', 'email', 'website'));
        $hospital_translation_data = array_filter($request->only('name', 'address', 'description', 'speciality'));

        $hospital->update($hospital_data);
        $hospital->translation()->update([
            $this->defaultLanguage => $hospital_translation_data
        ]);

        return response()->json(['message' => 'Hospital updated successfully', 'hospital' => $hospital->load('translation')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Hospital $hospital
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hospital $hospital)
    {
        $hospital->translation()->delete();
        $hospital->delete();
        return response()->json(['message' => 'Hospital deleted successfully']);
    }
}
