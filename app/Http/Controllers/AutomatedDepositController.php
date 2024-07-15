<?php

namespace App\Http\Controllers;

use App\Models\AutomatedDeposit;
use Illuminate\Http\Request;

class AutomatedDepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $accounts = AutomatedDeposit::with('customer')->orderby('created_at', 'DESC')->paginate(50);
        
        return view('admin.reserved_accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AutomatedDeposit $automatedDeposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AutomatedDeposit $automatedDeposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AutomatedDeposit $automatedDeposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutomatedDeposit $automatedDeposit)
    {
        // dd($automatedDeposi);
    }
}
