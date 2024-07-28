<?php

namespace App\Http\Controllers;

use App\Models\emp_img;
use App\Models\employee;
use App\Models\religion;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = employee::with('empImg')->orderBy('Employee_Id', 'asc')->paginate(10);
        return response()->json($employee);
    }

    public function allemp()
    {
        $employee = employee::orderBy('Full_Name', 'asc')->get();
        return response()->json($employee);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = employee::with('empImg')->where('Full_Name', 'LIKE', "%{$query}%")
            ->orWhere('Employee_Id', 'LIKE', "%{$query}%")
            ->paginate(50);


        return response()->json($results);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'companyId' => 'required',
            'employeeId' => 'required|unique:employees,Employee_Id',
            'fullName' => 'required',
            'dob' => 'required',
        ], [
            'companyId.required' => 'The company ID is required.',
            'employeeId.required' => 'The employee ID is required.',
            'employeeId.unique' => 'The employee ID has already been taken.',
            'fullName.required' => 'The full name is required.',
            'dob.required' => 'The date of birth is required.',
        ]);

        $file_path = null;

        DB::beginTransaction();
        $employee = employee::create([
            'Company_Id' => $request->input('companyId'),
            'Employee_Id' => $request->input('employeeId'),
            'Card_No' => $request->input('cardNo'),
            'Full_Name' => $request->input('fullName'),
            'Father_Name' => $request->input('fatherName'),
            'Mother_Name' => $request->input('motherName'),
            'Spouse_Name' => $request->input('spouseName'),
            'Marital_Status' => $request->input('maritalStatus'),
            'DOB' => $request->input('dob'),
            'Place_of_Birth' => $request->input('pob'),
            'Present_Address' => $request->input('presentAddress'),
            'Permanent_Address' => $request->input('permanentAddress'),
            'Contact_No' => $request->input('officialContact'),
            'Emergency_Contact' => $request->input('emergencyContact'),
            'Gender' => $request->input('gender'),
            'Personal_Email' => $request->input('personalEmail'),
            'Official_Email' => $request->input('officialEmail'),
            'Blood_Group_Id' => $request->input('bloodGroup'),
            'Religion_Id' => $request->input('religion'),
            'Nationality' => $request->input('nationality'),
            'NID' => $request->input('nid'),
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,xlsx,pdf|max:512'
            ]);

            $file_name = time() . '_' . $request->file->getClientOriginalName();
            $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

            $image = emp_img::create([
                'EID' => $employee->id,
                'img_url' => '/storage/' . $file_path,
            ]);
        }

        $lastId = employee::latest('id')->value('id');
        $response = [
            'success'   =>  true,
            'message'   =>  'Successfully inserted',
            'empid' => $lastId,
        ];

        DB::commit();
        return response()->json($response);


        DB::rollback();
        $response = [
            'success'   =>  false,
            'message'   =>  'Error while inserting',
        ];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(employee $id)
    {
        $emp = employee::find($id);

        if (!$emp) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
        return response()->json($emp);
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function generatePdf($id)
    {
        $employee = employee::with([
            'company',
            'blood',
            'religion',
            'academic',
            'training',
            'experience',
            'official',
            'nominee',
            'child',
            'academic.scale',
            'academic.board',
            'academic.education',
            'academic.education.degree',
            'official.designation',
            'official.department',
            'official.area',
            'official.employeeType',
            'official.territory', 
            'official.supervisor',
            'official.branch',
        ])->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $pdf = Pdf::loadView('reports.attendance', compact('employee'));


        return $pdf->stream('CV.pdf');
    }


    public function edit($id)
    {
        $employee = employee::with([
            'company',
            'blood',
            'religion',
            'academic',
            'training',
            'experience',
            'official',
            'nominee',
            'child',
            'academic.scale',
            'academic.board',
            'academic.education',
            'academic.education.degree',
            'official.designation',
            'official.department',
            'official.area',
            'official.employeeType',
            'official.territory',
            'official.supervisor',
            'official.branch',
        ])->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }

    public function find($id)
    {
        $employee = Employee::select('employees.id', 'employees.Full_Name', 'employees.Employee_Id', 'designations.Name')
            ->join('officials', 'officials.EID', '=', 'employees.id')
            ->join('departments', 'officials.Department_Id', '=', 'departments.id')
            ->join('designations', 'officials.Designation_Id', '=', 'designations.id')
            ->where('departments.id', '=', $id)
            ->get();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }

    public function attendenceEmployee()
    {
        $employee = employee::select('employees.id', 'employees.Full_Name', 'employees.Employee_Id', 'designations.Name as designation', 'departments.Name as department')
            ->join('officials', 'officials.EID', '=', 'employees.id')
            ->join('departments', 'officials.Department_Id', '=', 'departments.id')
            ->join('designations', 'officials.Designation_Id', '=', 'designations.id')
            ->where('officials.Status', '=', 'N')
            ->orderby('departments.Name', 'asc')
            ->get();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }


    public function update(Request $request, $id)
    {
        // try {
        DB::beginTransaction();
        $employee = employee::find($id);
        $employee->update([
            'Company_Id' => $request->companyId,
            'Employee_Id' => $request->input('employeeId'),
            'Card_No' => $request->input('cardNo'),
            'Full_Name' => $request->input('fullName'),
            'Father_Name' => $request->input('fatherName'),
            'Mother_Name' => $request->input('motherName'),
            'Spouse_Name' => $request->input('spouseName'),
            'Marital_Status' => $request->input('maritalStatus'),
            'DOB' => $request->input('dob'),
            'Place_of_Birth' => $request->input('pob'),
            'Present_Address' => $request->input('presentAddress'),
            'Permanent_Address' => $request->input('permanentAddress'),
            'Contact_No' => $request->input('officialContact'),
            'Emergency_Contact' => $request->input('emergencyContact'),
            'Gender' => $request->input('gender'),
            'Personal_Email' => $request->input('personalEmail'),
            'Official_Email' => $request->input('officialEmail'),
            'Blood_Group_Id' => $request->input('bloodGroup'),
            'Religion_Id' => $request->input('religion'),
            'Nationality' => $request->input('nationality'),
            'NID' => $request->input('nid'),
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,xlsx,pdf|max:512'
            ]);

            $existingImage = emp_img::where('EID', $employee->id)->first();

            if ($existingImage) {
                $file_name = time() . '_' . $request->file->getClientOriginalName();
                $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

                Storage::delete($existingImage->img_url);
                $existingImage->update([
                    'img_url' => '/storage/' . $file_path,
                ]);
            } else {
                $file_name = time() . '_' . $request->file->getClientOriginalName();
                $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');

                $image = emp_img::create([
                    'EID' => $employee->id,
                    'img_url' => '/storage/' . $file_path,
                ]);
            }
        }

        $response = [
            'success' => true,
            'message'  => 'Updated Successfully'
        ];
        DB::commit();
        return response()->json($response);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     $response = [
        //         'success'   =>  false,
        //         'message'   =>  'Error while updating',
        //     ];
        //     return response()->json($response);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(employee $employee)
    {
    }
}
