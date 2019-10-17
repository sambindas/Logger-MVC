<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
 
        if ($credentials) {
            return response()->json(['userData' => User::all()->where('email', $request->email)], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }

    public function register(Request $request) {
        if (User::all()->where('email', $request->email)->count() >0){ #check if user exists
            return response()->json(['status' => 'fail', 'message' => 'Another User Exists With That Email'], 406);
        }
        if (isset($request->type)) {
            $insert = DB::table('user')->insert([
                'email' => $request->email,
                'user_name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
                'state_id' => $request->state,
                'email' => $request->email,
                'facility_id' => $request->facility,
                'status' => 1,
                'date_added' => now(),
                'user_type' => 0
            ]);
            if ($insert) {
                return response()->json(['status' => 'success', 'message' => 'Client Registered Successfully!'], 200);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'An Error Occured, Please Try Again'], 400);
            }
        } else {
            $insert = DB::table('user')->insert([
                'email' => $request->email,
                'user_name' => $request->name,
                'phone' => $request->phone,
                'password' => $request->password,
                'user_role' => $request->role,
                'state_id' => $request->state,
                'email' => $request->email,
                'status' => 1,
                'date_added' => now(),
                'user_type' => 1
            ]);
            if ($insert) {
                return response()->json(['status' => 'success', 'message' => 'User Registered Successfully!'], 200);
            } else {
                return response()->json(['status' => 'fail', 'message' => 'An Error Occured, Please Try Again'], 400);
            }
        }
    }

    public function activate (Request $request) {
        $id = $request->route('id');
        if (!isset($id)) {
            return response()->json(['status'=>'fail', 'message'=>'User ID Required'], 428);
        }
        if (User::where('id', $id)->get()->count() <= 0) {
            return response()->json(['status'=>'fail', 'message'=>'User Not Found'], 428);
        }
        $activate = DB::table('user')->where('id', $id)->update(['status' => 1]);

        if ($activate) {
            return response()->json(['status'=>'success', 'message'=>'User Activated Successfully'], 200);
        }
    }

    public function deactivate (Request $request) {
        $id = $request->route('id');
        if (!isset($id)) {
            return response()->json(['status'=>'fail', 'message'=>'User ID Required'], 428);
        }
        if (User::where('id', $id)->get()->count() <= 0) {
            return response()->json(['status'=>'fail', 'message'=>'User Not Found'], 428);
        }
        $deactivate = DB::table('user')->where('id', $id)->update(['status' => 0]);

        if ($deactivate) {
            return response()->json(['status'=>'success', 'message'=>'User Deactivated Successfully'], 200);
        }
    }
}
