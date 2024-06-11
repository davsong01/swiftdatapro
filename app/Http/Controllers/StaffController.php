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
            $data['pass'] = Hash::make($request->password);
        }

        if (!empty($request->rolepermissions)) {
            $data['rolepermissions'] = implode(",", $request->rolepermissions);
        }
        
        Admin::create([
            "full_name" => $data['full_name'],
            "username" => $data['username'],
            "email" => $data['email'],
            "status" => $data['status'],
            "pass" => $data['pass'],
            "rolepermissions" => $data['rolepermissions'],
        ]);

        return redirect(route('staff.index'))->with('message', 'Staff added succesfully');
    }

    public function edit($id)
    {
        $staff = Admin::where('id', $id)->first();
        $roles = Role::orderBy('created_at', 'DESC')->get();

        return view('admin.admins.edit', compact('staff', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $staff = Admin::where('id', $id)->first();

        $data = $this->validate($request, [
            'full_name' => 'required',
            'username' => 'required',
            'email' => 'required',
            'status' => 'required',
            'password' => 'nullable',
            'rolepermissions' => 'required',
        ]);

        $permissions = implode(",", $request->rolepermissions);

        if (!empty($request->password)) {
            $data['pass'] = Hash::make($request->password);
        }

        if (!empty($request->rolepermissions)) {
            $data['rolepermissions'] = implode(",", $request->rolepermissions);
        }

        $staff->update([
            "full_name" => $data['full_name'],
            "username" => $data['username'],
            "email" => $data['email'],
            "status" => $data['status'],
            "pass" => $data['pass'] ?? $staff->pass,
            "rolepermissions" => $data['rolepermissions'],
        ]);

        return redirect(route('staff.index'))->with('message', 'Staff updated succesfully');
    }

    public function destroy($id)
    {
        $staff = Admin::where('id', $id)->first();
        $staff->delete();
        
        return back()->with('message', 'Deleted successfully');
    }

}
