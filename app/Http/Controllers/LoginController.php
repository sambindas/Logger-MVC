<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function Login(Request $request) {
        $email = $request->email;
        $password = $request->password;
        
        $users = DB::table('user')->where('password', $password)->where('email', $email)->get();
        
        if (!$users->count() > 0) {
            echo 0;
        } else {
            if ($users[0]->user_type == 1) {
                $request->session()->put('id', $users[0]->id);
                $request->session()->put('name', $users[0]->user_name);
                $request->session()->put('logged_in', 'support');
                $request->session()->put('email', $users[0]->email);
                $request->session()->put('state_id', $users[0]->state_id);
                echo 'support';
            } else {
                $request->session()->put('id', $users[0]->id);
                $request->session()->put('name', $users[0]->user_name);
                $request->session()->put('logged_in', 'client');
                $request->session()->put('email', $users[0]->email);
                $request->session()->put('state_id', $users[0]->state_id);
                $request->session()->put('client_code', $users[0]->user_role);
                echo 'client';
            }
        }
    }
}