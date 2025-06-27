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
                                        <h5 class="mb-0">Events Bookings List ({{ count($event_bookings) }})</h5>
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
                                                    <th>12% GST</th>
                                                    <th>5% GST</th>
                                                    <th>Total</th>
                                                    <th>Payment Method</th>
                                                    <th>Status</th>
                                                    <th>Branch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total = 0;
                                                    $subtotal = 0;
                                                    $gst18 = 0;
                                                    $gst12 = 0;
                                                    $gst5 = 0;
                                                @endphp
                                                @foreach ($event_bookings as $key => $event_booking)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ date('d M, Y', strtotime($event_booking->booking_date)) }}
                                                        </td>
                                                        <td>EVT{{ $event_booking->id }}</td>
                                                        <td style="text-transform: capitalize;">
                                                            {{ $event_booking->bill_name }}</td>
                                                        <td>{{ $event_booking->subtotal }}</td>
                                                        <td>{{ $event_booking->gst18 }}</td>
                                                        <td>{{ $event_booking->gst12 }}</td>
                                                        <td>{{ $event_booking->gst5 }}</td>
                                                        <td>{{ $event_booking->totalamount }}</td>
                                                        <td>{{ $event_booking->payment_method }}</td>
                                                        <td>{{ $event_booking->payment_status }}</td>
                                                        <td>{{ optional($event_booking['branch'])->name ?? '' }}</td>
                                                    </tr>
                                                    @php
                                                        $subtotal += $event_booking->subtotal;
                                                        $gst18 += $event_booking->gst18;
                                                        $gst12 += $event_booking->gst12;
                                                        $gst5 += $event_booking->gst5;
                                                        $total += $event_booking->totalamount;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Total: </td>

                                                    <td>{{ $subtotal }}</td>
                                                    <td>{{ $gst18 }}</td>
                                                    <td>{{ $gst12 }}</td>
                                                    <td>{{ $gst5 }}</td>
                                                    <td>{{ $total }}</td>
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
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>
</html>