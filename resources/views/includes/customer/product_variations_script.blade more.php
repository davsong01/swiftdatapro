{{-- <script type="text/javascript">
    var variations = [];

    $("#network_module").change(function() {
        resetAmount();
        var has_variation = $('#network_module').find(':selected').data('has_variations');
        var product_id = $('#network_module').val();
        var variation_id = $('#variation').val();
        var allow_quantity = $('#network_module').find(':selected').data('allow_quantity');
        var max = $('#network_module').find(':selected').data('max');
        var min = $('#network_module').find(':selected').data('min');
        var quantity_graduation = $('#network_module').find(':selected').data('quantity_graduation');
        var fixed_price = $('#network_module').find(':selected').data('fixed_price');

        if ($("#network_module").val() != "" && has_variation == 'yes') {
            var network_module = $("#network_module").val();
            $.LoadingOverlay("show");
            $.ajax({
                url: "{{ url('customer-get-variations') }}/" + product,
                method: 'GET',
                success: function(RES_data) {
                    // $.LoadingOverlay("hide");
                    // $("#network_discount").val(RES_data);
                    if (data && data.length > 0) {
                        for (t = 0; t <= data.length; t++) {
                            $('#variation').append(`<option value="${data[t].id}" data-min="${data[t].min}" data-max="${data[t].max}" data-isFixed="${data[t].fixed_price}" data-amount="${data[t].system_price}"> ${data[t].system_name}</option>`);

                            variations.push({
                                "id": data[t].id,
                                "verifiable": data[t].verifiable,
                                "unique_element": data[t].unique_element,
                                "max": data[t].max,
                                "min": data[t].min,
                                "fixedPrice": data[t].fixed_price,
                                "variation_amount": data[t].system_price,
                                "discount": data[t].discount.discount > 0 ? data[t].discount.message : '',
                                "discount_rate":data[t].discount.discount
                            });
                        }
                    }
                }

                $("#variation-div").show();
                $("#variation").show();
            })
        }
    })

    function calDiscount() {
        var has_variation = $('#network_module').find(':selected').data('has_variations');
        var product_id = $('#network_module').val();
        var variation_id = $('#variation').val();
        var allow_quantity = $('#network_module').find(':selected').data('allow_quantity');
        var max = $('#network_module').find(':selected').data('max');
        var min = $('#network_module').find(':selected').data('min');
        var quantity_graduation = $('#network_module').find(':selected').data('quantity_graduation');
        var fixed_price = $('#network_module').find(':selected').data('fixed_price');

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
                url: "{{ url('customer-get-discount') }}",
                method: 'POST',
                dataType: 'json',
                data: formData,
                success: function(data) {
                    if (data.new_amount > 0) {
                        $('#valAmount').show();
                        $("#valAmount").text("You will pay " + data.display_value);
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

    $("#form-data").submit(function(event) {
        var formData = {
            network_module: $("#network_module").val(),
            amount: $("#amount").val(),
            mobile_no: $("#mobile_no").val(),
            security_pin: $("#security_pin").val(),
            req_token: $("#req_token").val(),
        };

        $.LoadingOverlay("show");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '/buy/airtime',
            method: 'POST',
            dataType: 'json',
            data: formData,
            success: function(response_fd) {
                $.LoadingOverlay("hide");
                if (response_fd.status == 'success') {
                    $("#successModal").modal("show");
                    $("#successModal .modal-body").html(response_fd.message);
                    setTimeout(function() {
                        window.location.reload();
                    }, 2000);
                } else {
                    $("#errorModal").modal("show");
                    $("#errorModal .modal-body").html(response_fd.message);
                }
            }
        });

        event.preventDefault();
    });

    function resetAmount() {
        $('#valAmount').hide()
        $('#amount').val("");
        $("#amount").attr({
            "max": "",
            "min": "",
            "required": false,
        });
    }
</script> --}}
<script>
    function verify(e){
        $("#amount").attr({
            "required": true,
        });

        var unique_element = $("#unique_element").val();
        var category_slug = $("#category_slug").val();

        if(unique_element == ''){
            alert("Please enter biller to verify");
            return;
        }

        $.LoadingOverlay("show");
        $('#verify-modal').modal('hide');
        var amount = $('#amount').val();

        var url = "{{ url('customer-verify') }}";
        var element = "{{ $category->unique_element }}";
        var allow_subscription_type = $('#network_module').find(':selected').data('allow_subscription_type');

        var formData =  {
            category_id: {{ $category->id }},
            unique_element: $("#unique_element").val(),
            variation: $("#variation").val(),
            product_id: $("#network_module").val(),
        };

        $.ajax({
            url: url,
            method: 'POST',
            dataType: 'json',
            data:formData,

            success: function (data) {
                $.LoadingOverlay("hide");
                $("#verify-title").html(data.title);
                $("#verify-details").html(data.message);

                if(data.status == '1'){
                    if(allow_subscription_type == 'yes' && element == 'iuc_number'){
                        $('#bouquet').append(`<option value="change" data-amount="${amount}">Change Bouquet</option><option data-amount="${data.renewal_amount}" value="renew">Renew Bouquet</option>`);
                        $("#bouquet-div").show();
                        $("#bouquet").attr({
                            "required":true
                        });
                    }else{
                        $("#bouquet-div").hide();
                        $("#bouquet").attr({
                            "required":false
                        });
                    }

                    $("#continue_payment").show();

                }else{
                    $("#continue_payment").hide();
                }
                $('#verify-modal').modal('show');
            }
        });
    }

    function submitForm(){
        var inputs = document.getElementById("initialize").getElementsByTagName("input");
        // Loop through each input and perform validation
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];

            // Check if the input is required and empty
            if (input.hasAttribute("required") && input.value.trim() === "") {
                alert("Please fill all inputs");
                return;
            }
        }

        $.LoadingOverlay("show");
        document.forms["initialize"].submit();
    }

    $("#amount").keyup(function(){        
        var has_variation = $('#network_module').find(':selected').data('has_variation');
        var product_id = $('#network_module').val();
        var variation_id = $('#variation').val();
        var amount = $('#amount').val();
       
        if(amount > 0){
            if(has_variation == 'yes'){
                formData = {
                    variation_id:variation_id,
                    amount:amount
                };
            }else{
                formData = {
                    product_id:product_id,
                    amount:amount
                };
            }
            
            $.ajax({
                url: "{{ url('customer-get-discount') }}",
                method: 'POST',
                dataType: 'json',
                data:formData,
                success: function (data) {
                    if(data.discount > 0){
                        $('#discount').show();
                        var amount = $('#discount').html(data.message);
                    }
                }
            });
        }else{
            $('#discount').hide()
        }
    });

    // $(document).ready(function () {
        var variations = [];
        $('#network_module').on('change', function () {
            var fixed_price = $('#network_module').find(':selected').data('fixed_price');
            var has_variation = $('#network_module').find(':selected').data('has_variation');
            var system_price = $('#network_module').find(':selected').data('system_price');
            var allow_quantity = $('#network_module').find(':selected').data('allow_quantity');
            var max = $('#network_module').find(':selected').data('max');
            var min = $('#network_module').find(':selected').data('min');
            var quantity_graduation = $('#network_module').find(':selected').data('quantity_graduation');
            var product = $('#network_module').val();
            
            $("#verify-link").hide();
            $(".unique_element_div").hide();

            if (product == '') {
                $('#variation-div').hide();
                $('#amount-div').hide();
                $('#quantity-div').hide();
                $('#amount').hide();
                $('#quantity').hide();
                return;
            }

            // var image = $('#network_module').find(':selected').data('image');
            // var title = $('#network_module').find(':selected').data('name');
            // var description = $('#network_module').find(':selected').data('description');
            var bulk = $('#network_module').find(':selected').data('bulk');
            if (bulk == 'yes') {
                $("#bulk-purchase").show();
            } else {
                $("#bulk-purchase").hide();
            }

            // $('#network_module-image-div').show();
            // $("#network_module-image").attr("src", image);
            // $("#network_module-title").html(title);
            // $("#network_module-description").html(description);
            
            if(has_variation == 'yes'){
                $('#variation-div').show();
                $('#variation').show();
                $('#amount-div').hide();

                $("#amount").prop('readonly', false);
                $("#amount").val('');

                $('#variation').find('option').not(':first').remove();

                $.ajax({
                    url: "{{ url('customer-get-variations') }}/" + product,
                    success: function (data) {
                        if (data && data.length > 0) {
                            for (t = 0; t <= data.length; t++) {
                                $('#variation').append(`<option value="${data[t].id}" data-min="${data[t].min}" data-max="${data[t].max}" data-isFixed="${data[t].fixed_price}" data-amount="${data[t].system_price}"> ${data[t].system_name}</option>`);

                                variations.push({
                                    "id": data[t].id,
                                    "verifiable": data[t].verifiable,
                                    "unique_element": data[t].unique_element,
                                    "max": data[t].max,
                                    "min": data[t].min,
                                    "fixedPrice": data[t].fixed_price,
                                    "variation_amount": data[t].system_price,
                                    "discount": data[t].discount.discount > 0 ? data[t].discount.message : '',
                                    "discount_rate":data[t].discount.discount
                                });
                            }
                        }
                    }
                });
            }else{
                $('#amount-div').show();
                $('#amount').show();
                $('#quantity option:not(:first)').remove();
                $("#variation").hide();
                $("#variation-div").hide();

                if(fixed_price == 'yes'){
                    $("#amount").attr({
                        "max": "",
                        "min": ""
                    });

                    $('#amount').val(system_price);
                    $("#amount").attr({
                        "readonly": "true",
                    });
                }else{
                    $("#amount").prop('readonly', false);
                    $("#amount").attr({
                        "max": max,
                        "min": min,
                    });
                }
            }

            if(allow_quantity == 'yes'){
                $('#quantity-div').show();
                $('#quantity').show();
                var data = quantity_graduation.split(",");

                if (data && data.length > 0) {
                    for (t = 0; t < data.length; t++) {
                        $('#quantity').append(`<option value="${data[t]}"> ${data[t]}</option>`);
                    }
                }
            }else{
                $('#quantity-div').hide();
                $('#quantity').hide();
            }

        });

        $('#bouquet').on('change', function (e) {
            var amount = $('#bouquet').find(':selected').data('amount');
            var old_amount = $('#amount').val();
            if($('#bouquet') == 'change'){
                $('#amount').val(old_amount);
            }else{
                $('#amount').val(amount);
            }
        });

        $('#variation').on('change', function (e) {
            $('#amount-div').show();
            $('#amount').show();
            $("#bouquet-div").hide();
            $("#bouquet").attr({
                "required":false
            });
            // $("#unique_element").hide();

            var v = e.target.value;
            var selected = variations.filter((item) => {
                return item.id == v;
            });
            if (selected[0].verifiable == 'yes') {
                showAllUniqueElement();
                $("#verify-link").html('Verify '+ selected[0].unique_element.replace("_", " "));
                if (selected[0].unique_element == 'profile_id') {
                    $("#profile_id").show();
                    $("#unique_element").show();
                }
            }else{
                hideAllUniqueElement();
            }
            var currency = "{!! settings()['currency'] !!}";
            if (selected[0].discount_rate > 0) {
                if(selected[0].fixedPrice == 'yes'){
                    $("#discount").html(selected[0].discount);
                    $("#discount").show();
                }
            }else{
                $("#discount").html("");
                $("#discount").hide();
            }
            if (selected[0].fixedPrice == 'yes') {
                $("#amount").attr({
                    "max": "",
                    "min": ""
                });

                $('#amount').val(selected[0].variation_amount);
                $("#amount").attr({
                    "readonly": "true",
                });

            } else {
                $("#amount").prop('readonly', false);
                $("#amount").attr({
                    "max": selected[0].max,
                    "min": selected[0].min,
                });
            }
        });

        function showAllUniqueElement(){
            $("#verify-link").show();
            $(".unique_element_div").show();
            $("#unique_element").attr({
                "required": true,
            });
        }

        function hideAllUniqueElement(){
            $("#profile_id").hide();
            $("#verify-link").hide();
            $(".unique_element_div").hide();
            $("#buy-button").show();
            $("#unique_element").attr({
                "required": false,
            });
        }

        $('.select2').select2();
    // });
</script>
