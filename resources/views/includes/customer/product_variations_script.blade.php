<script type="text/javascript">

    $("#network_module").change(function() {
        resetAmount();
        var has_variation = $('#network_module').find(':selected').data('has_variations');
        var product_id = $('#network_module').val();
        var variation_id = $('#variation').val();
        var allow_quantity = $('#product').find(':selected').data('allow_quantity');
        var max = $('#network_module').find(':selected').data('max');
        var min = $('#network_module').find(':selected').data('min');
        var quantity_graduation = $('#network_module').find(':selected').data('quantity_graduation');
        var fixed_price = $('#network_module').find(':selected').data('fixed_price');

        if ($("#network_module").val() != "" && has_variation == 'yes') {
            var network_module = $("#network_module").val();
            $.LoadingOverlay("show");
            $.ajax({
                url: '/variations?services=' + network_module +
                    '&level={{ auth()->user()->level }}&&product=airtime_sns',
                method: 'GET',
                success: function(RES_data) {
                    $.LoadingOverlay("hide");
                    $("#network_discount").val(RES_data);
                }
            })
        }
    })

    function calDiscount() {
        var has_variation = $('#network_module').find(':selected').data('has_variations');
        var product_id = $('#network_module').val();
        var variation_id = $('#variation').val();
        var allow_quantity = $('#product').find(':selected').data('allow_quantity');
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
</script>