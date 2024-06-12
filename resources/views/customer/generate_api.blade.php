@extends('includes.customer_dashboard.app')
@section('header')
<div class="alert" style="background-color: #fff; border-radius: 0px 0px 0px 0px;">
    <div class="row">
			<div class="col-12">
				<div class="page_title_box d-flex flex-wrap align-items-center justify-content-between">
					<div class="page_title_left">
						<h3 class="f_s_25 f_w_700 dark_text">Integrate API</h3>
						<ol class="breadcrumb page_bradcam mb-0">
							<li class="breadcrumb-item"><a href="javascript:void(0);">Generate</a></li>
							<li class="breadcrumb-item active">APIKEY</li>
						</ol>
					</div>

				</div>
			</div>
		</div>
@endsection
@section('body')
		<div class="row ">
			<div class="col-xl-12">
				<div class="white_card card_height_100 mb_30 social_media_card">
					<div class="white_card_header">
						<div class="main-title">
							<h3 class="m-0">Hey <?php echo auth()->user()->username; ?></h3>
							<span class="text-danger"></span>
						</div>
					</div>

					<div class="media_thumb ml_25">
						<center></center>
					</div>


					<div class="alert" style="background-color: #fff; border-radius: 20px 20px 0px 0px;">
						<!-- start -->
						<div>
							<p>
								API-TOKEN<br />
								<span style="font-weight:bold"><?php echo auth()->user()->sk_token; ?></span>
							</p>

							<hr>

							<p style="color: red;">Copy the above API-key and use that in your request body when
								integrating our website API.</p>

							<br>

							<h5>USE THE BELOW EXAMPLE TO INTEGRATE DATA</h5>

							<hr>

							<p style="color: green;font-weight: bold;">See Sample Request Below</p>
							<code style="font-weight: bold;">

								$payload=array(<br />
								'token' => 'YOUR API TOKEN',<br />
								'network' => 'MTN',<br />
								'plan_code' => 500,<br />
								'mobile' => '0813905******',<br />
								'request_id' => 'REF-UNIQUE72300674352',<br />
								);<br />

								$url = 'https://<?php echo url('/'); ?>/api/data';<br />
								$ch = curl_init();<br />
								curl_setopt($ch, CURLOPT_URL, $url);<br />
								curl_setopt($ch, CURLOPT_POST, 1);<br />
								curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));<br />
								curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);<br />
								$request = curl_exec($ch);<br />
								curl_close($ch);<br />

								echo $request <br />

							</code>

							<hr>

							<p style="color: green;font-weight: bold;">See Sample Response Below</p>

							<code style="font-weight: bold;">

								{status:success, status_code:200, old_balance:1000, new_balance:850, time:11-Jul-2022
								10:47AM, amountPaid:150, message:MTN 500MB - N150 Data Purchase Successful On
								0814905******}

							</code>

							<br>
							<br>
							<br>


						</div>

					</div>
				</div>
			</div>


		</div>
  </div>
@endsection
@section('scripts')
    <script type="text/javascript">
  $("#form-data").submit(function(event) {
    var formData = {
      old_password: $("#old_password").val(),
    };

    // $.LoadingOverlay("show");
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
    $.ajax({
      url: '{{ route('process.password.change')}}',
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
</script>
@endsection
