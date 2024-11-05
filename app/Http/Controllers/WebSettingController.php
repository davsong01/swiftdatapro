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
        $allowed_domains_for_registration = !empty($contact->allowed_domain_emails_for_registration) ? implode(',',json_decode($contact->allowed_domain_emails_for_registration, true)) : '';
        $extra_settings = $contact->extra_settings;

        return view('admin.settings.contact_info', compact('extra_settings','contact', 'allowed_domains_for_registration'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WebSetting $webSetting)
    {
        $contact = WebSetting::first();
        $old_extra_settings = $contact->extra_settings['extra_settings'] ?? [];
        $extra_settings = [];
    
        foreach ($request->extra_settings as $key => $value) {
            $extra_settings[$key] = $request->extra_settings[$key] ? $request->extra_settings[$key] : $old_extra_settings[$key];
        }
        
        // foreach ($request->only('extra_settings')['extra_settings'] as $key => $value) {
        //     if (!array_key_exists($key, $old_extra_settings)) {
        //         $extra_settings[$key] = $value[$key]; 
        //     }
        // }

        $data = $request->except(['_token', '1', 'allowed_domain_emails_for_registration']);
        
        if (!empty($request->logo)) {
            $data['logo'] = $this->uploadFile($request->logo, 'site');
        }
        if (!empty($request->site_favicon)) {
            $data['site_favicon'] = $this->uploadFile($request->site_favicon, 'site');
        }

        if (!empty($request->allowed_domain_emails_for_registration)) {
            $data['allowed_domain_emails_for_registration'] = json_encode(explode(',',$request->allowed_domain_emails_for_registration));
        }
        
        $data['extra_settings'] = $extra_settings;
        $data['transaction_restriction_settings'] = $request->transaction_restriction_settings;
        $data['allowed_email_notifications'] = !empty($data['allowed_email_notifications']) ? $data['allowed_email_notifications'] : [];

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
