<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BugReport;
use App\Models\User;

class BugReportController extends Controller
{
    public function createBug(Request $request)
    {
        // validate
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);     

        

        //create
        $token = auth()->user()->id;
        $name = auth()->user()->name;
        $email = auth()->user()->email;

        $bug = BugReport::create([
            'user_id' => $token,
            'user_email' => $email,
            'user_name' => $name,
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        return response()->json([
            'status' => 200,
            'message' =>
                'Bug Created Successfully Admin or Developer fix the bug as soon as possible',
            'data' => $bug,
        ]);
    }
}
