@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title', 'View '.$customer->username)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('pending.kyc')}}">All Pending KYC</a></li>
@endsection
@section('breadcrumb-active')
<li class="breadcrumb-item active" aria-current="page">{{$customer->username}}</li>

@endsection
@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card custom-card">
            <div class="card-body">
                <div class="card-title">
                <a onclick="return confirm('Are you sure you want to approve?');" href="{{ route('approve.kyc', $customer->id) }}" class="btn btn-success"><i class="fa fa-check"></i>Approve KYC</a>
                @include('includes.alerts')
            </div>
                <form action="{{ route('pending.kyc.update', $customer->kycData->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-md-6">
                            <label class="form-label" for="name">Name</label>
                            <input type="text" class="form-control" value="{{ $customer->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="ref_code">Ref Code</label>
                            <input type="text" class="form-control"
                                placeholder="Ref code" value="{{ $customer->ref_code }}"
                                disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bvn">BVN</label>
                            <input name="bvn" type="text" class="form-control" placeholder="BVN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->bvn : mask($customer->kycData->bvn) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nin">NIN</label>
                            <input type="text" name="nin" class="form-control"
                                placeholder="NIN" value="{{ in_array('view.sensitive.data', allowedPermissions()) ? $customer->kycData->nin : mask($customer->kycData->nin) }}" {{ !in_array('view.sensitive.data', allowedPermissions()) ? 'readonly' : '' }}>
                        </div>
                       
                        <div class="col-md-6">
                            <label class="form-label" for="gender">Means of Identification Type</label>
                            <select class="form-control" name="means_of_identification_type" id="means_of_identification_type" required>
                                <option value="">Select</option>
                                <option value="international_passport" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'international_passport' ? 'selected' : '' }}>International Passport</option>
                                <option value="drivers_license" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'drivers_license' ? 'selected' : '' }}>Driver's License</option>
                                <option value="electricity_bill" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'electricity_bill' ? 'selected' : '' }}>Electricity Bill</option>
                                <option value="nin_slip" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'nin_slip' ? 'selected' : '' }}>NIN Slip</option>
                                <option value="voters_card" {{ isset($customer->kycData->means_of_identification_type) && $customer->kycData->means_of_identification_type == 'voters_card' ? 'selected' : '' }}>Voter's Card</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-2">
                                    <img style="width: 70px;" src="{{asset($customer->kycData->means_of_identification_document)}}" alt="">
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label">Means of Identification Document (JPEG, JPG Only, 1mb max)</label>
                                    <div class="input-group mb-3">
                                        <input type="file" class="form-control" name="means_of_identification_document" accept=".jpeg, .jpg, image/jpeg" id="means_of_identification_document">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button type="Save" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@section('extra-js') 
<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<!-- Datatables Cdn -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- Internal Datatables JS -->
<script src="{{asset('new/assets/js/datatables.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#masterCheckbox').on('change', function() {
            $('.childCheckbox').prop('checked', this.checked);
        });

        $('.childCheckbox').on('change', function() {
            $('#masterCheckbox').prop('checked', $('.childCheckbox:checked').length === $('.childCheckbox').length);
        });
    });
</script>
@endsection

