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
                            <div class="container-fluid">                                
                                <div class="row mb-2">
                                    <div class="col">
                                        <h5 class="mb-0">List ({{ count($membershipUsers) }})</h5>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">                                    
                                    <div class="col-md-6">
                                        <div id="datatable-buttons"></div>
                                    </div>
                                    <div class="col-md-6 text-md-right d-flex justify-content-md-end justify-content-start align-items-center flex-wrap">                                       
                                         <div class="mr-2 mb-2 mx-3">
                                            Search:
                                        </div>
                                        <div class="mb-2">
                                            <input type="search" id="datatable-search" class="form-control">
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
                                                    <th>S.No</th>
                                                    <th>Date</th>
                                                    <th>Time</th>
                                                    <th>Member Name</th>
                                                    <th>Phone</th>
                                                    <th>Use Credit</th>
                                                    <th>Left Credit</th>
                                                    <th>Total Credit</th>
                                                    <th>Branch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($membershipUsers as $key => $users)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        @if (\Carbon\Carbon::parse($users->created_at, 'Asia/Kolkata')->isToday())
                                                            <td>Today</td>
                                                        @elseif(\Carbon\Carbon::parse($users->created_at, 'Asia/Kolkata')->isYesterday())
                                                            <td>Yesterday</td>
                                                        @else
                                                            <td>{{ \Carbon\Carbon::parse($users->created_at)->format('d M, Y') }}
                                                            </td>
                                                        @endif
                                                        <td>{{ \Carbon\Carbon::parse($users->created_at)->format('H:i') }}
                                                        </td>
                                                        @if (isset($users['user']->name))
                                                            <td class="text-capitalize">{{ $users['user']->name }}</td>
                                                        @endif
                                                        <td>
                                                            @if (isset($users['userDetails']->phone_no))
                                                                {{ $users['userDetails']->phone_no }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (isset($users->use_credit))
                                                                {{ $users->use_credit }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (isset($users->current_left))
                                                                {{ $users->current_left }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if (isset($users['membershipBuy']->total_credit))
                                                                {{ $users['membershipBuy']->total_credit }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{ optional($users['branch'])->name ?? '' }}
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
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script>
        $(function() {
            var table = $('#example1').DataTable({
                dom: '<"row mb-3 align-items-center">rt' +
                    '<"row mt-3 align-items-center"' +
                    '<"col-md-4 d-flex align-items-center"i>' +
                    '<"col-md-4 d-flex justify-content-center"l>' +
                    '<"col-md-4 d-flex justify-content-end"p>>',
                buttons: ['copy', 'csv', 'print', 'colvis'],
                responsive: true,
                info: true
            });
            table.buttons().container().appendTo('#datatable-buttons');

            $('#datatable-search').addClass('form-control-sm');

            // Bind custom search input
            $('#datatable-search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('.dt-buttons button').addClass('btn-sm');
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
    </script>
</body>

</html>
