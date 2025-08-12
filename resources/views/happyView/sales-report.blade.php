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
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-6">
                                        <div id="datatable-buttons"></div>
                                    </div>
                                    <div class="col-md-6 text-md-right d-flex justify-content-md-end justify-content-start align-items-center flex-wrap">
                                        <div class="mr-2 mb-2 mx-3">
                                            Search:
                                        </div>
                                        <div class="mb-2">
                                            <input type="search" id="datatable-search" class="form-control form-control-sm">
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
                                                    <th>Gaurdian</th>
                                                    <th>First Kid</th>
                                                    <th>Date</th>
                                                    <th>Phone</th>
                                                    <th>Basic</th>
                                                    <th>9% CGST</th>
                                                    <th>9% SGST</th>
                                                    <th>Total</th>
                                                    <th>Basic</th>
                                                    <th>2.5% GST</th>
                                                    <th>2.5% GST</th>
                                                    <th>Total</th>
                                                    <th>Grand Total</th>
                                                    <th>Timer</th>
                                                    <th>kids</th>
                                                    <th>Hours</th>
                                                    <th>Payment Method</th>
                                                    <th>Type</th>
                                                    <th>Branch</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $gst9Ba = $gst9 = $gst9Total = $gst5Ba = $gst5 = $gst5Total = $gTotal = 0;
                                                @endphp
                                                @foreach ($sales as $key => $user_booking)
                                                    @php
                                                        $gst_18 = $gst_5 = $hourSockTotal = '0:00';
                                                        if (isset($user_booking->paymentInfo) && isset($user_booking->paymentInfo->gst18)) {
                                                            $gst_18 = number_format($user_booking->paymentInfo->gst18 / 2, 2);
                                                        }
                                                        if (isset($user_booking->paymentInfo) && isset($user_booking->paymentInfo->gst5)) {
                                                            $gst_5 = number_format($user_booking->paymentInfo->gst5 / 2, 2);
                                                        }
                                                        $hourSockTotal = $user_booking->paymentInfo?->amounts + $user_booking->paymentInfo?->gst5;
                                                        $bookingDate = isset($user_booking['event']->booking_date) ? date('d M Y', strtotime($user_booking['event']->booking_date)) : '';
                                                        $start_datetime = isset($user_booking['event']->Hours) ? $user_booking['event']->Hours : null;
                                                        $gst5TotalAmt = (float) ($user_booking['basicAmtArr'][5] ?? 0) + (float) ($user_booking->paymentInfo?->gst5 ?? 0);
                                                        $amount18 = (float) ($user_booking->paymentInfo?->amounts ?? 0) - $gst5TotalAmt;
                                                        $subTotal18 = round($amount18 / 1.18, 2);
                                                        $gst9Ba += $subTotal18;
                                                        $gst9 += $gst_18;
                                                        $gst9Total += $amount18;
                                                        $gst5Ba += (float) $user_booking['basicAmtArr'][5] ?? 0;
                                                        $gst5 += (float) $gst_5;
                                                        $gst5Total += $gst5TotalAmt;
                                                        $gTotal += (float) $user_booking->paymentInfo?->amounts ?? 0;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ ++$key }}</td>
                                                        <td>{{ $user_booking->name }}</td>
                                                        <td>{{ $user_booking->kids[0]->name ?? null }}</td>
                                                        <td>{{ isset($user_booking['event']->booking_date) ? date('d M, Y', strtotime($user_booking['event']->booking_date)) : '' }}</td>
                                                        <td>{{ $user_booking->userDetails->phone_no ?? null }}</td>
                                                        <td>{{ $subTotal18 }}</td>
                                                        <td>{{ $gst_18 }}</td>
                                                        <td>{{ $gst_18 }}</td>
                                                        <td>{{ $amount18 }}</td>
                                                        <td>{{ $user_booking['basicAmtArr'][5] ?? '' }}</td>
                                                        <td>{{ $gst_5 }}</td>
                                                        <td>{{ $gst_5 }}</td>
                                                        <td>{{ $gst5TotalAmt }}</td>
                                                        <td>{{ $user_booking->paymentInfo?->amounts ?? '' }}</td>
                                                        <td>{{ remaining_time($bookingDate, $start_datetime, $user_booking->kids[0]->hours) }}</td>
                                                        <td>{{ count($user_booking->kids) }}</td>
                                                        <td>{{ $user_booking->kids[0]->hours ?? null }}</td>
                                                        <td>{{ $user_booking->paymentInfo?->payment_method ?? '' }}</td>
                                                        <td>{{ $user_booking->userDetails?->membership_plan == 1 ? 'Membership' : 'Ticket' }}</td>
                                                        <td>{{ $user_booking->branch?->name ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>Total Amt.</td>
                                                    <td></td>
                                                    <td>{{ $gst9Ba }}</td>
                                                    <td>{{ $gst9 }}</td>
                                                    <td>{{ $gst9 }}</td>
                                                    <td>{{ $gst9Total }}</td>
                                                    <td>{{ $gst5Ba }}</td>
                                                    <td>{{$gst5}}</td>
                                                    <td>{{$gst5}}</td>
                                                    <td>{{$gst5Total}}</td>
                                                    <td>{{$gTotal}}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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

            // Bind custom search input
            $('#datatable-search').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('.dt-buttons button').addClass('btn-sm');
        });
    </script>
</body>

</html>
