<?php

namespace App\Http\Controllers;

use App\Models\scale;
use Illuminate\Http\Request;

class ScaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scale = scale::all();

        return response()->json($scale);
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
    public function show(scale $scale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(scale $scale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, scale $scale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(scale $scale)
    {
        //
    }
}
