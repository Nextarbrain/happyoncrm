@include('happyView.includes.head')

<body>
    <!--wrapper-->
    <div class="wrapper">
        @include('happyView.includes.sidebar')
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    @if (session()->has('success'))
                        <div id="success-message" class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif
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
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h4>Default MSG</h4>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="message_wrap">
                                            <textarea id="message" name="message" rows="4" cols="50"
                                                placeholder="Happy [Renewal Date] [Name]! As a valued member of our family playing area, we want to thank you for your continued support. To show our appreciation, we are offering you a special discount on your renewal fee. Come join us for some fun and games! We look forward to seeing you soon."></textarea>
                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <!-- data table  -->
                            <div class="container">
                                <div class="row">
                                    <div class="data_table membartable table-responsive">
                                        <div class="table_header">
                                            <div class="count">
                                                <p>All ({{ count($membershipUsers) }})</p>
                                            </div>
                                            @if (can('membership-add'))
                                                <div class="add_btn">
                                                    <button type="button" class="btn" data-bs-toggle="modal"
                                                        data-bs-target="#Addnew">ADD NEW +</button>
                                                </div>
                                            @endif

                                            <div class="col-md-3 pl-3">

                                                @if (can('admin-membership-delete'))
                                                    <div class="count" id="membership_div">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- <div class="col-md-3 pl-3">
												<form action="{{url('/membership-booking-report')}}" method="post">
													@csrf

													<div class="d-flex">
													<input type="text" name="daterange" class="form-control float-right" id="reservation1">
													<input type="submit" value="Apply" name="submit" class="btn btn-primary m-1">
													</div>
												</form>
											</div> --}}

                                            <br><br><br>
                                            <!-- popup form  -->
                                            <div class="modal fade" id="Addnew" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog add-form">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="h_btn">
                                                                <button type="button" class="btn">Add New
                                                                    Membership</button>
                                                            </div>

                                                            <form action="{{ url('/add-membership-plan') }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="mb-4 input_wrap dark">
                                                                            <label>Date</label>
                                                                            <input type="date" class="form-control"
                                                                                placeholder="date" name="date"
                                                                                id="date" required readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 input_wrap dark time">
                                                                        <div class="mb-4">
                                                                            <label>Time</label>
                                                                            <input type="time" class="form-control"
                                                                                placeholder="Time" name="time"
                                                                                id="time" required readonly>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-4 input_wrap">
                                                                            <label>Member Name <span>*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Name of Kid" name="name"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="mb-4 input_wrap">
                                                                            <label>Phone <span>*</span></label>
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Phone" name="phone"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 ">
                                                                        <div class="mb-4 input_wrap">
                                                                            <label>Choose Plan <span>*</span></label>
                                                                            <select name="membershipPlan"
                                                                                id="membershipPlan" class="form-select"
                                                                                required>
                                                                                <option value="Select">Select Plan
                                                                                </option>
                                                                                @foreach ($membership as $memberships)
                                                                                    <option
                                                                                        value="{{ $memberships->id }}">
                                                                                        {{ $memberships->plan_name }} -
                                                                                        {{ $memberships->credits }}
                                                                                        Entry</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-12">
                                                                        <div class="mb-4 input_wrap">
                                                                            <label>Payment Mode</label>
                                                                            <select name="payment_mode"
                                                                                id="payment_mode" class="form-select">
                                                                                <option value="1">UPI</option>
                                                                                <option value="2">Cash</option>
                                                                                <option value="3">Debit/Credit
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <div class="mb-4 input_wrap">
                                                                            <label>COUPAN CODE</label>
                                                                           <div class="d-flex align-items-center">
                                                                            <input type="text" class="form-control"
                                                                                placeholder="Any Discount Code"
                                                                                name="coupon_code">
                                                                                <button type="button" class="btn btn-primary" id="apply-coupon" >Apply</button>
                                                                            </div>
                                                                            <input type="hidden" id="plan_value">
                                                                        </div>
                                                                    </div>
                                                                    <div class="total_wrap">
                                                                        <div class="subtotal">
                                                                            <p>Subtotal</p>
                                                                            <p id="price"></p>
                                                                            <input type="hidden" id="subtotal"
                                                                                name="subtotal">
                                                                        </div>
                                                                        <hr>
                                                                        <div class="gst">
                                                                            <p id="gst">Includes Gst @ 18% on
                                                                            </p>
                                                                            <p id="gstTotal"></p>
                                                                            <input type="hidden" id="gst18"
                                                                                name="gst18">
                                                                        </div>
                                                                        <hr>
                                                                        <div class="amount">
                                                                            <p>Total Amount</p>
                                                                            <p id="totalAmount">₹</p>
                                                                            <input type="hidden" id="wholeAMount"
                                                                                name="totalamount">
                                                                        </div>
                                                                        <hr>
                                                                    </div>
                                                                    <div class="tearm_condition">
                                                                        <input type="checkbox" id=""
                                                                            name="" value=""
                                                                            checked="checked">
                                                                        <p>By Agreeing to the <b>Terms and Condition</b>
                                                                            of HappyOn</p>
                                                                    </div>
                                                                    <div class="s_btn">
                                                                        <button type="submit" class="btn">Create
                                                                            Member and Add Credit</button>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end popup form  -->
                                        </div>

                                        <table id="example1" class="table table-striped text-center"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" onclick="checkAll(this)"></th>
                                                    <th>Kid Name</th>
                                                    <th>Date</th>
                                                    <th>Renewal</th>
                                                    <th>Plan</th>
                                                    <th>Phone</th>
                                                    <th>Credit Left</th>
                                                    <th>Payment Method</th>
                                                    <th>Kids</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($membershipUsers as $membershipUser)
                                                    <tr>
                                                        <td><input type="checkbox"
                                                                data-item-id="{{ $membershipUser->id }}"
                                                                class="membership_checkbox"></td>
                                                        <td class="text-capitalize">{{ $membershipUser['user']->name }}
                                                        </td>

                                                        @if (\Carbon\Carbon::parse($membershipUser->date, 'Asia/Kolkata')->isToday())
                                                            <td>Today</td>
                                                        @elseif(\Carbon\Carbon::parse($membershipUser->date, 'Asia/Kolkata')->isYesterday())
                                                            <td>Yesterday</td>
                                                        @else
                                                            <td>{{ \Carbon\Carbon::parse($membershipUser->date)->format('d M, Y') }}
                                                            </td>
                                                        @endif

                                                        @if (\Carbon\Carbon::parse($membershipUser->renewal_date, 'Asia/Kolkata')->isToday())
                                                            <td>Today</td>
                                                        @elseif(\Carbon\Carbon::parse($membershipUser->renewal_date, 'Asia/Kolkata')->isYesterday())
                                                            <td>Yesterday</td>
                                                        @else
                                                            <td>{{ \Carbon\Carbon::parse($membershipUser->renewal_date)->format('d M, Y') }}
                                                            </td>
                                                        @endif

                                                        @if ($membershipUser->credit_left == 0)
                                                            <td>Expired</td>
                                                        @else
                                                            <td>{{ $membershipUser->plan }}</td>
                                                        @endif
                                                        <td>{{ $membershipUser['userDetails']->phone_no }}</td>
                                                        <td>{{ $membershipUser->credit_left }} hrs</td>
                                                        <td>{{ $membershipUser->payment_method }}</td>
                                                        <td class="gree_c">
                                                            <span>{{ $membershipUser['userDetails']->number_of_kids }}</span>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </td>
                                                        <td class="status">SMS Sent</td>
                                                        <td><a href="#"><i class="fa fa-whatsapp"
                                                                    aria-hidden="true"></i></a>
                                                            <a target="_blank" class="btn btn-sm btn-primary" style="width: 30px; height: 30px; border-radius: 50%;"
                                                                href="{{ url('invoice-membership-pdf/' . $membershipUser['user']->id) }}"><i
                                                                    class="fa fa-file" aria-hidden="true"></i></a>
                                                            <a class="btn btn-sm btn-dark" style="width: 30px; height: 30px; border-radius: 50%;"
                                                                href="{{ url('membership-history/' . $membershipUser['user']->id) }}"><i
                                                                    class="fa fa-list" aria-hidden="true"></i></a>
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

       $(document).ready(function() {
        $('#membershipPlan').change(function() {
            getMembershipPlan();
        });
        $('input[name="coupon_code"]').blur(function() {
            getMembershipPlan();
        });
        $('#apply-coupon').click(function() {
            getMembershipPlan();
        });
    });

    function getMembershipPlan() {
        var membershipId = $('#membershipPlan').val();
        var couponcode = $('input[name="coupon_code"]').val();
        console.log(membershipId);
        var baseUrl = "{{ URL::to('/') }}";
        $.ajax({
            url: baseUrl + '/get-membership-plan',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            data: {
                id: membershipId,
                coupon: couponcode
            },
            success: function(response) {
                console.log(response.price);
                $('#plan_value').val(response.price);
                var fullPrice = '₹ ' + parseFloat(response.price);
                $('#subtotal').val(parseFloat(response.price));
                $('#price').text(fullPrice);
                $('#gst').html("Includes Gst @18% on " + fullPrice);

                var total = (parseFloat(response.price) * 18) / 100;
                $('#gstTotal').html(total);
                $('#gst18').val(total);
                var totalAmount = parseFloat(response.price) + total;
                $('#totalAmount').html('₹ ' + totalAmount);
                $('#wholeAMount').val(totalAmount);



            },
            error: function(xhr) {
                console.log(xhr.responseText); // Handle errors
            }
        });
    }
        //Current Date

        //var today1 =  new Date().toISOString().substr(0,10);
        var today = new Date();
        var day = ("0" + today.getDate()).slice(-2);
        var month = ("0" + (today.getMonth() + 1)).slice(-2);
        var year = today.getFullYear();
        var formattedDate = year + '-' + month + '-' + day;
        document.getElementById('date').value = formattedDate;

        // Current Time
        var dateInput = document.getElementById('date');
        var timeInput = document.getElementById('time');
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');
        var timeString = hours + ":" + minutes;
        timeInput.value = timeString;




        $(document).ready(function() {
            // Remove success message after 3 seconds
            setTimeout(function() {
                $("#success-message").fadeOut("slow", function() {
                    $(this).remove();
                });
            }, 3000); // 3 seconds
        });

        document.getElementById('membership_div').addEventListener("click", function() {
            var selectedItems = [];
            console.log(selectedItems);
            $('.membership_checkbox:checked').each(function() {
                var membershipId = $(this).data('item-id');
                selectedItems.push(membershipId);
            });
            console.log(selectedItems);
            var baseUrl = "{{ URL::to('/') }}";
            if (selectedItems.length > 0) {

                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-membership-buy',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        selectedItems: selectedItems
                    },
                    success: function(response) {
                        console.log(response);
                        toastr.success('Membership deleted successfully');
                        location.reload();
                        $('#message_container').text('Membership deleted successfully');
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
