@include('happyView.includes.head')

<body>
	<!--wrapper-->
	<div class="wrapper">
	      @include('happyView.includes.sidebar')
			<!--start header -->
			<header>
				<div class="topbar d-flex align-items-center">
					<nav class="navbar navbar-expand">
						<div class="mobile-toggle-menu">
							<i class="fa fa-bars" aria-hidden="true"></i>
						</div>

						<div class="top-menu ms-auto">
							<ul class="navbar-nav align-items-center">
								<li class="nav-item dropdown dropdown-large">
									<div class="dropdown-menu dropdown-menu-end">
										<div class="header-notifications-list">
										</div>
									</div>
								</li>
								<li class="nav-item dropdown dropdown-large">
									<div class="dropdown-menu dropdown-menu-end">
										<div class="header-message-list">
										</div>
									</div>
								</li>
							</ul>
						</div>

					</nav>
				</div>
			</header>


		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">

			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="dashboard_left">
							<div class="user_list">
								<div class="row align-items-center text-center">
									<div class="col-md-3">
										<p>Members List ({{count($membershipUsers)}}) </p>
									</div>

								</div>


							</div>
							<!-- data table  -->
							<div class="container">
								<div class="row table-responsive">
									<div class="data_table">

										<table id="example1" class="table table-striped" style="width:100%">
											<thead>
												<tr>
													<th>S.No</th>
													<th>Date</th>
													<th>Invoice No</th>
													<th>Name</th>
													<th>Sub-Total</th>

													<th>18% GST</th>

													<th>Total</th>
													<th>Payment Method</th>
                                                    <th>Branch</th>
												</tr>
											</thead>
											<tbody>

												@php
													$total = 0;
													$subtotal = 0;
													$gst18 = 0;

												@endphp
												@foreach($membershipUsers as $key=> $membershipUser)

												<tr>
													<td>{{++$key}}</td>
													<td>{{date("d M, Y",strtotime($membershipUser->date))}}</td>
                                                    <td>MS{{$membershipUser->id}}</td>
                                                    <td style="text-transform: capitalize;">{{$membershipUser['user']->name}}</td>
                                                    <td>{{isset($membershipUser['paymentInfo']) && $membershipUser['paymentInfo']->subTotal ? $membershipUser['paymentInfo']->subTotal : 0}}</td>

                                                    <td>{{isset($membershipUser['paymentInfo']) && $membershipUser['paymentInfo']->gst18 ? $membershipUser['paymentInfo']->gst18 : 0}}</td>

                                                    <td>{{$membershipUser->amount}}</td>
                                                    <td>{{$membershipUser->payment_method}}</td>
                                                    <td>{{optional($membershipUser['branch'])->name ?? ''}}</td>
                                                </tr>
                                                    @php
                                                    $subtotal += optional($membershipUser['paymentInfo'])->subTotal ?? 0;
                                                    $gst18 += optional($membershipUser['paymentInfo'])->gst18 ?? 0;
                                                    $total += $membershipUser->amount;

                                                    @endphp


												@endforeach
												<tr>
														<td></td>
														<td></td>
														<td></td>
														<td>Total: </td>

														<td>{{$subtotal}}</td>
														<td>{{$gst18}}</td>

														<td>{{$total}}</td>
														<td></td>
														<td></td>

												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button-->
		  <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->

	</div>
	<!--end wrapper-->

	<!-- Bootstrap JS -->
	<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>


	<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>

	<script src="{{asset('assets/js/index.js')}}"></script>
	<!--app JS-->

	<script src="{{asset('assets/js/app.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>


	<script>
		$(function () {
		  $("#example1").DataTable({
			"responsive": true, "lengthChange": true, "autoWidth": true,
			"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
		  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

		});
	  </script>


</body>

</html>
