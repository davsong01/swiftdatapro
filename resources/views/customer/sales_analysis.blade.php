@extends('includes.customer_dashboard.app')
@section('header')
<div class="col-xl-12">
    <div class="alert" style="background-color: #fff;color: blue;">
        <div id="response" style="text-align: center;">
            <h4>Sales Summary</h4>
            <p style="color: red;font-weight: bold;"></p>
        </div>
    </div>
</div>

@endsection
@section('body')
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="row">
        <div class="col-md-6">
            <label for="start">Start Date</label>
            <input class="form-control" type="datetime-local" value="{{ old('start') }}" id="start" required>
        </div>
        <div class="col-md-6">
            <label for="end">End Date</label>
            <input class="form-control" type="datetime-local" value="{{ old('end') }}" id="end" required>
        </div>
    </div>
    <div class="row">
        <hr />
        <button class="btn btn-block btn-success" onclick="querSearch()" style="width: 100%">Search</button>
        <br>
        <br>
        <div style="padding:20px" id="transactionsDiv">{!! $analysis !!}</div>
    </div>
</div>
@endsection
@section('scripts')
    <script type="text/javascript">
    function querSearch(id) {
        var start = $("#start").val();
        var end = $("#end").val();
       
        // $.LoadingOverlay("show");
        $.ajax({
            url: '?start=' + start + '&end=' + end,
            dataType: 'json',
            success: function (transactionsRes) {
                $.LoadingOverlay("hide");
                $("#transactionsDiv").css("color", "red");
                $("#transactionsDiv").html(transactionsRes.message);
            }
        });
    } 

</script>
@endsection