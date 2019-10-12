<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;

class FacilityController extends Controller
{
    public function Dashboard (Request $request) {
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            $facilities = DB::table('facility')
                            ->select('facility.id as id', 'facility.name', 'facility.email', 'facility.contact_person', 'facility.contact_person_phone', 'facility.server_ip', 'facility.online_url', 'state.state_name', 'facility.code')
                            ->join('state', 'state.id', 'facility.state_id')->get()->toArray();
            $states = DB::table('state')->get()->toArray();
            return view('/support/manage/facility', ['facilities' => $facilities, 'states' => $states]);
        }
    }

    public function CheckFacility (Request $request) {
        $check = DB::table('facility')->where('code', $request->code)->get();
        if ($check->count() > 0) {
            return 0;
        } else {
            return 1;
        }
    }

    public function Create (Request $request) {
        $insert = DB::table('facility')->insert([
            'code' => $request->code,
            'name' => $request->name,
            'contact_person' => $request->cperson,
            'contact_person_phone' => $request->cpersonp,
            'server_ip' => $request->serverip,
            'online_url' => $request->online_url,
            'email' => $request->email,
            'state_id' => $request->state
        ]);
        if ($insert) {
            Session::flash('msg', 'Facility Added Successfully');
            return 1;
        } else {
            return 0;
        }
    }

    public function Edit (Request $request) {
        $update = DB::table('facility')->where('id', $request->id)->update([
            'name' => $request->name,
            'contact_person' => $request->cperson,
            'contact_person_phone' => $request->cpersonp,
            'server_ip' => $request->serverip,
            'online_url' => $request->online_url,
            'email' => $request->email
        ]);
        
        if ($update) {
            Session::flash('msg', 'Facility Updated Successfully');
            return redirect('/facility');
        }    
    }

    public function Delete (Request $request) {
        $delete = DB::table('facility')->where('id', $request->id)->delete();
        
        if ($delete) {
            Session::flash('msg', 'Facility Deleted Successfully');
            return redirect('/facility');
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
