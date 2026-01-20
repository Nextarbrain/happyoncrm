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
                            <div class="container">
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        {!! $dataTable->table() !!}
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
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {!! $dataTable->scripts() !!}
    <script>
        $(document).ready(function() {
            if ($('#custom-toolbar').length) {
                $('#custom-toolbar').html(
                    `<a href="javascript:void(0)" id="user_delete" class="btn btn-sm me-2">@if (can('tickets-delete'))
                <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>@endif</a>@if (can('customers-add'))
                <a href="{{ url('/tickets') }}" class="btn btn-sm btn-primary">Add New +</a> @endif`
                );
            } else {
                console.error('#custom-toolbar not found!');
            }
            $('.dt-buttons button').addClass('btn-sm');
            document.getElementById('user_delete').addEventListener("click", function() {
                var selectedItems = [];
                $('.users_checkbox:checked').each(function() {
                    var userId = $(this).data('item-id');
                    var paymentId = $(this).data('item-paymentid');
                    var date = $(this).data('item-date');
                    var userdetail = $(this).data('item-userdetail');

                    selectedItems.push({
                        userId: userId,
                        paymentId: paymentId || '',
                        date: date,
                        userdetail: userdetail || '',
                    });
                });

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
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
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
    </script>
</body>

</html>
