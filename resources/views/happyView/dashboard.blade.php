@include('happyView.includes.head')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ url('/datawise-record-show') }}" method="post">
                            @csrf
                            <label>Analysis date wise:</label>
                            <div class="d-flex">
                                <input type="text" name="daterange" class="form-control float-right"
                                    id="reservation">
                                <input type="hidden" name="branch_id" id="branch_id" value="{{ $branchId }}">
                                <input type="submit" value="Apply" name="submit" class="btn btn-primary m-1">
                            </div>
                        </form>
                    </div>
                    <div class="col-md-2 d-flex justify-content-between align-items-center">
                        <div class="fliter" style="z-index:0;">
                            <label>Analysis:</label>
                            @if ($applydate)
                                <p id="applyDate"><b>{{ $applydate }}</b></p>
                            @endif
                            @php
                                $applydate = base64_encode($applydate);
                            @endphp
                        </div>
                    </div>
                    <div class="col-md-3 mt-1">
                        @if (auth('admin')->user()->branch_id == null && auth('admin')->user()->isSuperAdmin == 1)
                        <form id="branchFilterForm" action="{{ url('/datawise-record-show') }}" method="POST">
                            @csrf
                                <label>Branch:</label>
                                <div class="d-flex">
                                    <select name="branch_id" class="form-control" id="branch_id" onchange="document.getElementById('branchFilterForm').submit();">
                                        <option value="">-- All Branches --</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch['id'] }}" {{ $branchId == $branch['id'] ? 'selected' : '' }}>{{ $branch['name']."-".$branch['type'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="col-md-1" style="margin-top:40px; ">
                        <div class="">
                            <a href="{{ route('logoutA') }}" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if (can('dashboard-ticket'))
                        <div class="col-md-3">
                            <div class="d_card green_card">
                                <div class="d_card_wrap">
                                    <div class="price_name">
                                        <h4>{{ $totalTicket }} Ticket Sale</h4>
                                        {{-- <h2 id="ticketSell" > {{formatNumberWithCommas($totalTicketAmount)}}</h2> --}}
                                        <h2 id="ticketSell">{{ $totalTicketAmount }}</h2>

                                    </div>
                                    <div class="card_icon">
                                        <img src="assets/images/user-s.png" alt="icon">
                                    </div>
                                </div>
                                <a href="{{ url('datawise-record-show/' . $applydate) }}">
                                    <div class="d_card_footer">
                                        <p class="text-white">Updated hour ago</p>
                                        <img src="assets/images/right.png" alt="icon">
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if (can('dashboard-sale'))
                        <div class="col-md-3">
                            <div class="d_card yallow_card">
                                <div class="d_card_wrap">
                                    <div class="price_name">
                                        <h4>Total Sales</h4>
                                        <h2 id="total_sell">{{ $totalSales }}</h2>
                                    </div>
                                    <div class="card_icon">
                                        <img src="assets/images/bag.png" alt="icon">
                                    </div>
                                </div>
                                <a href="{{ url('datawise-record-show/' . $applydate) }}">
                                    <div class="d_card_footer">
                                        <p class="text-black">Updated hour ago</p>
                                        <img src="assets/images/right-arrow.png" alt="icon">
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if (can('dashboard-user'))
                        <div class="col-md-3">
                            <div class="d_card green_card">
                                <div class="d_card_wrap">
                                    <div class="price_name">
                                        <h4>User</h4>
                                        <h2>{{ number_format($user, 0) }}</h2>
                                    </div>
                                    <div class="card_icon">
                                        <img src="assets/images/user.png" alt="icon">
                                    </div>
                                </div>
                                <a href="{{ url('datawise-record-show/' . $applydate) }}">
                                    <div class="d_card_footer">
                                        <p class="text-white">Updated hour ago</p>
                                        <img src="assets/images/right.png" alt="icon">
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                    @if (can('dashboard-kid'))
                        <div class="col-md-3">
                            <div class="d_card blue_card">
                                <div class="d_card_wrap">
                                    <div class="price_name">
                                        <h4>Kid</h4>
                                        <h2>{{ number_format($kid, 0) }}</h2>
                                    </div>
                                    <div class="card_icon">
                                        <img src="assets/images/smily.png" alt="icon">
                                    </div>
                                </div>
                                <a href="{{ url('datawise-record-show/' . $applydate) }}">
                                    <div class="d_card_footer">
                                        <p class="text-white">Updated hour ago</p>
                                        <img src="assets/images/right.png" alt="icon">
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="row">
                            @if (can('dashboard-discount'))
                                <div class="col-md-3">
                                    <div class="d_card blue_card">
                                        <div class="d_card_wrap">
                                            <div class="price_name">
                                                <h4>{{ number_format($couponcodes, 2) }} Discount Given</h4>
                                                <h2 id="coupon_code_value">{{ $couponvalue }} </h2>
                                            </div>
                                            <div class="card_icon">
                                                <img src="assets/images/calander.png" alt="icon">
                                            </div>
                                        </div>
                                        <div class="d_card_footer">
                                            <p>Updated hour ago</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if (can('dashboard-membership'))
                                <div class="col-md-3">
                                    <div class="d_card green_card">
                                        <div class="d_card_wrap">
                                            <div class="price_name">
                                                <h4>{{ $totalMembershipBuy }} Memberships Sales</h4>
                                                <h2 id="total_member_amount">{{ $totalMemberAmount }}</h2>
                                            </div>
                                            <div class="card_icon">
                                                <img src="assets/images/membar.png" alt="icon">
                                            </div>
                                        </div>
                                        <a href="{{ url('datawise-record-show/' . $applydate) }}?key=membership">
                                            <div class="d_card_footer">
                                                <p class="text-white">Updated hour ago</p>
                                                <img src="assets/images/right.png" alt="icon">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (can('dashboard-openbilling'))
                                <div class="col-md-3">
                                    <div class="d_card green_card">
                                        <div class="d_card_wrap">
                                            <div class="price_name">
                                                <h4>{{ $totalOpenBilling }} Open Billings</h4>
                                                <h2 id="total_open_billing_sale">{{ $totalOpenBillingSales }}</h2>
                                            </div>
                                            <div class="card_icon">
                                                <img src="assets/images/membar.png" alt="icon">
                                            </div>
                                        </div>
                                        <a href="{{ url('datawise-record-show/' . $applydate) }}?key=openbillings">
                                            <div class="d_card_footer">
                                                <p class="text-white">Updated hour ago</p>
                                                <img src="assets/images/right.png" alt="icon">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @if (can('dashboard-shocks'))
                                <div class="col-md-3">
                                    <div class="d_card green_card">
                                        <div class="d_card_wrap">
                                            <div class="price_name">
                                                <h4>Socks</h4>
                                                <h2>{{ $shocksCount }}</h2>
                                            </div>
                                            <div class="card_icon">
                                                <img src="assets/images/membar.png" alt="icon">
                                            </div>
                                        </div>
                                        <a href="javascript:void(0)">
                                            <div class="d_card_footer">
                                                <p class="text-white">Updated hour ago</p>
                                                <img src="assets/images/right.png" alt="icon">
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    @if (can('dashboard-membership'))
                        <div class="col-md-6">
                            <div class="membar_wrap">
                                <div class="membar_heading">
                                    <h2>Membership </h2>
                                    <p><a href="{{ url('/memberships') }}">View All</a></p>
                                </div>
                                @if ($member)
                                    @foreach ($member as $members)
                                        <div class="result_wrap">
                                            <div class="chart_name">
                                                <div class="chart_name_date">
                                                    <h4>{{ ucwords($members['name']) }}</h4>
                                                    @if (isset($members['membership']) && $members['membership'] != '')
                                                        <p>{{ \Carbon\Carbon::parse(date('Y-m-d'))->format('d F Y') }}
                                                            |
                                                            {{ $members['membership']->credit_left . ' Credit Left' }}
                                                            |
                                                            {{ $members['userDetails']->phone_no }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="day">
                                                @if (isset($members['membership']) && $members['membership'] != '')
                                                    @php
                                                        $start = strtotime(date('Y-m-d'));
                                                        $end = strtotime($members['membership']['renewal_date']);
                                                    @endphp
                                                    <p>{{ $days = (int) (($end - $start) / 86400) }} days left</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="col-md-6">
                        @if (can('dashboard-payments'))
                            <div class="kid_birthday">
                                <div class="row">

                                    <div class="col-md-5 payment_left">
                                        <h6>Payments</h6>
                                        <div class="row">
                                            <a href="{{ url('/payment-method-filter/' . $applydate) }}?key=Cash">
                                                <div class="payment d-flex justify-content-between align-items-center">
                                                    <h4><img src="assets/images/EllipseGreen.jpg" alt="icon">
                                                        Cash
                                                    </h4>
                                                    <p>₹{{ $cash }}</p>
                                                </div>
                                            </a>
                                            <a href="{{ url('/payment-method-filter/' . $applydate) }}?key=UPI">
                                                <div class="payment d-flex justify-content-between align-items-center">
                                                    <h4><img src="assets/images/EllipseYellow.jpg" alt="icon">
                                                        &nbsp;UPI
                                                    </h4>
                                                    <p>₹{{ $upi }}</p>
                                                </div>
                                            </a>
                                            <a
                                                href="{{ url('/payment-method-filter/' . $applydate) }}?key=Debit/Credit">
                                                <div class="payment d-flex justify-content-between align-items-center">
                                                    <h4><img src="assets/images/EllipseBlue.jpg" alt="icon">
                                                        &nbsp;Debit/Credit</h4>
                                                    <p>₹{{ $card }}</p>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="col-md-3 payment_left">
                                        <h6>Taxes</h6>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="kid">
                                                    <h4>GST 18%</h4>
                                                    <p>₹{{ $gst18 }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="kid">
                                                    <h4>GST 12%</h4>
                                                    <p>₹{{ $gst12 }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="kid">
                                                    <h4>GST 5%</h4>
                                                    <p>₹{{ $gst5 }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <a href="{{ url('/upcomingbirthdays') }}">
                                            <h6>Upcoming Birthdays</h6>
                                        </a>
                                        <div class="row">
                                            @foreach ($customers as $customer)
                                                <div class="col-md-12">
                                                    <div class="">
                                                        <div class="kid">
                                                            <h4>{{ \App\Models\User::where('id', $customer->user_id)->select('name')->first()->name ?? 'hh' }}
                                                            </h4>
                                                            @if (\Carbon\Carbon::parse($customer->dob, 'Asia/Kolkata')->isToday())
                                                                <p>Today</p>
                                                            @elseif(\Carbon\Carbon::parse($customer->dob, 'Asia/Kolkata')->isYesterday())
                                                                <p>Yesterday</p>
                                                            @else
                                                                <p>{{ \Carbon\Carbon::parse($customer->dob)->format('d M, Y') }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                </div>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-md-8">
                        @if (can('dashboard-crmgraph'))
                            {{-- <div id="curve_chart" class="rounded w-100" style="height:400px;"></div> --}}
                            <canvas id="barGraph"></canvas>
                        @endif
                    </div>
                    <div class="col-md-4">
                        @if (can('dashboard-event'))
                            <div class="d_card blue_card">
                                <div class="d_card_wrap">
                                    <div class="price_name">
                                        <h4>{{ $event_booking }} Events Sales</h4>
                                        <h2>₹ {{ $event_sale }}</h2>
                                    </div>
                                    <div class="card_icon">
                                        <img src="assets/images/user-s.png" alt="icon">
                                    </div>
                                </div>
                                @if (base64_decode($applydate) == 'Today')
                                    <a href="{{ url('eventsbookingmonth') }}">
                                        <div class="d_card_footer">
                                            <p class="text-white">{{ $event_booking_month }} Bookings this Month
                                            </p>
                                            <img src="assets/images/right.png" alt="icon">
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ url('datawise-record-show/' . $applydate) }}?key=event_booking">
                                        <div class="d_card_footer">
                                            <p class="text-white">{{ $event_booking }} Events</p>
                                            <img src="assets/images/right.png" alt="icon">
                                        </div>
                                    </a>
                                @endif
                            </div>
                        @endif
                        @if (can('dashboard-upcoming-event'))
                            <div class="booking_wrap">
                                <div class="booking_heading">
                                    <h5>Upcoming Events</h5>
                                    <p><a href="{{ url('/upcomingeventsbooking') }}">View All</a></p>
                                </div>
                                @foreach ($event_bookings as $item)
                                    <div class="row name_count">
                                        <div class="col-md-5">
                                            <h5>{{ $item['bill_name'] }}</h5>
                                        </div>
                                        <div class="col-md-4">
                                            @if (\Carbon\Carbon::parse($item['booking_date'], 'Asia/Kolkata')->isToday())
                                                <p>Today</p>
                                            @elseif(\Carbon\Carbon::parse($item['booking_date'], 'Asia/Kolkata')->isYesterday())
                                                <p>Yesterday</p>
                                            @else
                                                <p>{{ \Carbon\Carbon::parse($item['booking_date'])->format('d M, Y') }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <p>{{ $item['phone'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    {{-- <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/js/index.js"></script> --}}
    <script src="assets/plugins/moment/moment.min.js"></script>
    <script src="assets/plugins/daterangepicker/daterangepicker.js"></script>
    <!--app JS-->
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        $('#reservation').daterangepicker({
            maxDate: new Date(),          
        });
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        var currentUrl = window.location.href;
        var baseUrl = window.location.protocol + "//" + window.location.host;
        var relativeUrl = currentUrl.replace(baseUrl, "");
        const finalDate = (relativeUrl == '/datawise-record-show') ? dateValue = document.querySelector('#applyDate')
            .innerText : '';

        $.ajax({
            url: "{{ url('month_wise_sale') }}",
            type: "GET",
            data: {
                date: finalDate
            },
            success: function(dataReturn) {
                if (dataReturn == false) {
                    toastr.warning("Something Went wrong")
                } else {
                    var months = [];
                    var ticketCounts = [];
                    var eventCounts = [];
                    var memberCounts = [];
                    dataReturn.forEach(element => {
                        if (element[0] != 'Month') {
                            months.push(element[0]);
                        }
                        if (element[1] != 'Ticket') {
                            ticketCounts.push(element[1]);
                        }
                        if (element[2] != 'Member') {
                            memberCounts.push(element[2]);
                        }
                        if (element[3] != 'Event') {
                            eventCounts.push(element[3]);
                        }
                    });
                    var ctx = document.getElementById('barGraph').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: months,
                            datasets: [{
                                    label: 'Ticket Count',
                                    data: ticketCounts,
                                    backgroundColor: '#FADF08',
                                    borderColor: '#FADF08',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Event Count',
                                    data: eventCounts,
                                    backgroundColor: '#0A5F59',
                                    borderColor: '#0A5F59',
                                    borderWidth: 1
                                },
                                {
                                    label: 'Member Count',
                                    data: memberCounts,
                                    backgroundColor: '#252B42',
                                    borderColor: '#252B42',
                                    borderWidth: 1
                                }
                            ]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    stepSize: 1
                                }
                            }
                        }
                    });                    
                }
            }
        });
    </script>
    <script>
        jQuery(document).ready(function() {
            jQuery('#example').DataTable({
                "aLengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, "All"]
                ],
                "iDisplayLength": 10
            });
        });

        function checkAll(bx) {
            var cbs = document.getElementsByTagName('input');
            for (var i = 0; i < cbs.length; i++) {
                if (cbs[i].type == 'checkbox') {
                    cbs[i].checked = bx.checked;
                }
            }
        }
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

    <script>
        function formatNumber(elementId, number) {
            let elementText = $('#' + elementId).text();
            let numberValue = parseFloat(elementText);
            if (!isNaN(numberValue)) {
                let formattedNumber = numberValue.toLocaleString('en-IN');
                $('#' + elementId).text('₹'+formattedNumber);
            } else {
                $('#' + elementId).text('Invalid number format');
            }
        }

        $(document).ready(function() {
            formatNumber('ticketSell',"{{ $totalTicketAmount }}");
            formatNumber('total_sell',"{{ $totalSales }}");
            formatNumber('coupon_code_value',"{{ $couponvalue }}");
            formatNumber('total_member_amount',"{{ $totalMemberAmount }}");
            formatNumber('total_open_billing_sale',"{{ $totalOpenBillingSales }}");            
        });

        function branchWiseData(selectElement) {
            var branchId = selectElement.value;

            if (branchId) {
                $.ajax({
                    url: "{{ url('/branch-wise-data') }}", // Update this to your actual route
                    type: "POST",
                    data: {
                        branch_id: branchId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {

                    },
                    error: function(xhr) {
                        console.error("Error fetching branch data:", xhr);
                    }
                });
            }
        }
    </script>
</body>

</html>
