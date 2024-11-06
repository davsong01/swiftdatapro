@extends('layouts.new.app')
@section('extra-css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
@endsection
@section('title')
 View {{$customer->username}}&nbsp; <span class="badge bg-{{ $customer->kyc_status == 1 ? 'success' : ($customer->kyc_status == 0 ? 'warning' : 'danger') }} fw-semibold float-end">{{ $customer->kyc_status == 1 ? 'Active' : ($customer->kyc_status == 0 ? 'Inactive' : 'Declined') }}</span>
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('pending.kyc')}}">All Pending KYC</a> </li>
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
                @if(hasAccess('kyc.approve'))
                <a onclick="return confirm('Are you sure you want to approve?');" href="{{ route('approve.kyc', $customer->kycData->id) }}" class="btn btn-success"><i class="fa fa-check"></i>Approve KYC</a>
                
                    @if($customer->kyc_status == 0)
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#decline-kyc">Decline KYC</button>

                        <div class="modal fade" id="decline-kyc" tabindex="-1" aria-labelledby="decline-kycLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('decline.kyc', $customer->kycData->id) }}" method="POST" enctype="multipart/form-data">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title" id="decline-kyc">Decline KYC</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            @csrf
                                            <div class="row gy-4">
                                                <div class="col-md-12">
                                                    <label class="form-label" for="name">Reason</label>
                                                    <textarea name="reason" class="form-control" id="" cols="30" rows="10"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
                @include('includes.alerts')
            </div>
            @include('admin.customers.single-pending-kyc-form')
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

