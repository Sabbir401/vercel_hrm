<?php

namespace App\Http\Controllers;

use App\Models\employee_type;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emptype = employee_type::all();

        return response()->json($emptype);
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
    public function show(employee_type $employee_type)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(employee_type $employee_type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, employee_type $employee_type)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(employee_type $employee_type)
    {
        //
    }
}
