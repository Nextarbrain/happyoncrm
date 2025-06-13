
<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
		<!--favicon-->
	<link rel="icon" href="assets/images/happyon_logo.png" type="image/png" />
	<!--plugins-->
	<link href="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
	<link href="{{asset('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet"/>
	<link href="{{asset('assets/plugins/daterangepicker/daterangepicker.css')}}" rel="stylesheet" />
	<!-- Bootstrap CSS -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/app.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{asset('assets/css/header-colors.css')}}"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/> --}}
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/css/toastr.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap-select.min.css')}}">
	<title>Happy On</title>


	<link href="{{asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">

	<link href="{{asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <style>
          .btn i{
                font-size: 0.8rem !important;
            }
    </style>
</head>
