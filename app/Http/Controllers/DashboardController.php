<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function Dashboard(Request $request) {
       
    //    $request->session()->flush();
        if (!$request->session()->has('id')) {
            return view('/support/auth/login');
        } else {
            return view('/support/issue/home');
        }
    }
}
