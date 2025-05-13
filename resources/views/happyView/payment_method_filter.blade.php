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
			
			@php
				$user_record = [];
				$member_record = [];
				$event_record = [];
				$open_record = [];
				$event_partial_record = [];
				$advance_payment_record = [];
				$installment_amount_record = [];
				$final_installment_record = [];

				if(isset($data['upi_users']) || isset($data['upi_members'])|| isset($data['upi_event']) || isset($data['upi_event_partial']) || isset($data['upi_open'])){
					$user_record = $data['upi_users'];
					$member_record = $data['upi_members'];
					$event_record = $data['upi_event'];
					$open_record = $data['upi_open'];
					$event_partial_record = $data['upi_event_partial'];
					
					if(isset($event_partial_record['advance_payment_upi']) || isset($event_partial_record['installment_amount_upi']) || isset($event_partial_record['final_installment_upi']))
					{
						$advance_payment_record= $event_partial_record['advance_payment_upi'];
						$installment_amount_record= $event_partial_record['installment_amount_upi'];
						$final_installment_record= $event_partial_record['final_installment_upi'];
					}
					//dd($event_partial_record);
					
				}elseif(isset($data['cash_users']) || isset($data['cash_members'])||isset($data['cash_event']) || isset($data['cash_event_partial'])){
					$user_record = $data['cash_users'];
					$member_record = $data['cash_members'];
					$event_record = $data['cash_event'];
					$open_record = $data['cash_open'];
					$event_partial_record = $data['cash_event_partial'];

					if(isset($event_partial_record['advance_payment_cash']) || isset($event_partial_record['installment_amount_cash']) || isset($event_partial_record['final_installment_cash']))
					{
						$advance_payment_record= $event_partial_record['advance_payment_cash'];
						$installment_amount_record= $event_partial_record['installment_amount_cash'];
						$final_installment_record= $event_partial_record['final_installment_cash'];
					}
					
				}else{
					$user_record = $data['card_users'];
					$member_record = $data['card_members'];
					$event_record = $data['card_event'];
					$open_record = $data['card_open'];
					$event_partial_record = $data['card_event_partial'];

					if(isset($event_partial_record['advance_payment_card']) || isset($event_partial_record['installment_amount_card']) || isset($event_partial_record['final_installment_card']))
					{
						$advance_payment_record= $event_partial_record['advance_payment_card'];
						$installment_amount_record= $event_partial_record['installment_amount_card'];
						$final_installment_record= $event_partial_record['final_installment_card'];
					}
				}

			@endphp
			<div class="page-content">
				<div class="row">
					<div class="col-md-12">
						<div class="dashboard_left">
							<div class="user_list">
								<div class="row align-items-center text-center">
									<div class="col-md-12">
										<p>User List ({{count($user_record)+count($member_record)+count($event_record)+count($advance_payment_record)+count($installment_amount_record)+count($final_installment_record)}}) </p>
									</div>
								
											
								</div>
									
							</div>
							<!-- data table  -->
							<div class="container">
								{{-- @dd($data) --}}
								<div class="row table-responsive">
									<div class="data_table">
										
										<table id="example1" class="table table-striped" style="width:100%">
											<thead>
												<tr>	
													
													<th>Name</th>
													<th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Method</th>
													<th>Section</th>
												</tr>
											</thead>
											<tbody>
												@if(isset($user_record))
												@foreach($user_record as $key=> $users)
												<tr>
													@php
														$phone = \App\Models\UserDetail::where('user_id',$users->user_id)->select('phone_no')->first();
														$name = \App\Models\User::where('id',$users->user_id)->select('name')->first();
													@endphp
													<td class="text-capitalize">{{isset($name->name)?$name->name:''}}</td>
													<td>{{isset($phone->phone_no)?$phone->phone_no:''}}</td>
													<td>{{$users->amounts}}</td>
                                                    <td>{{\Carbon\Carbon::parse($users->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($users->created_at)->format('H:i A')}}</td>
                                                    <td>{{$users->payment_method}}</td>
													<td>Tickets</td>
												</tr>
												@endforeach
												@endif

												@if(isset($member_record))
												@foreach($member_record as $key=> $member)
												<tr>
													@php
														$phone = \App\Models\UserDetail::where('user_id',$member->user_id)->select('phone_no')->first();
														$name = \App\Models\User::where('id',$member->user_id)->select('name')->first();
													@endphp
													<td class="text-capitalize">{{isset($name->name)?$name->name:''}}</td>
													<td>{{isset($phone->phone_no)?$phone->phone_no:''}}</td>
													<td>{{$member->amount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($member->date)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($member->time)->format('H:i A')}}</td>
                                                    <td>{{$member->payment_method}}</td>
													<td>Membership</td>
												</tr>
												@endforeach
												@endif

												@if(isset($event_record))
												@foreach($event_record as $key=> $event)
												<tr>
													
													<td class="text-capitalize">{{isset($event->bill_name)?$event->bill_name:''}}</td>
													<td>{{isset($event->phone)?$event->phone:''}}</td>
													<td>{{$event->totalamount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($event->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($event->created_at)->format('H:i A')}}</td>
                                                    <td>{{$event->payment_method}}</td>
													<td>Event Booking</td>
												</tr>
												@endforeach
												@endif

												@if(isset($advance_payment_record))
												@foreach($advance_payment_record as $key=> $advance_event)
												<tr>
													
													<td class="text-capitalize">{{isset($advance_event->bill_name)?$advance_event->bill_name:''}}</td>
													<td>{{isset($advance_event->phone)?$advance_event->phone:''}}</td>
													<td>{{$advance_event->advance_amount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($advance_event->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($advance_event->created_at)->format('H:i A')}}</td>
                                                    <td>{{$advance_event->advance_payment_method}}</td>
													<td>Event Booking</td>
												</tr>
												@endforeach
												@endif

												@if(isset($installment_amount_record))
												
												@foreach($installment_amount_record as $key=> $installment_event)
												<tr>
													
													<td class="text-capitalize">{{isset($installment_event->bill_name)?$installment_event->bill_name:''}}</td>
													<td>{{isset($installment_event->phone)?$installment_event->phone:''}}</td>
													<td>{{$installment_event->installment_amount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($installment_event->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($installment_event->created_at)->format('H:i A')}}</td>
                                                    <td>{{$installment_event->installment_amount_method}}</td>
													<td>Event Booking</td>
												</tr>
												@endforeach
												@endif

												@if(isset($final_installment_record))
												@foreach($final_installment_record as $key=> $final_event)
												<tr>
													
													<td class="text-capitalize">{{isset($final_event->bill_name)?$final_event->bill_name:''}}</td>
													<td>{{isset($final_event->phone)?$final_event->phone:''}}</td>
													<td>{{$final_event->final_installment}}</td>
                                                    <td>{{\Carbon\Carbon::parse($final_event->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($final_event->created_at)->format('H:i A')}}</td>
                                                    <td>{{$final_event->final_installment_method}}</td>
													<td>Event Booking</td>
												</tr>
												@endforeach
												@endif

												@if(isset($open_record))
												@foreach($open_record as $key=> $open)
												<tr>
													
													<td class="text-capitalize">{{isset($open->bill_name)?$open->bill_name:''}}</td>
													<td>{{isset($open->phone)?$open->phone:''}}</td>
													<td>{{$open->totalamount}}</td>
                                                    <td>{{\Carbon\Carbon::parse($open->created_at)->format('d M, Y')}}</td>
                                                    <td>{{\Carbon\Carbon::parse($open->created_at)->format('H:i A')}}</td>
                                                    <td>{{$open->payment_method}}</td>
													<td>Open Billing</td>
												</tr>
												@endforeach
												@endif
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