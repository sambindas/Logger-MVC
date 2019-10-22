<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function Dashboard(Request $request) {
        $users = DB::table('user')
        ->where('status', 1)
        ->where('user_type', 0)
        ->where('state_id', $request->session()->get('state_id'))->get()->toArray();
        if ($request->session()->get('id') == 1 or $request->session()->get('id') == 6) {
            $facilities = DB::table('facility')->orderBy('name', 'asc')->get()->toArray();
        } else {
            $facilities = DB::table('facility')
            ->where('state_id', $request->session()->get('state_id'))
            ->orderBy('name', 'asc')->get()->toArray();
        }
        return view('/support/issue/home', ['users' => $users, 'facilities' => $facilities]);
    }

    public function Media(Request $request) {
        $issueID = $request->route('id');
        $media = DB::table('media')
        ->where('issue_id', $issueID)->get()->toArray();
        return view('/support/issue/image', ['media' => $media]);
    }

    public function Edit(Request $request) {
        $issueID = $request->route('id');
        $checkLogger = DB::table('issue')->where('issue_id', $issueID)->get()->toArray();
        if ($checkLogger[0]->support_officer !== $request->session()->get('id')) {
            return redirect('/')->with('badmsg', 'Cannot Edit Another User\'s Issue');
        }
        $facilities = DB::table('facility')->where('state_id', $request->session()->get('state_id'))->get()->toArray();
        $issue = DB::table('issue')
        ->where('issue_id', $issueID)->get()->toArray();
        return view('/support/issue/edit', [
            'issue' => $issue,
            'facilities' => $facilities
        ]);
    }
}
