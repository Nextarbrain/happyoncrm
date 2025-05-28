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
                                        <p>User List ({{ count($user) }}) </p>
                                    </div>
                                    {{--	<div class="col-md-3">
										<label>Download Report</label>
										<form action="{{url('/tickets-booking-report')}}" method="post">
											@csrf

											<div class="d-flex">
											<input type="text" name="daterange" class="form-control float-right" id="reservation1">
											<input type="submit" value="Apply" name="submit" class="btn btn-primary m-1">
											</div>
										</form>
									</div> --}}
                                    <div class="col-md-3">
                                        @if (can('tickets-add'))
                                            <div class="add_btn">
                                                <a href="{{ url('/tickets') }}">ADD NEW +</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        @if (can('tickets-delete'))
                                            <div class="count" id="user_delete">
                                                <i class="fa fa-trash-o  m-0" aria-hidden="true"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-3">

                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Payment Method
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                <li><a class="dropdown-item"
                                                        href="{{ url('customer-filter?key=Cash') }}">Cash</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ url('customer-filter?key=UPI') }}">UPI</a></li>
                                                <li><a class="dropdown-item"
                                                        href="{{ url('customer-filter?key=Debit/Credit') }}">Debit/Credit</a>
                                                </li>
                                            </ul>
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
                                        <table id="example1" class="table table-striped overflow-auto text-center"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" onclick="checkAll(this)"></th>
                                                    <th>Guardian Name</th>
                                                    <th>First Kid</th>
                                                    <th>Date</th>
                                                    <th>Phone</th>
                                                    <th>9% CGST</th>
                                                    <th>9% SGST</th>
                                                    <th>2.5% GST</th>
                                                    <th>2.5% GST</th>
                                                    <th>Amount</th>
                                                    <th>Timer</th>
                                                    <th>Kids</th>
                                                    <th>Hours</th>
                                                    <th>Pay Method</th>
                                                    <th>Type</th>

                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $totalAmount = 0;
                                                @endphp
                                                @foreach ($user as $users)

                                                    @if ($users->role == 1 || ($users->role == 2 && isset($users->membership)))
                                                        <tr>
                                                            <td><input type="checkbox" name="users[]"
                                                                    class="users_checkbox" value="{{ $users->id }}"
                                                                    data-item-id="{{ $users->id }}"></td>
                                                            @if (isset($users['paymentInfo']) && $users['paymentInfo']->payment_method == 'Card')
                                                                <td><img src="/assets/images/Vector.png" alt="logo">
                                                                    &nbsp;{{ $users->name }} </td>
                                                            @else
                                                                <td class="text-capitalize">{{ $users->name }}</td>
                                                            @endif
                                                            @if (isset($users['kid']->name) && $users['kid']->name != '')
                                                                <td class="text-capitalize">{{ $users['kid']->name }}
                                                                </td>
                                                            @else
                                                                <td>--</td>
                                                            @endif
                                                            @if (isset($users['event']->booking_date) && $users->role != 2)
                                                                @if (\Carbon\Carbon::parse($users['event']->booking_date, 'Asia/Kolkata')->isToday())
                                                                    <td>Today</td>
                                                                @elseif(\Carbon\Carbon::parse($users['event']->booking_date, 'Asia/Kolkata')->isYesterday())
                                                                    <td>Yesterday</td>
                                                                @else
                                                                    <td>{{ \Carbon\Carbon::parse($users['event']->booking_date)->format('d M, Y') }}
                                                                    </td>
                                                                @endif
                                                            @else
                                                                <td>{{ isset($users['membership']->date) ? \Carbon\Carbon::parse($users['membership']->date)->format('d M, Y') : '' }}
                                                                </td>
                                                            @endif
                                                            <td>{{ isset($users['userDetails']->phone_no) ? $users['userDetails']->phone_no : '' }}
                                                            </td>
                                                            <td>{{ $users['paymentInfo'] && $users['paymentInfo']->gst18 ?? number_format($users['paymentInfo']->gst18 / 2, 2) }}
                                                            </td>
                                                            <td>{{ $users['paymentInfo'] && $users['paymentInfo']->gst18 ?? number_format($users['paymentInfo']->gst18 / 2, 2) }}
                                                            </td>
                                                            <td>{{ $users['paymentInfo'] && $users['paymentInfo']->gst5 ??  number_format($users['paymentInfo']->gst5 / 2, 2) }}
                                                            </td>
                                                            <td>{{ $users['paymentInfo'] && $users['paymentInfo']->gst5 ??  number_format($users['paymentInfo']->gst5 / 2, 2) }}
                                                            </td>

                                                            @if (isset($users['paymentInfo']) && $users['paymentInfo']->payment_method == 'Card')
                                                                <td> {{ $users['membership']->amount ?? $users['paymentInfo']->amounts }}
                                                                </td>
                                                            @else
                                                                <td>{{ isset($users['paymentInfo']->amounts) ? $users['paymentInfo']->amounts : '' }}
                                                                </td>
                                                            @endif
                                                            @php

                                                                date_default_timezone_set('Asia/Kolkata');
                                                                $start_datetime = isset($users['event']->Hours) ? $users['event']->Hours : null;
                                                                $end_datetime = isset($users['kid']->end_time) ? $users['kid']->end_time : null;
                                                                $booking_date = isset($users['event']->booking_date) ? $users['event']->booking_date : null;
                                                                $start_date_time = $booking_date . ' ' . $start_datetime . ':00';

                                                                $start_time_unix = strtotime($start_date_time);

                                                                if (isset($users['kid']->hours) && $users['kid']->hours == 'Unlimited') {
                                                                    $hourQty = 1;
                                                                } else {
                                                                    $hourQty = isset($users['kid']->hours) ? $users['kid']->hours : '';
                                                                }

                                                                if ($hourQty == '1.5') {
                                                                    $totalSecond = $hourQty * 3600;
                                                                } else {
                                                                    $totalSecond = (int) $hourQty * 3600;
                                                                }

                                                                $end_time_unix_plus_one_hour = $start_time_unix + $totalSecond;

                                                                $remaining_seconds = $end_time_unix_plus_one_hour - time();

                                                                if ($remaining_seconds <= 0) {
                                                                    $remaining_seconds = 0;
                                                                }

                                                                $remaining_hours = floor($remaining_seconds / 3600);
                                                                $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
                                                                $remaining_seconds = $remaining_seconds % 60;

                                                                $remaining_time = $remaining_hours . ' : ' . $remaining_minutes . ' : ' . $remaining_seconds;

                                                            @endphp



                                                            @if (isset($users['kid']) && $users['kid']->hours == 'Unlimited')
                                                                <td>{{ $users['kid']->hours }}</td>
                                                            @else
                                                                <td>{{ $remaining_time }}</td>
                                                            @endif
                                                            <td class="gree_c">
                                                                <span>{{ isset($users['userDetails']->number_of_kids) ? $users['userDetails']->number_of_kids : '' }}</span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    id="time_hrs">{{ isset($users['kid']->hours) ? $users['kid']->hours : '' }}</span>
                                                            </td>
                                                            <td>{{ isset($users['paymentInfo']->payment_method) ? $users['paymentInfo']->payment_method : '' }}
                                                            </td>

                                                            <td>{{ $users->role == 1 ? 'Ticket' : '' }}
                                                                {{ $users->role == 2 ? 'Membership' : '' }}
                                                            </td>
                                                            <td>
                                                                @if (can('tickets-edit'))
                                                                    <a
                                                                        href="{{ url('/tickets-edit-view/' . $users->id) }}""><i
                                                                            class="fa fa-pencil"
                                                                            aria-hidden="true"></i></a>
                                                                @endif
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                <a href="{{ url('/invoice-pdf/' . $users->id) }}"
                                                                    target="_blank"><i class="fa fa-file"
                                                                        aria-hidden="true"></i></a>

                                                            </td>
                                                        </tr>

                                                        @if ($users->role == 1)
                                                            @php
                                                                $totalAmount += $users['paymentInfo']->amounts ?? 0;
                                                            @endphp
                                                        @elseif ($users->role == 2)
                                                            @php
                                                                $totalAmount += $users['membership']->amount ?? 0;
                                                            @endphp
                                                        @endif
                                                    @endif
                                                @endforeach
                                                @isset($event_bookings)

                                                    @foreach ($event_bookings as $event)
                                                        <tr>
                                                            <td>-</td>
                                                            <td>{{ $event->bill_name }}</td>
                                                            <td>{{ $event->kid_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($event->booking_date)->format('d M, Y') }}
                                                            </td>
                                                            <td>{{ $event->phone }}</td>
                                                            <td>{{ number_format($event->gst18 / 2, 2) }}</td>
                                                            <td>{{ number_format($event->gst18 / 2, 2) }}</td>
                                                            <td>{{ number_format($event->gst5 / 2, 2) }}</td>
                                                            <td>{{ number_format($event->gst5 / 2, 2) }}</td>
                                                            <td>{{ $event->totalamount }}</td>
                                                            <td>-</td>
                                                            <td class="gree_c">
                                                                <span>1</span>
                                                            </td>
                                                            <td>NA</td>
                                                            <td>{{ $event->advance_payment_method }}</td>
                                                            <td>Event</td>
                                                            <td>
                                                                <a href="{{ url('/edit-event', $event->id) }}""><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                <a href="{{ url('/invoice-event-pdf', $event->id) }}"
                                                                    target="_blank"><i class="fa fa-file"
                                                                        aria-hidden="true"></i></a>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalAmount += $event->totalamount;
                                                        @endphp
                                                    @endforeach

                                                @endisset
                                                @isset($openbillings)
                                                    @foreach ($openbillings as $openbilling)
                                                        <tr>
                                                            <td>-</td>
                                                            <td>{{ $openbilling->bill_name }}</td>
                                                            <td>NA</td>
                                                            <td>{{ \Carbon\Carbon::parse($openbilling->created_at)->format('d M, Y') }}
                                                            </td>
                                                            <td>{{ $openbilling->phone }}</td>
                                                            <td>{{ number_format($openbilling->gst18 / 2, 2) }}</td>
                                                            <td>{{ number_format($openbilling->gst18 / 2, 2) }}</td>
                                                            <td>{{ number_format($openbilling->gst5 / 2, 2) }}</td>
                                                            <td>{{ number_format($openbilling->gst5 / 2, 2) }}</td>
                                                            <td>{{ $openbilling->totalamount }}</td>
                                                            <td>-</td>
                                                            <td>
                                                                -
                                                            </td>
                                                            <td>NA</td>
                                                            <td>{{ $openbilling->payment_method }}</td>
                                                            <td>Open Billing</td>
                                                            <td>
                                                                <a
                                                                    href="{{ url('/edit-open-billing', $openbilling->id) }}""><i
                                                                        class="fa fa-pencil" aria-hidden="true"></i></a>
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                                <a href="{{ url('/invoice-open-billing-pdf', $openbilling->id) }}"
                                                                    target="_blank"><i class="fa fa-file"
                                                                        aria-hidden="true"></i></a>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            $totalAmount += $openbilling->totalamount;
                                                        @endphp
                                                    @endforeach

                                                @endisset

                                            </tbody>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>

                                                <td><b>Total:</b></td>
                                                <td><b>{{ number_format($totalAmount, 2) }}</b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
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
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <!--app JS-->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $('#reservation1').daterangepicker({
            maxDate: new Date()
        });
    </script>

    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],

            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
        // 		jQuery(document).ready(function() {
        //     			jQuery('#example').DataTable(

        //          {

        //       "aLengthMenu": [[5, 10, 25, -1], [5, 10, 25, "All"]],
        //         "iDisplayLength": 10
        //        }
        //         );
        // } );


        function checkAll(bx) {
            var cbs = document.getElementsByTagName('input');
            for (var i = 0; i < cbs.length; i++) {
                if (cbs[i].type == 'checkbox') {
                    cbs[i].checked = bx.checked;
                }
            }
        }


        document.getElementById('user_delete').addEventListener("click", function() {
            var selectedItems = [];
            $('.users_checkbox:checked').each(function() {
                var userId = $(this).data('item-id');
                selectedItems.push(userId);
            });
            console.log(selectedItems);
            var baseUrl = "{{ URL::to('/') }}";
            if (selectedItems.length > 0 && confirm('Are you sure you want to delete this data?')) {
                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-customer',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        selectedItems: selectedItems
                    },
                    success: function(response) {
                        toastr.success('Customer deleted successfully');
                        location.reload();
                        $('#message_container').text('Customer deleted successfully');
                    },
                    error: function(xhr, status, error) {
                        var statusCode = xhr.status;
                        var errorMessage = xhr.responseJSON.success;
                        if (statusCode == 500) {
                            toastr.error(errorMessage);
                            $('#message_container').text(errorMessage);
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
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
    </script>
</body>

</html>
