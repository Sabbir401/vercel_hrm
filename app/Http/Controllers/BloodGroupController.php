<?php

namespace App\Http\Controllers;

use App\Models\blood_group;
use Illuminate\Http\Request;

class BloodGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blood = blood_group::all();

        return response()->json($blood);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(blood_group $blood_group)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(blood_group $blood_group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, blood_group $blood_group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(blood_group $blood_group)
    {
        //
    }
}
