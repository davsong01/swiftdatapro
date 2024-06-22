@extends('includes.customer_dashboard.app')
@section('header')
    <div class="col-xl-12">
        <div class="alert" style="background-color: #fff;color: blue;">
            <div id="response" style="text-align: center;">
                <h4>Cheap Airtime VTU</h4>
                <p style="color: red;font-weight: bold;"></p>
            </div>
        </div>
    </div>
@endsection
@section('body')
    <div class="col-xl-12">
        <div class="alert" id="temp"
            style="box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;">
            <div id="response" style="text-align: left;">
                <span><button class="btn btn-dark" onclick="FundWallet()" style="font-size:10px">Fund Wallet</button></span>
                <br /><br />
            </div>
        </div>
    </div>

    <div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">

        <!-- start -->


        <div id="response"></div>
        <div id="extra"
            style="margin:5px 0;background: black;color: yellow;padding: 20px;margin-bottom: 15px;font-size: initial; display:none">
        </div>

        {{-- <div id="how_to_load" style="display:none;background: #d5f4d5;padding: 10px;margin-bottom: 10px;">
        <div class="col-lg-12 me">
            <div class="form-group">
                <label class="small mb-1" for="amount" style="color: #000000">To Load MTN Data Card</label>
                <ol style="margin-left: 15px;">
                    <li style=" list-style: decimal;">Dial: *460*6*1#</li>
                    <li style="list-style: decimal;">Insert the pin you generated from your swiftdatang account (make
                        sure you include the letter S that ends the number)</li>
                    <li style="list-style: decimal;">Recharge successful</li>
                </ol>
                To Check Data coupon balance: Dial *323*4#
            </div>
        </div>
    </div> --}}

        <form id="form-data" method="POST" action="{{ route('initialize.airtime2cashtransaction') }}">
            @csrf
            <div class="input-group mb-3">
                <span class="input-group-text"><i style="font-weight: bold" class="fa-solid fa-wifi"></i></span>
                <input type="hidden" id="network_discount">
                <select class="form-control" id="product" required name="product">
                    <option value="">--Choose--</option>
                    @foreach ($category->products as $product)
                        @if (($product->has_variations == 'yes' && $product->variations->count() > 0) || $product->has_variations == 'no')
                            <option data-fixed_price="{{ $product->fixed_price }}"
                                data-allow_quantity="{{ $product->allow_quantity }}" data-max="{{ $product->max }}"
                                data-min="{{ $product->min }}" data-has_variations="{{ $product->has_variations }}"
                                value="{{ $product->id }}">{{ $product->display_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <br>
            <div class="input-group mb-3">
                <span class="input-group-text"><i style="font-weight: bold" class="fas fa-phone"></i></span>
                <input type="tel" class="form-control" placeholder="Mobile No." id="mobile_no" name="mobile_no" required>
            </div>
            <br>
            <div class="input-group">
                <span class="input-group-text"><span
                        style="font-weight: bold;padding: 0.1px 3.2px;;">{{ settings()->currency }}</span>
                </span>
                <input type="number" class="form-control" name="amount" placeholder="Enter Amount" onkeyup="calDiscount()"
                    id="amount">
            </div>
            <div>
              <small style="color: red;font-weight: bold;" id="valAmount" style="display:none"></small> <br>
            <small class=""><a href="#checkbal">Learn how to transfer</a></small>
            </div>

            <br>
            <small class="">Forget Pin ? <a target="_blank" href="{{ route('pin.reset') }}">Reset Here</a></small>
            <div class="input-group mb-3">
                <span class="input-group-text"><i style="font-weight: bold" class="fa fa-unlock-alt"></i></span>
                <input type="password" name="security_pin" class="form-control" placeholder="Security PIN" id="security_pin" required>
            </div>
            <button class="btn btn-success btn-block" style="width: 100%" id="submitBtn">BUY NOW</button>
            <br>
            <br>
        </form>
        <hr/>
        <div id="checkbal">
            <h5 style="font-weight:bold">HOW TO TRANSFER AIRTIME TO ANOTHER NUMBER</h5>
            <div style="color:#940798">
                <img src="{{ asset('backend/img/mtn.png')}}" style="border-radius: 50%;" height="30px"> <br />
                TO TRANSFER MTN AIRTIME <br />
                <p>*600*recipient-number*amount*pin#</p>

                TO CHANGE MTN TRANSFER PIN <br />
                <p>*600*default-pin*new-pin*new-pin#</p>
            </div>
            <hr />

            <div style="color:#940798">
                <img src="{{ asset('backend/img/glo.png')}}" style="border-radius: 50%;" height="30px"> <br />
                TO TRANSFER GLO AIRTIME <br />
                <p>*131*recipient-number*amount*pin#</p>

                TO CHANGE GLO TRANSFER PIN <br />
                <p>*132*00000*new-pin*new-pin#</p>
            </div>
            <hr />

            <div style="color:#940798">
                <img src="{{ asset('backend/img/airtel.png')}}" style="border-radius: 50%;" height="30px"> <br />
                TO TRANSFER AIRTEL AIRTIME <br />
                <p>*432*1#</p>

                TO CHANGE AIRTEL TRANSFER PIN <br />
                <p>*432*3# (Default Pin: 1234)</p>
            </div>
            <hr />

            <div style="color:#940798">
                <img src="{{ asset('backend/img/mobile.png') }}" style="border-radius: 50%;" height="30px"> <br />
                TO TRANSFER 9MOBILE AIRTIME <br />
                <p>*223*pin*amount*number#</p>

                TO CHANGE 9MOBILE TRANSFER PIN <br />
                <p>*247*default-pin*new-pin# (E.g *247*0000*new-pin#)</p>
            </div>


        </div>
    </div>

    <div id="myModal2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="myModalLabel"></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        function calDiscount() {
            var has_variation = $('#product').find(':selected').data('has_variations');
            var product_id = $('#product').val();
            var variation_id = $('#variation').val();
            var allow_quantity = $('#product').find(':selected').data('allow_quantity');
            var max = $('#product').find(':selected').data('max');
            var min = $('#product').find(':selected').data('min');
            var quantity_graduation = $('#product').find(':selected').data('quantity_graduation');
            var fixed_price = $('#product').find(':selected').data('fixed_price');

            var amount = $('#amount').val();
            if (amount > 0) {
                if (has_variation == 'yes') {
                    formData = {
                      variation_id: variation_id,
                      amount: amount
                    };
                } else {
                    formData = {
                      product_id: product_id,
                      amount: amount
                    };
                }

                $.ajax({
                    url: "{{ url('customer-get-charges') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function(data) {
                        if (data.new_amount > 0) {
                            $('#valAmount').show();
                            $("#valAmount").text("You will recieve " + data.display_value);
                        } else {
                            $('#valAmount').hide();
                            $('#valAmount').text("");
                        }
                    }
                });
            } else {
                $('#valAmount').hide();
                $('#valAmount').text("");
            }
        };

        // $("#form-data").submit(function(event) {
        //     var formData = {
        //         product: $("#product").val(),
        //         amount: $("#amount").val(),
        //         mobile_no: $("#mobile_no").val(),
        //         security_pin: $("#security_pin").val(),
        //         req_token: $("#req_token").val(),
        //     };

        //     // $.LoadingOverlay("show");
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.ajax({
        //         url: "{{ route('initialize.airtime2cashtransaction') }}",
        //         method: 'POST',
        //         dataType: 'json',
        //         data: formData,
        //         success: function(response_fd) {
        //             $.LoadingOverlay("hide");
        //             if (response_fd.status == 'success') {
        //                 $("#successModal").modal("show");
        //                 $("#successModal .modal-body").html(response_fd.message);
        //                 setTimeout(function() {
        //                     window.location.reload();
        //                 }, 2000);
        //             } else {
        //                 $("#errorModal").modal("show");
        //                 $("#errorModal .modal-body").html(response_fd.message);
        //             }
        //         }
        //     });

        //     event.preventDefault();
        // });

        function resetAmount() {
            $('#amount').val("");
            $("#amount").attr({
                "max": "",
                "min": "",
                "required": false,
            });
        }
    </script>
@endsection
