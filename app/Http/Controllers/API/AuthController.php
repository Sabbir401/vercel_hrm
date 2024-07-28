<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function register(Request $request){
        // $name = employee::select('Full_Name')->where('id', $request->input('employee_Id'))->get();
        $name = employee::where('id', $request->input('employee_Id'))->pluck('Full_Name')->first();

        $validator = Validator::make($request->all(),[
            'email'     =>  'required|email',
            'password'  =>  'required',
            'c_password'=>  'required|same:password',
        ]);
        
        if($validator->fails()){
            $response = [
                'success'   =>  false,
                'message'   =>  $validator->errors()
            ];
            return response()->json($response, 400);
        }
        
        // $input = $request->all();   
        // $input['password']  =  bcrypt($input['password']);
        // $user = User::create($input);

        $user = User::create([
            'name' => $name,
            'email' => $request->input('email'),
            'EID' => $request->input('employee_Id'),
            'password' => bcrypt($request->input('password')),
        ]);

        $success['token']   =   $user->createToken('MyApp')->plainTextToken;
        $success['name']    =   $user->name;
        $response           =   [
            'success'       =>  true,
            'data'          =>  $success,
            'message'       =>  'User register successfully'
        ];
        return response()->json($response,200);

    }

    public function login(Request $request){
        if(Auth::attempt(['email' =>  $request->email, 'password'  =>  $request->password])){
            $user = Auth::user();
            Session::put('user_email', $request->email);

            $success['token']   =   $user->createToken('MyApp')->plainTextToken;
            $success['name']    =   $user->name;
            $response       =   [
                'success'       =>  true,
                'data'          =>  $success,
                'message'       =>  'Successfully logged In'
            ];
            return response()->json($response,200);
        }else{
            $response       =   [
            'success'       =>  false,
            'message'       =>  'Unauthorized'
            ];
            return response()->json($response);
        }
    }
}
