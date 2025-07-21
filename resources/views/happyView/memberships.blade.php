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
                                <div class="row mb-3">
                                    <div class="col">
                                        <h5 class="mb-0">All ({{ count($membershipUsers) }})</h5>
                                    </div>
                                </div>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-6">
                                        <div id="datatable-buttons"></div>
                                    </div>
                                    <div
                                        class="col-md-6 text-md-right d-flex justify-content-md-end justify-content-start align-items-center flex-wrap">
                                        <div id="user_delete" class="mr-3 mb-2">
                                            @if (can('admin-membership-delete'))
                                                <i class="fa fa-trash-o fa-lg" id="membership_div"
                                                    style="cursor: pointer;" aria-hidden="true"></i>
                                            @endif
                                        </div>
                                        <div class="mr-2 mb-2 mx-3">
                                            <input type="search" id="datatable-search"
                                                class="form-control form-control-sm" placeholder="Search...">
                                        </div>
                                        <div class="mb-2">
                                            @if (can('membership-add'))
                                                <button type="button" class="btn btn-sm btn-primary"
                                                    data-bs-toggle="modal" data-bs-target="#Addnew">ADD NEW +</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- data table  -->
                            <div class="container">
                                <div class="row table-responsive">
                                    <div class="data_table membartable">
                                        <table id="example1" class="table table-striped text-center"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" name="checkAll-member"
                                                            onclick="checkAll(this)"></th>
                                                    <th>Kid Name</th>
                                                    <th>Date</th>
                                                    <th>Renewal</th>
                                                    <th>Plan</th>
                                                    <th>Phone</th>
                                                    <th>Credit Left</th>
                                                    <th>Payment Method</th>
                                                    <th>Kids</th>
                                                    <th>Status</th>
                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($membershipUsers as $membershipUser)
                                                    <tr>
                                                        <td><input name="member-check" type="checkbox"
                                                                data-item-id="{{ $membershipUser->id }}"
                                                                class="membership_checkbox"></td>
                                                        <td class="text-capitalize">
                                                            {{ optional($membershipUser['user'])->name ?? '' }}
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
                                                        <td>{{ optional($membershipUser['userDetails'])->phone_no ?? '' }}</td>                                                        
                                                        <td>{{ $membershipUser->credit_left }} hrs</td>
                                                        <td>{{ $membershipUser->payment_method }}</td>
                                                        <td class="gree_c">
                                                            <span>{{ $membershipUser['userDetails']->number_of_kids }}</span>
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </td>
                                                        <td class="status">SMS Sent</td>
                                                        <td>{{ optional($membershipUser['branch'])->name ?? '' }}</td>
                                                        <td><a href="#"><i class="fa fa-whatsapp"
                                                                    aria-hidden="true"></i></a>
                                                            <a target="_blank" class="btn btn-sm btn-primary"
                                                                style="width: 30px; height: 30px; border-radius: 50%;"
                                                                href="{{ url('invoice-membership-pdf/' . optional($membershipUser['user'])->id ?? '') }}"><i
                                                                    class="fa fa-file" aria-hidden="true"></i></a>
                                                            <a class="btn btn-sm btn-dark"
                                                                style="width: 30px; height: 30px; border-radius: 50%;"
                                                                href="{{ url('membership-history/' . optional($membershipUser['user'])->id ?? '') }}"><i
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

    <!-- popup form  -->
    <div class="modal fade" id="Addnew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog add-form">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="h_btn">
                        <button type="button" class="btn" style="pointer-events: none;">Add New
                            Membership</button>
                    </div>
                    <form action="{{ url('/add-membership-plan') }}" method="post">
                        @csrf
                        <div class="row">
                            @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                <div class="col-md-12 ">
                                    <div class="mb-4 input_wrap">
                                        <label for="branchId">Branch<span>*</span></label>
                                        <select name="branch_id" class="form-select" id="branchId" required>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch['id'] }}">
                                                    {{ $branch['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-4 input_wrap dark">
                                    <label for='date'>Date</label>
                                    <input type="date" class="form-control" placeholder="date" name="date"
                                        id="date" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6 input_wrap dark time">
                                <div class="mb-4">
                                    <label for='time'>Time</label>
                                    <input type="time" class="form-control" placeholder="Time" name="time"
                                        id="time" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4 input_wrap">
                                    <label for="name">Member Name <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Name of Kid"
                                        name="name" id='name' required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4 input_wrap">
                                    <label for="phone">Phone <span>*</span></label>
                                    <input type="text" class="form-control" placeholder="Phone" name="phone"
                                        id="phone" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <div class="mb-4 input_wrap">
                                    <label for="membershipPlan">Choose Plan <span>*</span></label>
                                    <select name="membershipPlan" id="membershipPlan" class="form-select" required>
                                        <option value="">Select Plan
                                        </option>
                                        @foreach ($membership as $memberships)
                                            <option value="{{ $memberships->id }}">
                                                {{ $memberships->plan_name }} -
                                                {{ $memberships->credits }}
                                                Entry</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4 input_wrap">
                                    <label for="payment_mode">Payment Mode</label>
                                    <select name="payment_mode" id="payment_mode" class="form-select">
                                        <option value="1">UPI</option>
                                        <option value="2">Cash</option>
                                        <option value="3">Debit/Credit
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4 input_wrap">
                                    <label for="coupon_code">COUPAN CODE</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control" placeholder="Any Discount Code"
                                            name="coupon_code" id="coupon_code">
                                        <button type="button" class="btn btn-sm btn-primary ms-2"
                                            id="apply-coupon">Apply</button>
                                        <button type="button" class="btn btn-sm btn-warning ms-2 disabled"
                                            id="remove-coupon">Remove</button>
                                    </div>
                                    <p id="couponmsg"></p>
                                    <input type="hidden" id="plan_value">
                                </div>
                            </div>
                            <div class="total_wrap">
                                <div class="subtotal">
                                    <p>Subtotal</p>
                                    <p id="price"></p>
                                    <input type="hidden" id="subtotal" name="subtotal">
                                </div>
                                <hr>
                                <div class="gst1">
                                    <p id="couponCodeShow">Coupon </span></p>
                                    <p id="couponValueShow"></p>
                                    <input type="hidden" id="couponDis" name="couponDis">
                                </div>
                                <hr>
                                <div class="gst">
                                    <p id="gst">Includes Gst @ 18% on
                                    </p>
                                    <p id="gstTotal"></p>
                                    <input type="hidden" id="gst18" name="gst18">
                                </div>
                                <hr>
                                <div class="amount">
                                    <p>Total Amount</p>
                                    <p id="totalAmount">₹</p>
                                    <input type="hidden" id="wholeAMount" name="totalamount">
                                </div>
                                <hr>
                            </div>
                            <div class="tearm_condition">
                                <input type="checkbox" id="term-text" name="term-text" value=""
                                    checked="checked">
                                <p>By Agreeing to the <b>Terms and Condition</b>
                                    of HappyOn</p>
                            </div>
                            <div class="s_btn d-flex gap-2 my-3">
                                <button type="submit" class="btn">Create
                                    Member and Add Credit</button>
                                <a href="javascript:void(0);" class="btn btn-secondary"
                                    style="padding-top: 12px;font-size: 18px;" onclick="handleCancel()">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end popup form  -->

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

    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
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

    <script>
        function checkAll(bx) {
            var cbs = document.getElementsByTagName('input');
            for (var i = 0; i < cbs.length; i++) {
                if (cbs[i].type == 'checkbox') {
                    cbs[i].checked = bx.checked;
                }
            }
        }

        function handleCancel() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('Addnew'));
            modal.hide();
            setTimeout(() => {
                location.reload();
            }, 300);
        }

        $(document).ready(function() {
            $('#branchId').change(function() {
                getBranchPlan();
            });
            $('#membershipPlan').change(function() {
                getMembershipPlan();
            });
            $('input[name="coupon_code"]').blur(function() {
                // getMembershipPlan();
            });
            $('#apply-coupon').click(function() {
                getMembershipPlan();
            });
            $('#remove-coupon').click(function() {
                $('#coupon_code').val('');
                $('#apply-coupon').removeClass('disabled');
                getMembershipPlan();
                $('#remove-coupon').addClass('disabled');
            });
        });

        function getBranchPlan() {
            var branchId = $('#branchId').val();
            var baseUrl = "{{ URL::to('/') }}";
            $.ajax({
                url: baseUrl + '/get-branch-plan',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                data: {
                    branch_id: branchId
                },
                success: function(response) {
                    if (response) {
                        $('#membershipPlan').empty();
                        $('#membershipPlan').append('<option value="">Select Plan</option>');

                        // Add new options
                        $.each(response, function(index, plan) {
                            $('#membershipPlan').append(
                                '<option value="' + plan.id + '">' +
                                plan.plan_name + ' - ' +
                                plan.credits + ' Entry</option>'
                            );
                        });
                    }
                },
                error: function(xhr) {
                    alert('Failed to load plans.');
                }
            });
        }

        function getMembershipPlan() {
            var membershipId = $('#membershipPlan').val();
            var branchId = $('#branchId').val();
            var couponcode = $('input[name="coupon_code"]').val();
            var baseUrl = "{{ URL::to('/') }}";
            $.ajax({
                url: baseUrl + '/get-membership-plan',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: {
                    id: membershipId,
                    coupon: couponcode,
                    branch_id: branchId
                },
                success: function(response) {
                    if (response.status == false) {
                        $('#couponmsg').html("<p style='color:red;'>" + response.message + "</p>");
                        return false;
                    }
                    if (response && response.price) {
                        $('#plan_value').val(response.price);
                        var fullPrice = parseFloat(response.price);
                        var total = response.subtotal / 1.18;
                        total = total.toFixed(2);                        
                        $('#subtotal').val(parseFloat(response.subtotal));
                        /* plan price should be the same  gstTotal */
                        let stGSTC = response.subtotal - total;
                        // stGSTC = stGSTC.toFixed(2);
                        $('#gst').html("Includes Gst @18% on ₹ " + response.subtotal);
                        let couponDiscountAmount = 0;
                        if (response && response.coupan_name) {
                            $('#remove-coupon').removeClass('disabled');
                            $('#apply-coupon').addClass('disabled');
                            couponDiscountAmount = parseFloat(response.discounted_amt);                             
                            $('#couponCodeShow').html("Coupon Code (" + response.coupan_name + ")");
                            $('#couponValueShow').html(couponDiscountAmount.toFixed(2));
                            $('#couponDis').val(couponDiscountAmount.toFixed(2));
                        } else {
                            couponDiscountAmount = 0;
                            $('#couponCodeShow').html("Coupon");
                            $('#couponValueShow').html('');                            
                        }                        
                        total -= couponDiscountAmount.toFixed(2);                        
                        $('#price').text('₹ ' + total.toFixed(2));
                        $('#subtotal').val(total.toFixed(2));
                        // var total = parseFloat(response.subtotal / 1.18);
                        stGSTC = (total * 18)/100;
                        stGSTC = stGSTC.toFixed(2);                        
                        $('#gstTotal').html(stGSTC);
                        $('#gst18').val(stGSTC);
                        // let totalAmount = parseFloat(response.subtotal) - couponDiscountAmount;                        
                        let totalAmount = parseFloat(total) + parseFloat(stGSTC);                        
                        $('#totalAmount').html('₹ ' + totalAmount);
                        $('#wholeAMount').val(totalAmount);
                    } else {
                        $('#plan_value').val(0);
                        $('#subtotal').val(0);
                        $('#price').text('₹ 0');
                        $('#gst').html("Includes Gst @18% on ₹ 0");
                        $('#gstTotal').html(0);
                        $('#gst18').val(0);
                        $('#totalAmount').html('₹ 0');
                        $('#wholeAMount').val(0);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
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
            }, 3000);
        });

        document.getElementById('membership_div').addEventListener("click", function() {
            var selectedItems = [];
            $('.membership_checkbox:checked').each(function() {
                var membershipId = $(this).data('item-id');
                selectedItems.push(membershipId);
            });
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
            } else {
                toastr.error('Please select at least one membership to delete');
                $('#message_container').text('Please select at least one membership to delete');
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
