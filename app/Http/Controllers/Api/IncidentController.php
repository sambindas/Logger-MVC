<?php

namespace App\Http\Controllers\Api;
use App\Issue;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IncidentController extends Controller
{
    public function incidents($id = null) {
        if ($id != null) {
            return response()->json([
                'code' => 200,
                'data' => Issue::find($id)
            ]);
        }
        $query = DB::table('issue')
        ->join('user', 'user.id', '=', 'issue.support_officer')
        ->join('facility', 'facility.code', '=', 'issue.facility')->get();
        return response()->json([
            'code' => 200,
            'data' => $query->name
        ]);
    }
}
