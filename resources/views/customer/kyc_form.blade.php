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
                @include('includes.alerts')
            <div class="row p-3">
                <p>
                    Due to circular from the CBN, you are now required to enter your BVN or NIN in order to
                    facilitate free flow of the virtual account process. <br>
                    Please note that this is required for you to be able to use or generate Virtual Accounts and we don't in
                    anyway have access to Your Information. Thanks for choosing SwiftDataNg
                </p>
            </div>

            <form action="{{ route('kyc.update') }}" method="POST" enctype="multipart/form-data">
                {{-- <label class="label-title">BVN</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control {{ isset(auth()->user()->kycData->bvn) ? 'disabled-input' : ''}}" placeholder="{{auth()->user()->kyc_status == 0 && isset(auth()->user()->kycData->bvn) ? mask(auth()->user()->kycData->bvn) : 'Enter bvn'}}" id="contract" name="bvn" value="{{ old('bvn')}}">
                </div> --}}

                <label class="label-title">NIN</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control {{ isset(auth()->user()->kycData->nin) ? 'disabled-input' : ''}}" placeholder="{{auth()->user()->kyc_status == 0 && isset(auth()->user()->kycData->nin) ? mask(auth()->user()->kycData->nin) : 'Enter nin'}}" id="contract" name="nin" value="{{ old('nin')}}">
                </div>

                <label class="label-title">Means of Identification</label>
                <div class="input-group mb-3" required>
                    <select class="form-control" name="means_of_identification_type" id="kyc_data" required>
                        <option value="">Select</option>
                        <option value="international_passport" {{ isset(auth()->user()->kycData->means_of_identification_type) && auth()->user()->kycData->means_of_identification_type == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                        <option value="drivers_license" {{ isset(auth()->user()->kycData->means_of_identification_type) && auth()->user()->kycData->means_of_identification_type == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                        <option value="electricity_bill" {{ isset(auth()->user()->kycData->means_of_identification_type) && auth()->user()->kycData->means_of_identification_type == 'electricity_bill' ? 'selected' : '' }}>Electricity Bill</option>
                        <option value="nin_slip" {{ isset(auth()->user()->kycData->means_of_identification_type) && auth()->user()->kycData->means_of_identification_type == 'nin_slip' ? 'selected' : '' }}>NIN Slip</option>
                        <option value="voters_card" {{ isset(auth()->user()->kycData->means_of_identification_type) && auth()->user()->kycData->means_of_identification_type == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
                    </select>
                </div>
                <label class="label-title">Means of Identification Document (JPEG, JPG Only, 1mb max)</label>
                <div class="input-group mb-3">
                    <input type="file" class="form-control" name="means_of_identification_document" accept=".jpeg, .jpg, image/jpeg" id="means_of_identification_document">
                </div>

                <script>
                    document.getElementById('means_of_identification_document').addEventListener('change', function() {
                        const file = this.files[0];
                        if (file && file.size > 1024 * 1024) { 
                            alert("File size must be less than or equal to 1 MB.");
                            this.value = ''; 
                        }
                    });
                </script>

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