@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-12">
    <div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
        <div class="page_title_left">
            <h3 class="f_s_25 f_w_700 dark_text">Update Kyc Info</h3>
            <ol class="breadcrumb page_bradcam mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Profile</a></li>
                <li class="breadcrumb-item active">Default</li>
            </ol>
        </div>

    </div>
</div>
@endsection
@section('body')
<div class="col-xl-12">
    <h3>Kyc Info</h3>
</div>
<div class="col-xl-12">
    <div class="white_card card_height_100 mb_30 sales_card_wrapper">
        @if($kyc_status == 0)
        <div class="alert" style="background-color: #fff;">
            <div class="row p-5">
                <p>
                    Due to recent circular from the CBN, you are now required to enter your BVN or NIN in order to
                    facilitate free flow of the virtual account process. <br>
                    Please note that this is required for you to be able to use or generate Virtual Accounts and we don't in
                    anyway have access to Your Information. Thanks for choosing SwiftDataNg
                </p>
            </div>
            <form action="{{ route('kyc.update') }}" method="POST" enctype="multipart/form-data">
                <label class="label-title">BVN</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" 
                        name="kyc_data[bvn]" 
                        value="{{ (auth()->user()->kyc_status == 0 || !isset($kyc_data['bvn'])) ? old('kyc_data.bvn') : mask($kyc_data['bvn']) }}">
                    <input type="hidden" class="form-control" 
                        name="kyc_data[bvn]" 
                        value="{{ isset($kyc_data['bvn']) ? $kyc_data['bvn'] : old('kyc_data.bvn') }}">
                </div>

                <label class="label-title">NIN</label>
                <div class="input-group mb-3">
                    <input type="text" id="masked-nin" readonly class="form-control pr-4" value="{{ auth()->user()->kyc_status == 0 && !empty(auth()->user()->kycData->nin) ? masked(auth()->user()->kycData->nin) : '' }}">       
                    <input type="hidden" id="nin" value="{{ auth()->user()->kycData->nin ?? '' }}">
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label class="col-form-label">NIN</label>
                        <div class="col-md-12">
                            <div class="position-relative">
                                <input type="text" id="masked-paystack-hashkey" readonly class="form-control pr-4" value="{{ !empty($setting->provider_payment_settings['paystack']['hash_key']) ? masked($setting->provider_payment_settings['paystack']['hash_key']) : '' }}">
                                
                                <input type="hidden" id="paystack-hashkey" value="{{ $setting->provider_payment_settings['paystack']['hash_key'] ?? '' }}">
                                
                                <span class="position-absolute copy-btn" data-target="#paystack-hashkey" style="right: 10px; top: 50%; transform: translateY(-50%); cursor:pointer">
                                    <i class="fa fas fa-solid fa-copy"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
{{-- 
                <div class="input-group mb-3">
                    <input type="text" class="form-control" 
                        value="{{ isset($kyc_data['nin']) && (auth()->user()->kyc_status == 0) ? old('kyc_data.nin') : mask($kyc_data['nin']) }}">
                    <input type="hidden" class="form-control" name="kyc_data[nin]" 
                        value="{{ isset($kyc_data['nin']) && (auth()->user()->kyc_status == 0) ? old('kyc_data.nin') : mask($kyc_data['nin']) }}">
                </div> --}}

                <label class="label-title">Means of Identification Document</label>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="kyc_data[means_of_identification_document]">
                </div>



                <label class="label-title">Means of Identification</label>
                <div class="input-group mb-3" required>
                    <select class="form-control" name="kyc_data[means_of_identification]" id="kyc_data" required>
                        <option value="">Select</option>
                        <option value="international_passport" {{ isset($kyc_data['means_of_identification']) && $kyc_data['means_of_identification'] == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                        <option value="drivers_license" {{ isset($kyc_data['means_of_identification']) && $kyc_data['means_of_identification'] == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                        <option value="electricity_bill" {{ isset($kyc_data['means_of_identification']) && $kyc_data['means_of_identification'] == 'electricity_bill' ? 'selected' : '' }}>Electricity Bill</option>
                        <option value="nin_slip" {{ isset($kyc_data['means_of_identification']) && $kyc_data['means_of_identification'] == 'nin_slip' ? 'selected' : '' }}>NIN Slip</option>
                        <option value="voters_card" {{ isset($kyc_data['means_of_identification']) && $kyc_data['means_of_identification'] == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
                    </select>
                </div>
                <label class="label-title">Means of Identification Document</label>
                <div class="input-group mb-3">
                    <!-- Display the current file name if there is an existing document -->
                    @if(isset($kyc_data['means_of_identification_document']))
                        <input type="text" class="form-control" value="{{ basename($kyc_data['means_of_identification_document']) }}" readonly>
                    @endif
                    <!-- Allow users to upload a new document if needed -->
                    <input type="file" class="form-control" name="kyc_data[means_of_identification_document]">
                    <!-- Store the existing document path in a hidden input to retain it if not updated -->
                    @if(isset($kyc_data['means_of_identification_document']))
                        <input type="hidden" name="kyc_data[existing_means_of_identification_document]" value="{{ $kyc_data['means_of_identification_document'] }}">
                    @endif
                </div>

                @if(auth()->user()->kyc_status != 1)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="i-agree" checked required>
                        <label class="form-check-label" for="flexCheckChecked">
                            I certify that the data supplied above is correct and I consent to give this data out.
                        </label>
                    </div>
                    <button class="btn btn-primary" style="width: 100%">Submit</button>

                    <br>
                    <br>
                @else
                    <p style="margin:top:10px;color:green"><strong><i class="fa fa-check" aria-hidden="true"></i>
                            KYC Verified!</strong></p>
                @endif
                @csrf
                

            </form>
            <hr>

            {{-- <input type="checkbox" name=""> I want to deactivate my account.
            <br>
            <button class="btn btn-danger">Deactivate</button> --}}
        </div>
        @else   
        <div class="alert" style="background-color: #fff;">
            <p>You have successfully updated KYC info, no other action is required from you.</p>
        </div>
        @endif
    </div>
</div>
@endsection