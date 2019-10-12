<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class UserController extends Controller
{
    public function Dashboard (Request $request) {
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            $users = DB::table('user')->where('user_type', 0)->get()->toArray();
            $states = DB::table('state')->get()->toArray();
            return view('/support/manage/user', ['users' => $users, 'states' => $states]);
        }
    }

    public function ClientDashboard (Request $request) {
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            $users = DB::table('user')
                        ->select('user.id as id', 'user.email as user_email', 'user.user_name as user_name',
                        'user.phone as phone','user.status as status', 'facility.name as name', 
                        'facility.email as facility_email', 'facility.id as facility_id')
                        ->where('user.user_type', 0)
                        ->join('facility', 'user.facility_id', 'facility.id')->get()->toArray();
            $states = DB::table('state')->get()->toArray();
            return view('/support/manage/client', ['users' => $users, 'states' => $states]);
        }
    }

    public function GetFacility(Request $request) {
        $query = DB::table('facility')->where('state_id', $request->state_id)->get()->toArray();
        if ($query) {
            foreach ($query as $row) {
                echo '<option value="'.$row->id.'">'.$row->name.'</option>';
            }
        }
    }

    public function CheckEmail (Request $request) {
        $check = DB::table('user')->where('email', $request->email)->get();
        if ($check->count() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function Register (Request $request) {
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
        }
        if ($insert) {
            Session::flash('msg', 'User Added Successfully');
            return 1;
        } else {
            return 0;
        }
    }

    public function Activate (Request $request) {
        $activate = DB::table('user')->where('id', $request->id)->update([
            'status' => 1
        ]);
        
        if ($activate) {
            Session::flash('msg', 'User Activated Successfully');
            return back();
        }    
    }

    public function Deactivate (Request $request) {
        $deactivate = DB::table('user')->where('id', $request->id)->update([
            'status' => 0
        ]);
        
        
        if ($deactivate) {
            Session::flash('msg', 'User Deactivated Successfully');
            return back();
        }    
    }

    public function ChangeState (Request $request) {
        $change = DB::table('facility')->where('id', $request->id)->update(['state_id' => $request->state_id]);
        
        if ($change) {
            Session::flash('msg', 'State For Facility Updated Successfully');
            return redirect('/facility');
        }    
    }
}
