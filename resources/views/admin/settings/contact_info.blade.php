@extends('layouts.new.app')
@section('extra-css')
@endsection
@section('title', 'Edit Contact Settings')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('settings.update')}}">Contact Settings</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">Edit Contact Settings</li>
@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-header">
                <h5>Edit Contact Settings</h5> <br>
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
                        <label for="seo_title" class="form-label">SEO Description<span
                        class="text-danger">*</span></label>
                        <textarea name="seo_description" class="form-control" id="" cols="30" rows="10" value="{{ $contact->seo_description ?? old('seo_description') }}">
                            {{ $contact->seo_description ?? old('seo_description') }}
                        </textarea>
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
