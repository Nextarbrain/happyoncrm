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
										<p>User List ({{count($user)}}) </p>
									</div>
							
									<div class="col-md-4">
										
										  <div class="add_btn">
											<a href="{{url('/add-staffs')}}">ADD NEW +</a>
										</div>
									</div>
									<div class="col-md-4">
										
										<div class="count" id="user_delete">
												<i class="fa fa-trash-o  m-0" aria-hidden="true"></i>
											</div>
									</div>

									
									
									
								</div>
								
								
							</div>
							<!-- data table  -->
							<div class="container">
								<div class="row table-responsive">
									<div class="data_table">
										<div class="table_header">
											
											
										</div>
										<table id="example1" class="table table-striped" style="width:100%">
											<thead>
                                                
												<tr>
													<th><input type="checkbox" onclick="checkAll(this)"></th>
													<th>Name</th>
													<th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Role</th>
												
													<th>Status</th>
													
													<th>Action</th>
												</tr>
											</thead>
											<tbody>

												@foreach($user as $users)
												@if($users->id > 1)
												<tr>
													<td><input type="checkbox" name="users[]" class="users_checkbox" value="{{$users->id}}" data-item-id="{{$users->id}}"></td>
												
													<td class="text-capitalize">{{$users->name}}</td>
													<td>{{$users->email}}</td>
                                                    <td>{{$users->phone}}</td>
                                                    <td>{{$users->role}}</td>
													
													<td><div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault1" onclick="changeStatus({{$users->id}}, @if($users->status == 1) 0 @else 1 @endif,'status')" @if($users->status == 1) checked @endif>
  <label class="form-check-label" for="flexSwitchCheckDefault1"></label>
</div></td>
													
													<td><a href="{{ url('/edit-staffs/'.$users->id) }}""><i class="fa fa-pencil" aria-hidden="true"></i></a>
														
													</td>
												</tr>
                                                @endif
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

	<script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>

	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
	<script src="{{asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>

	{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
	
    <script>
    var baseUrl = "{{ URL::to('/') }}";
    function changeStatus(id, status,column)
    {
        $.ajax({
            type: 'Get',
            url: baseUrl + '/change-status',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                status: status,
                column: column,
            },
            success: function(response) {

                toastr.success('Status update successfully');
                location.reload();

            },
            error: function(xhr, status, error) {

            }

        });   
    }
    </script>
  

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
    console.log(selectedItems);
    var baseUrl = "{{URL::to('/')}}";
    if(selectedItems.length > 0){
        $.ajax({
            type: 'Delete',
            url: baseUrl + '/delete-staff',
            headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                selectedItems: selectedItems
            },
            success: function(response){
                toastr.success('Staff deleted successfully');
                location.reload();
               
            },
            error: function(xhr,status, error){

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