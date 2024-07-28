<?php

namespace App\Http\Controllers;

use App\Models\child;
use App\Models\nominee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NomineeController extends Controller
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
        $request->validate([
            'nominee.nomineeName' => 'required',
            'nominee.dob' => 'required',
            'nominee.contactNo' => 'required',
        ], [
            'nominee.nomineeName.required' => 'The Nominee Name is required.',
            'nominee.dob.required' => 'The Nominee Date of Birth is required.',
            'nominee.contactNo.required' => 'The Contact No is required.',
        ]);

        $store = nominee::create([
            'EID' => $request->input('nominee.eid'),
            'Nominee_Name' => $request->input('nominee.nomineeName'),
            'DOB' => $request->input('nominee.dob'),
            'Contact_No' => $request->input('nominee.contactNo'),
            'Email' => $request->input('nominee.email'),
            'NID' => $request->input('nominee.nid'),
            'Share' => $request->input('nominee.share'),
            'Personal_Address' => $request->input('nominee.presentAddress'),
        ]);

        $store = child::create([
            'EID' => $request->input('child.eid'),
            'Child_Name' => $request->input('child.childName'),
            'NID' => $request->input('child.nid'),
            'Email' => $request->input('child.email'),
            'Contact_No' => $request->input('child.contactNo'),
            'DOB' => $request->input('child.dob'),
        ]);

        $response = [
            'success'   =>  true,
            'message'   =>  'Successfully inserted',
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(nominee $nominee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        $nominee = DB::table('nominees')
            ->select('*')
            ->where('EID',$id)
            ->get();

        $child = DB::table('children')
            ->select('*')
            ->where('EID',$id)
            ->get();
        if (!$nominee) {
            return response()->json(['message' => 'Nominee not found']);
        }
        return response()->json([
            'nominee' => $nominee,
            'child' => $child,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $nominee = Nominee::where('EID', $id)->first();
        $child = child::where('EID', $id)->first();

    if ($nominee) {
        $nominee->update([
            'Nominee_Name' => $request->input('nominee.nomineeName'),
            'DOB' => $request->input('nominee.dob'),
            'Contact_No' => $request->input('nominee.contactNo'),
            'Email' => $request->input('nominee.email'),
            'NID' => $request->input('nominee.nid'),
            'Share' => $request->input('nominee.share'),
            'Personal_Address' => $request->input('nominee.presentAddress'),
        ]);

        $child->update([
            'Child_Name' => $request->input('child.childName'),
            'NID' => $request->input('child.nid'),
            'Email' => $request->input('child.email'),
            'Contact_No' => $request->input('child.contactNo'),
            'DOB' => $request->input('child.dob'),
        ]);

        $response = [
            'success' => true,
            'message' => 'Updated Successfully'
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Nominee not found'
        ];
    }

    return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(nominee $nominee)
    {
        //
    }
}
