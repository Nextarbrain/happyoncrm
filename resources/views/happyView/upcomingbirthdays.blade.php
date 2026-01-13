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
                                        <p>User List ({{ count($user) }}) </p>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="add_btn">
                                            <a href="{{ url('/tickets') }}">ADD NEW +</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <div class="count" id="user_delete">
                                            <i class="fa fa-trash-o  m-0" aria-hidden="true"></i>
                                        </div>
                                    </div>


                                </div>


                            </div>
                            <div class="user_list">
                                <div class="row align-items-center text-center">
                                    <div class="col-md-8">
                                        <div class="col">
                                            <form action="{{ url()->current() }}" method="get">
                                                @csrf
                                                <div class="d-flex flex-wrap align-items-center">
                                                    <label class="font-weight-bold">Select Date:</label>
                                                    <div class="mb-2 mx-2">
                                                        <input type="date" name="fromDate"
                                                            value="{{ request()->get('fromDate') }}"
                                                            class="form-control-sm border border-primary"
                                                            placeholder="From Date">
                                                    </div>

                                                    <div class="mb-2">
                                                        <input type="date" name="toDate"
                                                            value="{{ request()->get('toDate') }}"
                                                            class="form-control-sm border border-primary"
                                                            placeholder="To Date">
                                                    </div>
                                                    @if (auth('admin')->user()->branch_id == null && auth('admin')->user()->isSuperAdmin == 1)
                                                        <div class="mb-2 mx-2">
                                                            <select name="branch_id" class="form-control"
                                                                id="branch_id">
                                                                <option value="">-- All Branches --</option>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch['id'] }}"
                                                                        {{ request()->get('branch_id') == $branch['id'] ? 'selected' : '' }}>
                                                                        {{ $branch['name'] . '-' . $branch['type'] }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="mb-2 mx-2">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-primary">Apply</button>
                                                    </div>
                                                </div>
                                            </form>
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
                                                    <th>Guardian Name</th>
                                                    <th>First Kid</th>
                                                    <th>Kid DOB</th>
                                                    <th>Birthday in days</th>
                                                    <th>Age</th>
                                                    <th>Amount Spent</th>
                                                    <th>Phone</th>
                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($user as $key => $users)
                                                    <tr>
                                                        <td><input type="checkbox" name="users[]" class="users_checkbox"
                                                                value="{{ $users->user_id }}"
                                                                data-item-id="{{ $users->user_id }}"></td>

                                                        <td class="text-capitalize">
                                                            {{ \App\Models\User::where('id', $users->user_id)->select('name')->first()->name ?? '' }}
                                                        </td>
                                                        @php
                                                            $kid_name = \App\Models\Kid::where(
                                                                'user_id',
                                                                $users->user_id,
                                                            )
                                                                ->select('name')
                                                                ->first();
                                                            $kid_dob = \App\Models\UserDetail::where(
                                                                'user_id',
                                                                $users->user_id,
                                                            )
                                                                ->select('dob')
                                                                ->first();
                                                            $amount_spent = \App\Models\PaymentMethod::select('amounts')
                                                                ->where('user_id', $users->user_id)
                                                                ->sum('amounts');
                                                        @endphp
                                                        @if (isset($kid_name['name']))
                                                            <td class="text-capitalize">{{ $kid_name['name'] }}</td>
                                                        @else
                                                            <td>--</td>
                                                        @endif

                                                        @if (isset($kid_dob['dob']))
                                                            @if (\Carbon\Carbon::parse($kid_dob['dob'], 'Asia/Kolkata')->isToday())
                                                                <td>Today</td>
                                                            @elseif(\Carbon\Carbon::parse($kid_dob['dob'], 'Asia/Kolkata')->isYesterday())
                                                                <td>Yesterday</td>
                                                            @else
                                                                <td>{{ \Carbon\Carbon::parse($kid_dob['dob'])->format('d M Y') }}
                                                                </td>
                                                            @endif
                                                        @else
                                                            <td>--</td>
                                                        @endif
                                                        <td>{{ getBirthDays($kid_dob['dob']) . ' Days' }}</td>
                                                        <td>{{ str_replace(' ago', '', Carbon\Carbon::parse($kid_dob['dob'])->diffForHumans()) }}
                                                        </td>
                                                        <td>{{ $amount_spent }}</td>
                                                        <td>{{ isset($users->phone_no) ? $users->phone_no : '' }}</td>
                                                        <td>{{ isset($users->branch) ? $users->branch->name : '' }}
                                                        </td>

                                                        <td><a
                                                                href="{{ url('/tickets-edit-view/' . $users->user_id) }}""><i
                                                                    class="fa fa-pencil" aria-hidden="true"></i></a>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                            <a href="{{ url('/invoice-pdf/' . $users->user_id) }}"
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
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
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
            if (selectedItems.length > 0) {
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
