<?php

namespace App\Http\Controllers;

use App\Models\emp_img;
use Illuminate\Http\Request;

class EmpImgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(emp_img $emp_img)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $image = emp_img::with('employee')
        ->where('EID',$id)
        ->get();

        return response()->json($image);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, emp_img $emp_img)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(emp_img $emp_img)
    {
        //
    }
}
