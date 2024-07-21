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
    <h3>Update Kyc Info</h3>
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
            <form action="{{ route('kyc.update') }}" method="POST">
                @if(empty(auth()->user()->bvn))
                    <label class="label-title">Bvn</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="bvn"
                            value="{{ auth()->user()->bvn ?? old('bvn') }}">
                    </div>
                @endif

                @if(empty(auth()->user()->nin))
                    <label class="label-title">Nin</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="nin"
                            value="{{ auth()->user()->nin ?? old('nin') }}">
                    </div>
                @endif

                @if(empty(auth()->user()->bvn) || empty(auth()->user()->nin))

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="i-agree" checked required>
                        <label class="form-check-label" for="flexCheckChecked">
                            I certify that the data supplied above is correct and I consent to give this data out.
                        </label>
                    </div>
                    <button class="btn btn-primary" style="width: 100%">UPDATE PROFILE</button>

                    <br>
                    <br>
                @else
                    <p style="margin:top:10px;color:green"><strong><i class="fa fa-check" aria-hidden="true"></i>
                            Account update complete</strong></p>
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
            <p>You have successfully updated account info, no other action is required from you.</p>
        </div>
        @endif
    </div>
</div>
@endsection