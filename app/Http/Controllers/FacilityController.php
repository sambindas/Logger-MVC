<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class FacilityController extends Controller
{
    public function Dashboard (Request $request) {
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            $facilities = DB::table('facility')->get()->toArray();
            return view('/support/manage/facility', ['facilities' => $facilities]);
        }
    }
}
