<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function Dashboard(Request $request) {
       
    //    $request->session()->flush();
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            $users = DB::table('user')
            ->where('status', 1)
            ->where('user_type', 0)
            ->where('state_id', $request->session()->get('state_id'))->get()->toArray();
            return view('/support/issue/home', ['users' => $users]);
        }
    }
}
