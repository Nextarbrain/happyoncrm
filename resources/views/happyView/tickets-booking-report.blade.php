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
                                        <h5 class="mb-0">User List ({{ count($user_bookings) }})</h5>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">                                    
                                    <div class="col-md-6">
                                        <div id="datatable-buttons"></div>
                                    </div>
                                    <div class="col-md-6 text-md-right d-flex justify-content-md-end justify-content-start align-items-center flex-wrap">                                       
                                        <div class="mr-2 mb-2 mx-3">
                                            <input type="search" id="datatable-search" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="mb-2">
                                            <a href="{{ url('/add-event') }}" class="btn btn-sm btn-primary">Add New +</a>
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
                                                    <th>Invoice No</th>
                                                    <th>Name</th>
                                                    <th>Sub-Total</th>

                                                    <th>9% CGST</th>
                                                    <th>9% SGST</th>
                                                    <th>2.5% GST</th>
                                                    <th>2.5% GST</th>

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
                                                    $gst5 = 0;
                                                    $gst12 = 0;
                                                @endphp
                                                @foreach ($user_bookings as $key => $user_booking)
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ isset($user_booking['event']->booking_date) ? date('d M, Y', strtotime($user_booking['event']->booking_date)) : '' }}
                                                        </td>
                                                        <td>TKT{{ $user_booking->id }}</td>
                                                        <td style="text-transform: capitalize;">
                                                            {{ $user_booking->name }}</td>
                                                        <td>{{ $user_booking['paymentInfo'] != null && $user_booking['paymentInfo']->subTotal ? $user_booking['paymentInfo']->subTotal : 0 }}
                                                        </td>

                                                        <td>{{ isset($user_booking['paymentInfo']) && $user_booking['paymentInfo']->gst18 ? number_format($user_booking['paymentInfo']->gst18 / 2, 2) : '0.00' }}
                                                        </td>
                                                        <td>{{ isset($user_booking['paymentInfo']) && $user_booking['paymentInfo']->gst18 ? number_format($user_booking['paymentInfo']->gst18 / 2, 2) : '0.00' }}
                                                        </td>
                                                        <td>{{ isset($user_booking['paymentInfo']) && $user_booking['paymentInfo']->gst18 ? number_format($user_booking['paymentInfo']->gst5 / 2, 2) : '0.00' }}
                                                        </td>
                                                        <td>{{ isset($user_booking['paymentInfo']) && $user_booking['paymentInfo']->gst18 ? number_format($user_booking['paymentInfo']->gst5 / 2, 2) : '0.00' }}
                                                        </td>

                                                        <td>{{ isset($user_booking['paymentInfo']) && isset($user_bookin['paymentInfo']->amounts) ? number_format($user_booking['paymentInfo']->amounts, 2) : '0.00' }}
                                                        </td>
                                                        <td>{{ $user_booking['paymentInfo'] != null && $user_booking['paymentInfo']->payment_method ? $user_booking['paymentInfo']->payment_method : 0 }}
                                                        </td>
                                                        <td>{{ isset($user_booking['branch']) && $user_booking['branch'] ? $user_booking['branch']->name : '' }}
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $subtotal +=
                                                            isset($user_booking['paymentInfo']) &&
                                                            $user_booking['paymentInfo']->subTotal
                                                                ? $user_booking['paymentInfo']->subTotal
                                                                : 0;
                                                        $gst18 +=
                                                            isset($user_booking['paymentInfo']) &&
                                                            $user_booking['paymentInfo']->gst18
                                                                ? $user_booking['paymentInfo']->gst18
                                                                : 0;
                                                        $gst12 +=
                                                            isset($user_booking['paymentInfo']) &&
                                                            $user_booking['paymentInfo']->gst12
                                                                ? $user_booking['paymentInfo']->gst12
                                                                : 0;
                                                        $gst5 +=
                                                            isset($user_booking['paymentInfo']) &&
                                                            $user_booking['paymentInfo']->gst5
                                                                ? $user_booking['paymentInfo']->gst5
                                                                : 0;
                                                        $total +=
                                                            isset($user_booking['paymentInfo']) &&
                                                            $user_booking['paymentInfo']->amounts
                                                                ? $user_booking['paymentInfo']->amounts
                                                                : 0;

                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Total: </td>

                                                    <td>{{ number_format($subtotal, 2) }}</td>

                                                    <td>{{ number_format($gst18 / 2, 2) }}</td>
                                                    <td>{{ number_format($gst18 / 2, 2) }}</td>
                                                    <td>{{ number_format($gst5 / 2, 2) }}</td>
                                                    <td>{{ number_format($gst5 / 2, 2) }}</td>
                                                    <td>{{ number_format($total, 2) }}</td>
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
</body>

</html>
