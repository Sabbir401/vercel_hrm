<?php

namespace App\Http\Controllers;

use App\Models\board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $board = board::all();

        return response()->json($board);
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
    public function show(board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(board $board)
    {
        //
    }
}
