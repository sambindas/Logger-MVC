<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function Dashboard(Request $request) {
        if (!$request->session()->has('id')) {
            return redirect()->route('/home');
        } else {
            return view('/support/issue/home');
        }
        
    }
}
