<?php

namespace App\Http\Controllers\Api;
use App\Issue;
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

        return response()->json([
            'code' => 200,
            'data' => Issue::get()
        ]);
    }
}
