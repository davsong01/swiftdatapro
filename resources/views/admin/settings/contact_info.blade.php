@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit General Settings')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('settings.update')}}">General Settings</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit General Settings</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit General Settings</h5> <br>
                @include('includes.alerts')
            </div>
            <div class="card-body">
                <form action="{{ route('settings.update', $contact->id) }}" method="POST" enctype="multipart/form-data">
                @csrf                
                <div class="row gy-4">
                    <div class="col-md-6">
                        <label for="sitename" class="form-label">Busines Name<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="sitename" name="sitename" value="{{ $contact->sitename ?? old('sitename') }}" placeholder="Business Name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="domain" class="form-label">Domain Link<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="domain" name="domain" value="{{ $contact->domain ?? old('domain') }}" placeholder="Domain URL" required>
                    </div>
                    <div class="col-md-6">
                        <label for="address" class="form-label">Busines Address<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" name="address" value="{{ $contact->address ?? old('address') }}" placeholder="Business Address" required>
                    </div>
                    <div class="col-md-6">
                        <label for="contact_no" class="form-label">Busines Call Line<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ $contact->contact_no ?? old('contact_no') }}" placeholder="Contact No" required>
                    </div>
                    <div class="col-md-6">
                        <label for="whatsapp_no" class="form-label">Whatsapp Line<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="whatsapp_no" name="whatsapp_no" value="{{ $contact->whatsapp_no ?? old('whatsapp_no') }}" placeholder="Whatsapp No" required>
                    </div>
                    <div class="col-md-6">
                        <label for="admin_email" class="form-label">Admin Email<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_email" name="admin_email" value="{{ $contact->admin_email ?? old('admin_email') }}" placeholder="Email" required>
                    </div>

                    <div class="col-md-6">
                        <label for="admin_notification_email" class="form-label">Admin Notification Email<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="admin_notification_email" name="admin_notification_email" value="{{ $contact->admin_notification_email ?? old('admin_notification_email') }}" placeholder="Admin Notification Email" required>
                    </div>

                    <div class="col-md-6">
                        <label for="download_android_app_link" class="form-label">Download Android App Link<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="download_android_app_link" name="download_android_app_link" value="{{ $contact->download_android_app_link ?? old('download_android_app_link') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="download_ios_app_link" class="form-label">Download IOS App Link<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="download_ios_app_link" name="download_ios_app_link" value="{{ $contact->download_ios_app_link ?? old('download_ios_app_link') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="seo_title" class="form-label">SEO Title<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="seo_title" name="seo_title" value="{{ $contact->seo_title ?? old('seo_title') }}" placeholder="SEO Title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="logo" class="form-label">Site Logo</label>
                        <input type="file" class="form-control" id="logo" name="logo">
                    </div>

                    <div class="col-md-6">
                        <label for="site_favicon" class="form-label">Site Favicon</label>
                        <input type="file" class="form-control" id="site_favicon" name="site_favicon">
                    </div>
                    <div class="col-md-6">
                        <label for="kyc_settings" class="form-label">Enable KYC Check <small>(Customers will not be able to use the system except they complete KYC)</small><span
                        class="text-danger">*</span></label>
                        
                        <select class="form-control" name="kyc_settings[status]" id="kyc_settings" required>
                            <option value="" selected>Select</option>
                            <option value="yes" {{ !empty($kyc_settings['status']) && $kyc_settings['status'] == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ !empty($kyc_settings['status']) && $kyc_settings['status'] == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="disable_registration" class="form-label">Disable New Registration<span
                        class="text-danger">*</span></label>
                        
                        <select class="form-control" name="disable_registration" id="disable_registration" required>
                            <option value="" selected>Select</option>
                            <option value="yes" {{ $contact->disable_registration == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ $contact->disable_registration == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="seo_title" class="form-label">Allowed domain emails for registration<span
                        class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="allowed_domain_emails_for_registration" name="allowed_domain_emails_for_registration" value="{{ $allowed_domains_for_registration ?? old('allowed_domain_emails_for_registration') }}" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="enable_mode_selector" class="form-label">Enable Mode Selector<span
                        class="text-danger">*</span></label>
                        <select class="form-control" name="enable_mode_selector" id="enable_mode_selector" required>
                            <option value="yes" {{ $contact->enable_mode_selector == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ $contact->enable_mode_selector == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="enable_switcher" class="form-label">Enable Switcher<span
                        class="text-danger">*</span></label>
                        <select class="form-control" name="enable_switcher" id="enable_switcher" required>
                            <option value="yes" {{ $contact->enable_switcher == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ $contact->enable_switcher == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="allow_transaction_refferal_system" class="form-label">Allow Refferal Commission system<span
                        class="text-danger">*</span></label>
                        
                        <select class="form-control" name="allow_transaction_refferal_system" id="allow_transaction_refferal_system" required>
                            <option value="" selected>Select</option>
                            <option value="yes" {{  $contact->allow_transaction_refferal_system == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{  $contact->allow_transaction_refferal_system == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="seo_title" class="form-label">SEO Description<span
                        class="text-danger">*</span></label>
                        <textarea name="seo_description" class="form-control" id="" cols="30" rows="10" value="{{ $contact->seo_description ?? old('seo_description') }}">
                            {{ $contact->seo_description ?? old('seo_description') }}
                        </textarea>
                    </div>
                </div>
            </div>

            <div class="card-header">
                <h5>Email Notification Settings</h5> <br>
            </div>
            {{-- {{dd($contact->allowed_email_notifications)}} --}}
            <div class="card-body">
                <div class="row gy-4">
                    @foreach (emailNotificationOptions() as $key=>$value)
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input ms-2" type="checkbox" name="allowed_email_notifications[]" id="email_notifications-{{$value}}" value="{{$value}}" {{!empty($contact->allowed_email_notifications) && in_array($value, $contact->allowed_email_notifications) ? 'checked' : ''}}>
                            <label for="email_notifications-{{$value}}"> &nbsp;Allow {{$key}}</label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-header">
                <h5>Extra Settings</h5> <br>
            </div>
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-md-12">
                        <div class="form-check">
                            <label for="charges_message"> &nbsp;Automated Payment Charges message</label>
                            <textarea name="extra_settings[charges_message]" class="form-control" id="charges_message" cols="30" rows="3">{{ $extra_settings['charges_message'] ?? old('charges_message') }}</textarea>
                        </div>
                    </div>

                </div>
                <div class="row mt-2">
                    <button class="btn btn-primary">Update</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('extra-js') 
@endsection
