<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::get();
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required",
            "type" => "required",
            "status" => "nullable",
            "content" => "nullable",
        ]);

        Notification::updateOrCreate([
            "name" => $request->name,
            "type" => $request->type,
            "status" => $request->status,
            "content" => $request->content,
        ]);

        return redirect(route('notification.index'))->with('message', 'Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', ['notification' => $notification]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        $this->validate($request, [
            "name" => "required",
            "type" => "required",
            "status" => "nullable",
            "content" => "nullable",
        ]);

        $notification->update([
            "name" => $request->name,
            "type" => $request->type,
            "status" => $request->status,
            "content" => $request->content,
        ]);
        
        return redirect()->route('notification.index')->with('message', "Update successful");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
       $notification->delete();
       return back()->with('message', 'Delete Successful');
    }
}
