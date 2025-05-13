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
									<div class="col-md-4">
										<p>List ({{count($membershipUser)}})</p>
									</div>



                                    <div class="col-md-4">
										@if(can('membership-delete'))
										<div class="count" id="user_delete">
												<i class="fa fa-trash-o  m-0" aria-hidden="true"></i>
											</div>
                                            @endif
									</div>

                                    <div class="col-md-4">

										  <div>
											<a href="{{url('/memberships')}}" class="btn btn-secondary">Back</a>
										</div>
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
                                                <th><input type="checkbox" onclick="checkAll(this)"></th>
													<th>Date</th>
													<th>Time</th>
													<th>Member Name</th>
													<th>Phone</th>
													<th>Use Credit</th>
													<th>Left Credit</th>
													<th>Total Credit</th>

												</tr>
											</thead>
											<tbody>
												@foreach($membershipUser as $users)
												<tr>
                                                <td><input type="checkbox" name="users[]" class="users_checkbox" value="{{$users->id}}" data-item-id="{{$users->id}}"></td>

													@if (\Carbon\Carbon::parse($users->created_at, 'Asia/Kolkata')->isToday())
														<td>Today</td>
													@elseif(\Carbon\Carbon::parse($users->created_at,'Asia/Kolkata')->isYesterday())
														<td>Yesterday</td>
													@else
													<td>{{\Carbon\Carbon::parse($users->created_at)->format('d M, Y')}}</td>
													@endif

													<td>{{\Carbon\Carbon::parse($users->created_at)->format('H:i')}}</td>

													@if(isset($users['user']->name))
													<td class="text-capitalize">{{$users['user']->name}}</td>

													@endif

													<td>
													@if(isset($users['userDetails']->phone_no))
														{{$users['userDetails']->phone_no}}
													@endif
													</td>

													<td>
													@if(isset($users->use_credit))
														{{$users->use_credit}}
													@endif
													</td>

													<td>

														@if(isset($users->current_left))
															{{$users->current_left}}
														@endif

													</td>

													<td>
													@if(isset($users['membershipBuy']->total_credit))
														{{$users['membershipBuy']->total_credit}}
													@endif
													</td>
												</tr>
												@endforeach
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
	<script src="{{asset('assets/plugins/chartjs/js/Chart.min.js')}}"></script>
	<script src="{{asset('assets/plugins/chartjs/js/Chart.extension.js')}}"></script>
	<script src="{{asset('assets/js/index.js')}}"></script>
	<!--app JS-->

	<script src="{{asset('assets/js/circle-progress.min.js')}}"></script>
	<script src="{{asset('assets/js/app.js')}}"></script>
	{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

	<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>


	<script>
		$(function () {
		  $("#example1").DataTable({
			"responsive": true, "lengthChange": true, "autoWidth": true,
			"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
		  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

		});
	  </script>

	<script>


    function checkAll(bx) {
    var cbs = document.getElementsByTagName('input');
    for(var i=0; i < cbs.length; i++) {
        if(cbs[i].type == 'checkbox') {
        cbs[i].checked = bx.checked;
        }
    }
    }


	document.getElementById('user_delete').addEventListener("click", function() {
    var selectedItems = [];
    $('.users_checkbox:checked').each(function(){
       var userId =  $(this).data('item-id');
       selectedItems.push(userId);
    });

    var baseUrl = "{{URL::to('/')}}";
    if(selectedItems.length > 0){
        $.ajax({
            type: 'Delete',
            url: baseUrl + '/delete-membership-history',
            headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                selectedItems: selectedItems
            },
            success: function(response){
                toastr.success('Membership History deleted successfully');
                location.reload();
                $('#message_container').text('Membership History deleted successfully');
            },
            error: function(xhr,status, error){
                var statusCode = xhr.status;
                        var errorMessage = xhr.responseJSON.success;
                        if (statusCode == 500) {
                            console.log(errorMessage);
                        } else {
                            toastr.error('No Permission to Delete');
                            $('#message_container').text('No Permission to Delete');
                        }
            }

        });
    }

});


$(document).ready(function() {
    toastr.options.timeOut = 10000;
    @if (Session::has('error'))
        toastr.error('{{ Session::get('error') }}');
    @elseif(Session::has('success'))
        toastr.success('{{ Session::get('success') }}');
    @endif
});

	</script>
</body>

</html>
