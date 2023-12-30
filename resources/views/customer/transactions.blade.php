@extends('includes.customer_dashboard.app')
<?php 
    use App\Models\WebSetting;
    $web_settings = WebSetting::first();
?>
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Add Money</h4>
            <p style="color: red;font-weight: bold;">Transfer money into one of the reserved account below and your
                wallet will be automatically credited.</p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="container-fluid p-0 sm_padding_15px">
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <div class="alert" style="background-color: #fff;color: #000;">
                    <input type="text" id="search_data" class="form-control" placeholder="Search Mobile No.">
                    <hr />
                    <button class="btn btn-danger" onclick="findRec()">Search</button>
                </div>
                <div id="transactionsDiv">
                </div>
                <br>
                <div id="loadMoreDiv">
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function findRec() {
        var search_data = $("#search_data").val();
        $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url('/') }}/customer/transactions?search_data=' + search_data,
            dataType: 'json',
            success: function (transactionsRes) {
                $.LoadingOverlay("hide");
                $("#transactionsDiv").html(transactionsRes.message);
                $("#loadMoreDiv").hide();
            }
        });
    }

    function rceipt(id) {
        $.LoadingOverlay("show");
        window.location.href = "/reference?reference=" + id;
    }
</script>
@if(!isset($type))
    <script type="text/javascript">
        $(document).ready(function () {
            $.LoadingOverlay("show");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/') }}/customer/transactions?load_id=0&&status=all',
                dataType: 'json',
                success: function (transactionsRes) {
                    $.LoadingOverlay("hide");
                    $("#transactionsDiv").html(transactionsRes.message);
                    $("#loadMoreDiv").html(transactionsRes.ctrlButton);
                }
            });
        });

        function showMore(id) {
            $.LoadingOverlay("show");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('/') }}/customer/transactions?load_id=' + id,
                dataType: 'json',
                success: function (transactionsRes) {
                    $.LoadingOverlay("hide");
                    $("#transactionsDiv").html(transactionsRes.message);
                    $("#loadMoreDiv").html(transactionsRes.ctrlButton);
                }
            });
        }

    </script>
@endif
@if(isset($type) & $type == 'success')
    <script type="text/javascript">
        $(document).ready(function () {
            $.LoadingOverlay("show");
            $.ajax({
                url: '{{ url('/') }}/customer/transactions?load_id=500&type=success',
                dataType: 'json',
                success: function (transactionsRes) {
                    $.LoadingOverlay("hide");
                    $("#transactionsDiv").html(transactionsRes.message);
                    $("#loadMoreDiv").html(transactionsRes.ctrlButton);
                }
            });
        });

        function showMore(id) {
            $.LoadingOverlay("show");
            $.ajax({
                url: '{{ url('/') }}/customer/transactions?type=success&load_id=' + id,
                dataType: 'json',
                success: function (transactionsRes) {
                    $.LoadingOverlay("hide");
                    $("#transactionsDiv").html(transactionsRes.message);
                    $("#loadMoreDiv").html(transactionsRes.ctrlButton);
                }
            });
        }
    </script>

@endif

@if(isset($type) & $type == 'failed')
<script type="text/javascript">
	$(document).ready(function () {
	    $.LoadingOverlay("show");
	    $.ajax({
	        url: '{{ url('/') }}/customer/transactions?type=failed&load_id=500',
	        dataType: 'json',
	        success: function (transactionsRes) {
	            $.LoadingOverlay("hide");
	            $("#transactionsDiv").html(transactionsRes.message);
	            $("#loadMoreDiv").html(transactionsRes.ctrlButton);
	        }
	    });
	});

	function showMore(id) {
	    $.LoadingOverlay("show");
	    $.ajax({
	        url: '{{ url('/') }}/customer/transactions?type=failed&load_id=' + id,
	        dataType: 'json',
	        success: function (transactionsRes) {
	            $.LoadingOverlay("hide");
	            $("#transactionsDiv").html(transactionsRes.message);
	            $("#loadMoreDiv").html(transactionsRes.ctrlButton);
	        }
	    });
	}

</script>

@endif
@endsection
