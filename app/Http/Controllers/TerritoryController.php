<?php

namespace App\Http\Controllers;

use App\Models\territory;
use Illuminate\Http\Request;

class TerritoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $territory = territory::all();

        return response()->json($territory);
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
    public function show(territory $territory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(territory $territory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, territory $territory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(territory $territory)
    {
        //
    }
}
