<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        $user = User::orderBy('id')->paginate(5);

        //users is view file folder name
        // user is model or table name
        return view('users.index', compact('user'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',

        ]); 
        
        User::create($request->post());

        return redirect()->route('users.index')->with('success','Company has been created successfully.');
    }
}
