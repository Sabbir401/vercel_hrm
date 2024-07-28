<?php

namespace App\Http\Controllers;

use App\Models\religion;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $religion = religion::all();

        return response()->json($religion);
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
    public function show(religion $religion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(religion $religion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, religion $religion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(religion $religion)
    {
        //
    }
}
