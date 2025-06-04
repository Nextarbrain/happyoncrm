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
                                        <p>Billing List ({{ count($open_billings) }})</p>
                                    </div>
                                    <div class="col-md-6"></div>
                                    <div class="col-md-3 d-flex align-items-center justify-content-center">
                                        <div>
                                            <div class="count" id="user_delete">
                                                <i class="fa fa-trash-o m-0" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="add_btn">
                                                <a href="{{ url('/add-open-billing') }}">ADD NEW +</a>
                                            </div>
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
                                                    <th>Billing Name</th>
                                                    <th>Date</th>
                                                    <th>Phone</th>
                                                    <th>Amount</th>
                                                    <th>Time</th>


                                                    <th>Pay Method</th>

                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($open_billings as $item)
                                                    <tr>
                                                        <td><input type="checkbox" name="bookings[]"
                                                                class="users_checkbox" value="{{ $item['id'] }}"
                                                                data-item-id="{{ $item['id'] }}"></td>

                                                        <td class="text-capitalize">{{ $item['bill_name'] }} </td>

                                                        @if (\Carbon\Carbon::parse($item['created_at'], 'Asia/Kolkata')->isToday())
                                                            <td>Today</td>
                                                        @elseif(\Carbon\Carbon::parse($item['created_at'], 'Asia/Kolkata')->isYesterday())
                                                            <td>Yesterday</td>
                                                        @else
                                                            <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d M, Y') }}
                                                            </td>
                                                        @endif



                                                        <td>{{ $item['phone'] }}</td>

                                                        <td>{{ $item['totalamount'] }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('h:i a') }}
                                                        </td>

                                                        <td>{{ $item['payment_method'] }}</td>
                                                        <td>{{ optional($item['branch'])->name ?? '' }}</td>
                                                        <td><a  class="btn btn-sm btn-primary"  href="{{ url('/edit-open-billing', $item['id']) }}""><i
                                                                    class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            <a class="btn btn-sm btn-dark" href="{{ url('/invoice-open-billing-pdf', $item['id']) }}"
                                                                target="_blank"><i class="fa fa-file"
                                                                    aria-hidden="true"></i></a>

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
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <!--app JS-->
    <script src="{{ asset('assets/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
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
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        });
    </script>

    <script>
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
                    url: baseUrl + '/delete-open-billing',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        selectedItems: selectedItems
                    },
                    success: function(response) {
                        toastr.success('Open Billing deleted successfully');
                        location.reload();

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
