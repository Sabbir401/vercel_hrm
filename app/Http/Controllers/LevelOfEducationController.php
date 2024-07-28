<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\level_of_education;
use Illuminate\Support\Facades\DB;

class LevelOfEducationController extends Controller
{
    public function index()
    {
        $education = DB::table('level_of_educations')
            ->select('id', 'Name')
            ->whereNull('education_id')
            ->orWhere('education_id', '')
            ->get();

        $degree = DB::table('level_of_educations')
            ->select('id', 'Name')
            ->whereNotNull('education_id')
            ->get();

        return response()->json([
            'education' => $education,
            'degree' => $degree,
        ]);

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
    public function show($id)
    {
        $degree = DB::table('level_of_educations')
            ->select('id', 'Name')
            ->where('education_id', $id)
            ->orderBy('Name', 'asc')
            ->get();

        return response()->json($degree);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(level_of_education $level_of_education)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, level_of_education $level_of_education)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(level_of_education $level_of_education)
    {
        //
    }
}
