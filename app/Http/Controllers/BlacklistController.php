<?php

namespace App\Http\Controllers;

use App\Models\Blacklist;
use Illuminate\Http\Request;

class BlacklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $billers = Blacklist::orderby('created_at', 'DESC')->get();
        return view('admin.blacklist.index', compact('billers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blacklist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "biller" => "required",
        ]);

        $bank = Blacklist::updateOrCreate([
            "biller" => $request->biller,
            "added_by" => auth()->guard('admin')->user()->id,
        ]);

        return redirect(route('blacklist.index'))->with('message', 'Added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Blacklist $blacklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blacklist $blacklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blacklist $blacklist)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blacklist $blacklist)
    {
        $blacklist->delete();
        return back()->with('message', 'Deleted successfully');
    }
}
