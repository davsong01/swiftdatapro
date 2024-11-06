<?php

namespace App\Http\Controllers;

use App\Models\KycLog;
use Illuminate\Http\Request;

class KycLogController extends Controller
{
    public function logKycAction($data){
        $log = KycLog::create([
            'kyc_data_id' => $data['kyc_data_id'],
            'reason' => $data['reason'] ?? null,
            'kyc_status' => $data['kyc_status'],
        ]);

        return $log;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(KycLog $kycLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KycLog $kycLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KycLog $kycLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KycLog $kycLog)
    {
        //
    }
}
