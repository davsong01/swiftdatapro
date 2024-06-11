<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $admin = Admin::orderBy('created_at', 'DESC')->get();
        return view('admin.admins.index', ['admin' => $admin]);
    }

    public function create()
    {
        $roles = Role::orderBy('created_at', 'DESC')->get();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'full_name' => 'required',
            'username' => 'required',
            'email' => 'required|unique:vtuapp_admin',
            'status' => 'required',
            'password' => 'required',
            'rolepermissions' => 'required',
        ]);

        $permissions = implode(",", $request->rolepermissions);

        if(!empty($request->password)){
            $data['password'] = Hash::make($request->password);
        }

        if (!empty($request->rolepermissions)) {
            $data['rolepermissions'] = implode(",", $request->rolepermissions);
        }

        Admin::create([
            "full_name" => $data['full_name'],
            "username" => $data['username'],
            "email" => $data['email'],
            "status" => $data['status'],
            "password" => $data['password'],
            "rolepermission" => $data['rolepermissions']
        ]);

        return redirect(route('staff.index'))->with('message', 'Staff added succesfully');
    }


}
