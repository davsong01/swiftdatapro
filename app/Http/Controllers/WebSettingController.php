<?php

namespace App\Http\Controllers;

use App\Models\WebSetting;
use Illuminate\Http\Request;

class WebSettingController extends Controller
{
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
    public function show(WebSetting $webSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebSetting $webSetting)
    {
        $contact = WebSetting::first();

        $kyc_settings = !empty($contact->kyc_settings) ? json_decode($contact->kyc_settings, true) : [];
        
        return view('admin.settings.contact_info', compact('contact', 'kyc_settings'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebSetting $webSetting)
    {
        $contact = WebSetting::first();
        $data = $request->except(['_token', '1']);

        if (!empty($request->logo)) {
            $data['logo'] = $this->uploadFile($request->logo, 'site');
        }

        if (!empty($request->site_favicon)) {
            $data['site_favicon'] = $this->uploadFile($request->site_favicon, 'site');
        }
       
        if (!empty($request->kyc_settings)) {
            $data['kyc_settings'] = json_encode($request->kyc_settings);
        }

        $contact->update($data);

        return back()->with('message', 'Update Successful');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebSetting $webSetting)
    {
        //
    }
}
