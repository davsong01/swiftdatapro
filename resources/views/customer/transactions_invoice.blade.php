<!DOCTYPE html>
<html lang="zxx">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>{{ config('app.name')}}</title>
    <link rel="icon" type="image/png" href="../access/images/favicon.png">

    <link rel="stylesheet" href="backend/css/bootstrap1.min.css" />

    <link rel="stylesheet" href="backend/vendors/themefy_icon/themify-icons.css" />

    <link rel="stylesheet" href="backend/vendors/niceselect/css/nice-select.css" />

    <link rel="stylesheet" href="backend/vendors/owl_carousel/css/owl.carousel.css" />

    <link rel="stylesheet" href="backend/vendors/gijgo/gijgo.min.css" />

    <link rel="stylesheet" href="backend/vendors/font_awesome/css/all.min.css" />
    <link rel="stylesheet" href="backend/vendors/tagsinput/tagsinput.css" />

    <link rel="stylesheet" href="backend/vendors/datepicker/date-picker.css" />
    <link rel="stylesheet" href="backend/vendors/vectormap-home/vectormap-2.0.2.css" />

    <link rel="stylesheet" href="backend/vendors/scroll/scrollable.css" />

    <link rel="stylesheet" href="backend/vendors/datatable/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="backend/vendors/datatable/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="backend/vendors/datatable/css/buttons.dataTables.min.css" />

    <link rel="stylesheet" href="backend/vendors/text_editor/summernote-bs4.css" />

    <link rel="stylesheet" href="backend/vendors/morris/morris.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="backend/vendors/material_icon/material-icons.css" />

    <link rel="stylesheet" href="backend/css/metisMenu.css">

    <link rel="stylesheet" href="backend/css/style1.css" />
    <link rel="stylesheet" href="backend/css/colors/default.css" id="colorSkinCSS">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-loading-overlay@1.1.0/dist/js-loading-overlay.min.js"></script>
    <script type="text/javascript" src="backend/js/overlay.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <style type="text/css">
        .centered {
            position: realtive;
            text-align: center;
            top: 2px;
            left: 5px;
            margin: 0 auto;
            transform: translateY(-50%);
        }

        .parent {
            background: #CCCCCC;
            height: 650px;
            width: 100%;
            border-radius: 10px;
            position: relative;
        }

        .child {
            background: #FFFF00;
            width: 130px;
            height: 74px;
            top: 1%;
            right: 7%;
        }

        .child2 {
            background: #FFFF00;
            width: 130px;
            height: 74px;
            top: 1%;
            left: 4%;
        }
    </style>
</head>
<body class="crm_body_bg" id="genReceipt">
	<nav class="sidebar">
		<div class="logo d-flex justify-content-between">
			<h3 class="large_logo"><?php echo strtoupper(config('app.name')); ?></h3>

		</div>

	</nav>

    <section class="main_content dashboard_part large_header_bg">
        <div class="main_content_iner overly_inner ">
            <div class="container-fluid p-0 ">

                <div class="row">
                    <div class="col-12">
                        <div class="page_title_box d-flex align-items-center justify-content-between">
                            <div class="page_title_left">
                                <h3 class="f_s_30 f_w_700 dark_text">Invoice - {{ config('app.name') }}</h3>
                                <ol class="breadcrumb page_bradcam mb-0">
                                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">invoice</li>
                                </ol>
                            </div>

                        </div>
                    </div>
                </div>
                {!! $receipt !!}
                {!! $buttons !!}
            </div>
        </div>
    </section>

</body>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">
    document.getElementById('PdfDial').addEventListener('click',
        Export);

    function Export() {
        html2canvas(document.getElementById('genReceipt'), {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("TransactionReceipt.pdf");
            }
        });
    }
</script>
