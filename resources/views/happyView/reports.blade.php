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
                <div class="row mb-3">
                    <div class="col-md-12">

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">{{$_GET['key']}} Report</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            @if($_GET['key'] == "Ticket")
                            <form action="{{url('/tickets-booking-report')}}" method="post">
                            @elseif($_GET['key'] == "Membership")
                            <form action="{{url('/membership-booking-report')}}" method="post">
                            @elseif($_GET['key'] == "MembershipUser")
                            <form action="{{url('/membership-booking-user')}}" method="post">
                            @elseif($_GET['key'] == "Event")
                            <form action="{{url('/event-booking-report')}}" method="post">
                            @else
							<form action="{{url('/open-billing-report')}}" method="post">
							@endif
                                <div class="modal-body">
                                    @if($_GET['key'] == "MembershipUser")
                                        @csrf
                                         @if (auth('admin')->user()->branch_id == null)
                                            <label>Branches:</label>
                                             <select name="branch_id[]" class="selectpicker form-control" style="margin-bottom: 15px;" title="Select Branch" id="branchMembershipUser" multiple>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch['id'] }}">{{ $branch['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif

                                        <label>Membership User:</label>
                                        <div class="d-flex">
                                            <select name="membership_user" class="form-control selectpicker" data-live-search="true">
                                            </select>
                                        </div>
                                    @else
                                        @csrf
                                        @if (auth('admin')->user()->branch_id == null)
                                            <label>Branches:</label>
                                            <select name="branch_id[]" class="selectpicker form-control" style="margin-bottom: 15px;" data-live-search="true" title="Select Branch" multiple>
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch['id'] }}">{{ $branch['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
                                        <label>Analysis date wise:</label>
                                        <div class="d-flex">
                                            <input type="text" name="daterange" class="form-control float-right"
                                                id="reservation">

                                        </div>
                                        @endif
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" name="submit">Apply</button>
                                </div>
                            </div>
                            </form>
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/js/index.js"></script>
    <script src="assets/plugins/moment/moment.min.js"></script>

    <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
    <!--app JS-->
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="assets/js/bootstrap-select.min.js"></script>

    <script>
        $('#reservation').daterangepicker({
            maxDate: new Date()
        });
    </script>
    <script>
    $(document).ready(function() {
    $('.modal').modal('show');
    });
    </script>
    <script>
        $(document).on('change', '#branchMembershipUser', function() {
            var baseUrl = "{{ URL::to('/') }}";
            var selectedBranches = $('#branchMembershipUser').val();
            console.log(selectedBranches, '--selectedBranches---');
            $.ajax({
                url: baseUrl + '/get-membership-users-by-branch',
                type: 'GET',
                data: {
                    branches: selectedBranches
                },
                success: function(response) {
                    if(response && response.data) {
                        let $dropdown = $('select[name="membership_user"]');
                        $dropdown.selectpicker('destroy');
                        $dropdown.empty();
                        $dropdown.append('<option value="">-- Select Membership User --</option>');
                        response.data.forEach(function(user) {
                            $dropdown.append(`<option value="${user.user.id}">${user.user.name}</option>`);
                        });
                        $dropdown.selectpicker();
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
        $(document).ready(function() {
            $('.selectpicker').selectpicker();
            $('select[name="membership_user"]').selectpicker();
        });
    </script>
</body>

</html>
