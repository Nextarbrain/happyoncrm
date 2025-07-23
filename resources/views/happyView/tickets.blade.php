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
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_left">
                            <div class="tab_wrap">
                                <div class="tab-content">
                                    <div id="choosetheme" class="container tab-pane active" role="tabpanel"><br>
                                        <div class="tab_wrap_theme">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active left" data-bs-toggle="tab" href="#paid" aria-selected="true" role="tab">
                                                        <p class="heading">Customer </p>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link right" data-bs-toggle="tab" href="#free" aria-selected="false" tabindex="-1" role="tab">
                                                        <p class="heading">Membership</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">

                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                <style>
                                                    .phone-list {
                                                        position: absolute;
                                                        z-index: 10000;
                                                        width: 100%;
                                                        background: white;
                                                    }

                                                    .phone-list a p {
                                                        padding: 10px;
                                                        border-bottom: 2px solid red;
                                                    }
                                                </style>
                                                @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                    <form method="GET" action="{{ route('tickets') }}">
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label for='branchId'>Branch <span>*</span></label>
                                                                <select name="branch_id" id="branchId" class="form-select" onchange="this.form.submit()" required>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch['id'] }}" {{ $branch['id'] == $branchId ? 'selected' : '' }}>
                                                                            {{ $branch['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif
                                                <form action="{{ url('/add-tickets') }}" method="post">
                                                    @csrf
                                                    @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                        <input type="hidden" name="branch_id" value="{{ $branchId }}" id="hiddenBranchId">
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label for='date'>Date</label>
                                                                <input type="date" class="form-control" placeholder="date" name="date" id="date" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 input_wrap dark time">
                                                            <div class="mb-4">
                                                                <label for="time">Time</label>
                                                                <input type="time" class="form-control" placeholder="Time" name="time" id="time" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control" placeholder="Phone" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10" required>
                                                                <div id="result" class="shadow phone-list"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="nameOfKid">Guardian Name
                                                                    <span>*</span></label>
                                                                <input type="text" class="form-control" placeholder="Name of Guardian" name="name_of_kid" id="nameOfKid" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="kidsQty">Number of kids
                                                                    <span>*</span></label>
                                                                <select name="kids" id="kidsQty" class="form-select" required>
                                                                    <option value="">0</option>
                                                                    @for ($i = 1; $i <= 50; $i++)
                                                                        <option value="{{ $i }}">
                                                                            {{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="dob">Kid DOB <span>*</span></label>
                                                                <input type="date" class="form-control" placeholder="DOB" name="dob" id="dob" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" id="parent-div">
                                                        </div>
                                                        <table id="dataRow">

                                                        </table>
                                                        <div class="button_wrap">
                                                            @foreach ($product as $pro)
                                                                <div class="h_btn">
                                                                    <a href="javascript:void(0)" onclick="AddItems(event, '{{ $pro->product_name }}', '{{ $pro->product_price }}', '{{ $pro->product_tax }}')" class="more_adult" id="{{ Str::slug($pro->product_name) }}">+
                                                                        {{ $pro->product_name }}</a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <hr>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="services">Payment Mode</label>
                                                                <select name="payment_mode" id="services" class="form-select">
                                                                    <option value="2">Cash</option>
                                                                    <option value="1">UPI</option>
                                                                    <option value="3">Debit/Credit </option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="couponCode">COUPON CODE</label>
                                                                <div class="d-flex">
                                                                    <input type="text" name="couponcode" class="form-control" id="couponCode" placeholder="Any Discount Code">
                                                                    <input type="hidden" name="couponvalue" id="couponValue">
                                                                    <button type="button" id="couponCodebutton" class="btn btn-sm btn-primary ms-2">Apply</button>
                                                                    <button type="button" class="btn btn-sm btn-warning ms-2 disabled d-none" id="remove-coupon">Remove</button>
                                                                </diV>
                                                                <p id="couponmsg"></p>
                                                            </div>
                                                        </div>
                                                        <div class="total_wrap">
                                                            <div class="subtotal">
                                                                <p>Subtotal</p>
                                                                <p id="subtotal"></p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="couponCodeShow">Coupon </span></p>
                                                                <p id="couponValueShow"></p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst">
                                                                <p id="gstOn">Includes Gst @ 18%</p>
                                                                <p id="gstValue"></p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst d-none" id="gst12Container">
                                                                <p id="gst12Percent">Includes Gst @12% </p>
                                                                <p id="gst12PercentVal"></p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="gstSock">Includes Gst @5% </p>
                                                                <p id="valueGst"></p>
                                                            </div>
                                                            <hr>
                                                            <div class="amount">
                                                                <p>Total Amount</p>
                                                                <p id="totalAmount"></p>
                                                                <input type="hidden" name="totalAmount" id="chargeAmount">
                                                                <input type="hidden" name="subTotal" id="subtotlSave">
                                                                <input type="hidden" name="gst18" id="gst18">
                                                                <input type="hidden" name="gst18On" id="gst18On">
                                                                <input type="hidden" name="gst5" id="gst5">
                                                                <input type="hidden" name="gst12" id="gst12">
                                                                <input type="hidden" id="adult_price_backend" value="{{ isset($result[0]['Adult']) ? $result[0]['Adult'] : 200 }}">
                                                                <input type="hidden" id="socks_price_backend" value="{{ isset($result[0]['Socks']) ? $result[0]['Socks'] : '80' }}">
                                                            </div>
                                                            <hr>
                                                        </div>
                                                        <div class="tearm_condition">
                                                            <input type="checkbox" id="term" name="term" value="" checked="checked">
                                                            <p>By Agreeing to the <b>Terms and Condition</b> of HappyOn
                                                            </p>
                                                        </div>
                                                        <div class="h_btn s_btn">
                                                            <button class="btn btn-primary" href="#">Make
                                                                Payment & Start Timer</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div id="free" class="container tab-pane fade" role="tabpanel"><br>
                                                <form action="{{ url('/update-membership') }}" method="post" id="my-form">
                                                    @csrf
                                                    <div class="col-md-12">
                                                        @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                            <div class="col-md-12 ">
                                                                <div class="mb-4 input_wrap">
                                                                    <label for="branch_id">Branch<span>*</span></label>
                                                                    <select name="branch_id" id="branch_id" class="form-select" required>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch['id'] }}">
                                                                                {{ $branch['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="mb-4 input_wrap">
                                                            <label for="mobile_no">Phone Number</label>
                                                            <input type="text" class="form-control" placeholder="+91 0123456789" id="mobile_no" maxlength="10">
                                                            <div id="resultmembership" class="shadow phone-list">
                                                            </div>
                                                            <div class="happy_card">
                                                                <div class="card_header">
                                                                    <div class="card_logo">
                                                                        <img src="assets/images/happyon_logo.png" alt="logo">
                                                                    </div>
                                                                    <div class="card_symbol">
                                                                        <svg width="48" height="44" viewBox="0 0 48 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <g opacity="0.3">
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.194806 35.4381C0.208372 39.6656 3.65516 43.0672 7.89746 43.0503L16.0337 43.0178L16.0063 34.4678L0.191895 34.5309L0.194806 35.4381Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.183275 31.8143L15.9929 31.7512L15.9649 23.0255L0.155273 23.0885L0.183275 31.8143Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.106817 8.02616L0.109744 8.93816L15.9241 8.87512L15.8966 0.315622L7.7604 0.348055C3.51811 0.364966 0.0932657 3.80341 0.106817 8.02616Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M40.0707 0.219304L18.6206 0.30481L18.7576 43.0073L29.6664 42.9638L29.5568 8.82082L47.7763 8.74819L47.7733 7.83619C47.7598 3.61344 44.3082 0.202412 40.0707 0.219304Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M0.14641 20.3755L15.956 20.3125L15.928 11.5868L0.118408 11.6498L0.14641 20.3755Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M32.3197 20.2472L47.8144 20.1855L47.7864 11.4597L32.2917 11.5215L32.3197 20.2472Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M32.3564 31.6861L47.851 31.6243L47.823 22.8986L32.3284 22.9603L32.3564 31.6861Z" fill="#878773" />
                                                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M32.3927 42.9527L40.2092 42.9216C44.4467 42.9047 47.8763 39.4757 47.8628 35.2482L47.8599 34.3409L32.3652 34.4027L32.3927 42.9527Z" fill="#878773" />
                                                                            </g>
                                                                        </svg>
                                                                    </div>
                                                                </div>
                                                                <div class="card_number">
                                                                    <p id="number"></p>
                                                                </div>
                                                                <div class="card_footer">
                                                                    <div class="card_name">
                                                                        <p id="nameCustomer"></p>
                                                                        <p id="planDate"></p>
                                                                    </div>
                                                                    <div class="master_card">
                                                                        <img src="assets/images/Mastercard.png" alt="mastercard">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-4 input_wrap dark">
                                                                    <label for="credit_left">Credit Left</label>
                                                                    <input type="text" class="form-control" placeholder="22 hrs" id="credit_left" name="credit_left" readonly>
                                                                    <input type="hidden" id="user_id" name="user_id">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-4 input_wrap dark">
                                                                    <label for="kidsName2">Kids Name</label>
                                                                    <input type="text" class="form-control" placeholder="Kids Name" id="kidsName2" name="kidsName2" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 ">
                                                                <div class="mb-4 input_wrap">
                                                                    <label for="numberKids">Number of Kids</label>
                                                                    <select name="number_of_kids" id="numberKids" class="form-select">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 ">
                                                                <div class="mb-4 input_wrap">
                                                                    <label for="hrs">Number of Hrs</label>
                                                                    <select name="hrs" id="hrs" class="form-select">
                                                                        <option value="1">1</option>
                                                                        <option value="2">2</option>
                                                                        <option value="3">3</option>
                                                                        <option value="4">4</option>
                                                                        <option value="5">5</option>
                                                                        <option value="6">6</option>
                                                                        <option value="7">7</option>
                                                                        <option value="8">8</option>
                                                                        <option value="9">9</option>
                                                                        <option value="10">10</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="input_button">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label for="response_otp">OTP</label>
                                                                        <input type="text" class="form-control" placeholder="Enter OTP" id="response_otp" name="response_otp" maxlength="6" pattern="\d*" inputmode="numeric" required>
                                                                        <div class="h_btn">
                                                                            <a href="javascript:void(0)" onclick="verifyMember()">Send</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="h_btn s_btn">
                                                                    <button class="btn btn-primary" type="submit" id="submit-button">Use <span id="credit"></span> hours
                                                                        Credit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
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
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        let removeCouponDiscountAmt = '';
        let removeCouponType = '';
        //Get Phone List from Tickets
        $(document).ready(function() {
            var baseUrl = "{{ URL::to('/') }}";
            $('#phone').keyup(function() {
                var search = $(this).val();
                if (search.length > 0) {
                    $('#result').css("display", "block");
                    $.ajax({
                        type: 'Post',
                        url: baseUrl + '/get-ticket-phone-list',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            query: search
                        },
                        success: function(data) {
                            $('#nameOfKid').val('');
                            $('#kidsQty').val('');
                            $('#parent-div').empty();
                            $('#result').html(data);
                        }
                    });
                } else {
                    $('#result').css("display", "none");
                }
            });
        });

        function fetchuserDetail(user) {
            if (user != "") {
                $.ajax({
                    type: 'Post',
                    url: baseUrl + '/get-user-detail',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        query: user
                    },
                    success: function(data) {
                        var arr = JSON.parse(data);
                        $("#nameOfKid").val(arr.name);
                        $("#phone").val(arr.phone_no);
                        $("#dob").val(arr.dob);
                        $("#kidsQty").val(1);

                        $('#parent-div').empty();
                        var i = 1;
                        var childDiv =
                            '<div class="row child-div"><div class="col-md-4"><div class="mb-4 input_wrap dark"><label>Kid Name <span>*</span></label><input type="text" class="form-control" placeholder="Kid Name " value="' +
                            arr.kiddetail + '" name="kidName[]" id ="kidName' + i +
                            '" required><input type="hidden" class="kidPrice' + i +
                            '" name="kidPrice[]"></div></div> <div class="col-md-4"><div class="mb-4 input_wrap dark"><label>Price</label><input type="number" class="form-control kid_price" placeholder="Price" name="kid_price[]" id="kid_price_' +
                            i +
                            '" onkeyup="calculate()" readonly></div></div><div class="col-md-4 "><div class="mb-4 input_wrap"> <label>Hours <span>*</span></label><select onchange="calculateTotal()" name="hours[]" id="kid_hrs' +
                            i +
                            '" class="form-select hours" required><option value="">0</option><option value="1">1</option><option value="2">2</option><option value="Unlimited">Unlimited</option></select></div></div></div> ';
                        $("#parent-div").append(childDiv);
                        $('#result').css("display", "none");

                    }
                });
            }
        }
    </script>
    <script>
        //Number of Kids Options
        $("#kidsQty").on("change", function() {
            var numDivs = $(this).val(); // Get the selected value
            $('#parent-div').empty();
            document.getElementById('gstOn').innerHTML = "Includes Gst @18% ";
            document.getElementById('gstValue').innerHTML = '';
            document.getElementById('totalAmount').innerHTML = '';
            document.getElementById('couponCodeShow').innerHTML = 'Coupon';
            document.getElementById('couponValueShow').innerHTML = '';
            document.getElementById("couponCode").value = '';
            document.getElementById("couponValue").value = '';
            $('#couponmsg').removeAttr('style').text('');
            $('#couponCodebutton').prop('disabled', false);
            $('#subtotal').text("");

            if (!numDivs) {
                $('#subtotal').text("");
                $('#subtotlSave').val('0');
                $("#chargeAmount").val('0');
                document.getElementById('gstOn').innerHTML = "Includes Gst @18%";
                document.getElementById('gstValue').innerHTML = '0';
                document.getElementById('totalAmount').innerHTML = '₹ 0';
                $('#valueGst').text("₹ 0");
                $('#gst18').val('0');
                $('#dataRow').empty();
                $('#extra-time').css("display", "block");
                $('#socks').css("display", "block");
                $('#adult').css("display", "block");
            }
            for (var i = 1; i <= numDivs; i++) {
                var childDiv =
                    '<div class="row child-div"><div class="col-md-4"><div class="mb-4 input_wrap dark"><label>Kid Name <span>*</span></label><input type="text" class="form-control" placeholder="Kid Name " name="kidName[]" id ="kidName' +
                    i +
                    '" required></div></div> <div class="col-md-4"><div class="mb-4 input_wrap dark"><label>Price</label><input type="number" class="form-control kid_price" placeholder="Price" name="kid_price[]" id="kid_price_' +
                    i +
                    '" readonly></div></div><div class="col-md-4 "><div class="mb-4 input_wrap"> <label>Hours <span>*</span></label><select name="hours[]" id="kid_hrs' +
                    i +
                    '" class="form-select hours" required><option value="">0</option><option value="1">1</option><option value="2">2</option><option value="Unlimited">Unlimited</option></select></div></div></div>';
                $("#parent-div").append(childDiv);
            }
        });
    </script>

    <script>
        //Kids Hours Options
        $(document).on('change', 'select[id^="kid_hrs"]', function() {
            var selectBoxId = $(this);
            var inputTag = selectBoxId.closest('.child-div').find('.kid_price');
            // Make the AJAX call to fetch data from the server
            var baseUrl = "{{ URL::to('/') }}";
            $.ajax({
                url: baseUrl + '/get-price-kids',
                type: 'GET',
                data: {
                    option: $(this).val(),
                    branch_id: $('#hiddenBranchId').val(),
                },
                success: function(data) {
                    // Update the input tags with the received data
                    inputTag.val(data);
                    var sum = 0;
                    $('.kid_price').each(function() {
                        var val = parseFloat($(this).val()) || 0;
                        sum += val;
                    });
                    var gstDeductedFromKidPrice = (sum / 1.18); // 762.71                    
                    var totalDeductGst = sum - gstDeductedFromKidPrice; //137.28                    
                    $('#subtotal').text("₹ " + gstDeductedFromKidPrice.toFixed(2));
                    $('#subtotlSave').val(gstDeductedFromKidPrice.toFixed(2));
                    document.getElementById('gstOn').innerHTML = "Includes Gst @18% ";
                    $('#gst18On').val(sum.toFixed(2));
                    document.getElementById('gstValue').innerHTML = '' + totalDeductGst.toFixed(2);
                    let finalAmount = sum;
                    document.getElementById('totalAmount').innerHTML = '₹ ' + finalAmount.toFixed(2);
                    $("#chargeAmount").val(finalAmount.toFixed(2));
                    $('#gst18').val(totalDeductGst.toFixed(2));
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>

    <script>
        function AddItems(event, name, price, tax) {
            var table = document.querySelector('#dataRow');
            var newRow = document.createElement("tr");
            var cell1 = document.createElement("td");

            data =
                `
                <input type="hidden" value="${price}" class="product_price">
<div class="row mb-3">
    <input type="hidden" value="${name}" name="product_name[]">
    <div class="col-md-3">
        <div class=" input_wrap">
            <label>${name} Qty</label>
            <select name="product_quantity[]" onchange="calculateTotal()" class="form-select">
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input_wrap dark">
            <label>Price</label>
            <input type="number" class="form-control" placeholder="Price" name="price[]" readonly>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input_wrap dark">
            <label>Tax</label>
            <select name="tax[]" class="form-select">
                <option value="${tax}">${tax}%</option>
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="input_wrap dark">
            <label>Total</label>
            <input type="text" value="${price}" class="final_price form-control">
        </div>
    </div>
</div>

                             `;
            cell1.innerHTML = data;
            newRow.appendChild(cell1);
            table.appendChild(newRow);
            event.target.style.display = 'none';
        }

        function calculateTotal() {
            var sum = 0;
            var totalPrice = 0;
            var taxAmount18 = 0;
            var taxAmount12 = 0;
            var taxAmount5 = 0;
            var finalPriceEach = [];
            $('.kid_price').each(function() {
                var val = parseFloat($(this).val()) || 0;
                sum += val;
            });
            select1Value = [];
            $('select[name="product_quantity[]"]').each(function() {
                var val = parseFloat($(this).val()) || 0;
                select1Value.push(val);
            });
            adult_price = [];
            $('.product_price').each(function() {
                var val = parseFloat($(this).val()) || 0;
                adult_price.push(val);
            });
            var itemPrice = [];

            $('input[name="price[]"]').each(function(index) {
                var finalAdultPrice = parseFloat(select1Value[index]) * parseFloat(adult_price[index]);
                finalPriceEach.push(finalAdultPrice);
                $(this).val(finalAdultPrice);
            });
            let tax = [];
            $('select[name="tax[]"]').each(function(index) {
                var val = parseFloat($(this).val()) || 0;
                tax.push(val);
            });
            $('input[name="kid_price[]"]').each(function(index) {
                var val = parseFloat($(this).val()) || 0;
                var bAmt = val / 1.18;
                totalPrice += bAmt;
                var taxAmount = (val - bAmt);
                taxAmount18 += taxAmount;
            });
            const taxRatesList = {
                '18': 1.18,
                '12': 1.12,
                '5': 1.05,
                '0': 1.00
            };
            $('.final_price').each(function(index) {
                const rate = tax[index];
                const finalPrice = finalPriceEach[index];
                const divisor = taxRatesList[rate];
                const basicAmount = finalPrice / divisor;
                totalPrice += basicAmount;
                const taxAmount = finalPrice - basicAmount;

                switch (rate) {
                    case 18:
                        taxAmount18 += taxAmount;
                        break;
                    case 12:
                        taxAmount12 += taxAmount;
                        break;
                    case 5:
                        taxAmount5 += taxAmount;
                        break;
                }
                $(this).val((finalPrice + taxAmount).toFixed(2));
            });
            let subTotalPrice = 0;
            subTotalPrice = totalPrice + taxAmount18 + taxAmount12 + taxAmount5;
            $('#subtotal').text("₹ " + totalPrice.toFixed(2));
            $('input[name="subTotal"]').val(totalPrice.toFixed(2));
            $('input[name="totalAmount"]').val(subTotalPrice.toFixed(2));
            $('#totalAmount').text("₹ " + subTotalPrice.toFixed(2));

            let priceWith18Gst = 0;
            tax.forEach((value, index) => {
                if (value === 18) {
                    priceWith18Gst += adult_price[index] * select1Value[index];
                }
            });
            let gstOnPrice = sum + priceWith18Gst;
            document.getElementById('gstOn').innerHTML = "Includes Gst @18% ";

            $('#gst18On').val(gstOnPrice.toFixed(2));
            $('#gstValue').text("₹ " + taxAmount18.toFixed(2));
            $('input[name="gst18"]').val(taxAmount18.toFixed(2));
            $('#valueGst').text("₹ " + taxAmount5.toFixed(2));
            $('input[name="gst5"]').val(taxAmount5.toFixed(2));

            if (taxAmount12 > 0) {
                document.querySelector('#gst12Container').classList.remove('d-none');
            } else {
                document.querySelector('#gst12Container').classList.add('d-none');
            }
            $('#gst12PercentVal').text("₹ " + taxAmount12.toFixed(2));
            $('input[name="gst12"]').val(taxAmount12.toFixed(2));
            var totalwithTax = (totalPrice + taxAmount18 + taxAmount5 + taxAmount12).toFixed(2);
            var totalwithOutTax = totalPrice.toFixed(2);
            // $('#totalAmount').text("₹ EXF" + subTotalPrice);
            // $('input[name="totalAmount"]').val(subTotalPrice);
            // $('input[name="subTotal"]').text(totalPrice.toFixed(2));
        }
    </script>

    <script>
        $("#couponCodebutton").on("click", function() {
            var branchId = $("#hiddenBranchId").val();
            $.ajax({
                url: baseUrl + '/get-coupon',
                type: 'GET',
                data: {
                    option: $("#couponCode").val(),
                    branch: branchId
                },
                success: function(data) {
                    let sum = $("#chargeAmount").val();                    
                    let sumamount = 0;
                    if (data.status === 400) {
                        $('#couponmsg').css('color', 'red').text(data.msg);
                        return false;
                    }
                    if (!sum) {
                        toastr.error("Please select Number of kids & Hours");
                        return false;
                    }
                    if (data != '') {
                        let discount = removeCouponDiscountAmt = data.discount;
                        removeCouponType = data.discount_type;
                        $('#remove-coupon').removeClass('disabled');
                        $('#couponCodebutton').addClass('disabled');
                        $('#couponmsg').css('color', '#0A5F59').text('Apply Coupon Successful!');

                        // GST deduction
                        let subTotalLessGST = parseFloat(sum);                        

                        ["#gst5", "#gst18", "#gst12"].forEach(id => {
                            let gst = parseFloat($(id).val()) || 0;                           
                            subTotalLessGST -= gst;
                        });                        

                        // $('#subtotal').text("₹ " + subTotalLessGST.toFixed(2));
                        // $('#subtotlSave').val(subTotalLessGST.toFixed(2));

                        let gst18Ded = parseFloat($('#gst18').val()) || 0;
                        let gst18OnVal = parseFloat($('#gst18On').val()) || 0;
                        document.getElementById('gstOn').innerHTML = "Includes Gst @18% ";
                        document.getElementById('gstValue').innerHTML = gst18Ded.toFixed(2);

                        // coupon discount deduction AFTER GST deducated
                        let discountAmt = 0;
                        if (data.discount_type == 'flat') {
                            discountAmt = discount;
                        } else if (data.discount_type == 'percent') {
                            discountAmt = (subTotalLessGST.toFixed(2) * discount) / 100;
                        }
                        $('#couponValue').val(discountAmt.toFixed(2));
                        $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                        $('#couponValueShow').html(discountAmt.toFixed(2));
                        sum = subTotalLessGST - discountAmt.toFixed(2);

                        $('#subtotal').text("₹ " + sum.toFixed(2));
                        $('#subtotlSave').val(sum.toFixed(2));

                        //update GST                        
                        let newGst = (sum * 18) / 100;
                        $('#gst18').val(newGst.toFixed(2));
                        document.getElementById('gstValue').innerHTML = newGst.toFixed(2);

                        ["#gst5", "#gst18", "#gst12"].forEach(id => {
                            let reGst = parseFloat($(id).val()) || 0;
                            sum += reGst;
                        });
                        document.getElementById('totalAmount').innerHTML = '₹ ' + sum.toFixed(2);
                        $("#chargeAmount").val(sum.toFixed(2));
                    } else {
                        // Get the value of the first select box
                        var select1Value = parseInt($('#adult_qty')
                            .val());
                        var adult_price = document.getElementById(
                            'adult_price_backend').value;
                        var finalAdultPrice = parseFloat(adult_price) *
                            parseFloat(select1Value);
                        // Get the value of the second select box
                        var select2Value = parseInt($('#socks_qty')
                            .val());
                        var socks_price = document.getElementById(
                            'socks_price_backend').value;
                        var finalSocksPrice = parseFloat(socks_price) *
                            parseFloat(select2Value);
                        $("#socks_price").val(finalSocksPrice.toFixed(
                            2));
                        // Add the values of the select boxes to the sum
                        if (isNaN(finalAdultPrice)) {
                            var taxSum = sumamount + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sumamount += finalAdultPrice;
                            $("#adult_price").val(finalAdultPrice
                                .toFixed(2));
                        }
                        $('#subtotal').text("₹ " + sum.toFixed(2));
                        $('#subtotlSave').val(sum.toFixed(2));
                        document.getElementById('gstOn').innerHTML = "Includes Gst @18% ";
                        var gstKid = (sum * 18) / 100;
                        document.getElementById('gstValue').innerHTML = '' + gstKid.toFixed(2);
                        var finalAmount = sum + gstKid;
                        if (!isNaN(finalSocksPrice)) {
                            var gstSocks = (socks_price * 5) / 100;
                            finalAmount += gstSocks;
                        }
                        document.getElementById('totalAmount')
                            .innerHTML = '₹ ' + finalAmount.toFixed(2);
                        $("#chargeAmount").val(finalAmount.toFixed(2));
                        $('#gst18').val(gstKid.toFixed(2));
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>
    <script>
        //Coupon Code remove
        $("#remove-coupon").on("click", function() {
            const sum = $("#chargeAmount").val();
            console.log('sum : ', sum);
            const branchId = $("#hiddenBranchId").val();
            let revertedSumAmt = 0;

            if (removeCouponDiscountAmt !== '' || removeCouponType !== '') {
                $('#couponCodeShow').html("Coupon");
                $('#couponCodebutton').removeClass('disabled');
                $('#remove-coupon').addClass('disabled');
                $('#couponmsg').removeAttr('style').text('');
                $('#couponValue').val('');
                $('#couponCode').val('');
                $('#couponValueShow').text('');
                if (removeCouponType == 'flat') {
                    revertedSumAmt = parseFloat(sum) + parseFloat(removeCouponDiscountAmt);
                } else if (removeCouponType == 'percent') {
                    revertedSumAmt = parseFloat(sum) / (1 - (parseFloat(removeCouponDiscountAmt) / 100));
                } else {
                    return false;
                }
                revertedSumAmt = parseFloat(revertedSumAmt);
                $('#totalAmount').text('₹ ' + revertedSumAmt.toFixed(2));
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            var baseUrl = "{{ URL::to('/') }}";
            let errorShown = false;
            $('#mobile_no').keyup(function() {
                var search = $(this).val();
                if (search.length > 0) {
                    $('#resultmembership').css("display", "block");
                    $.ajax({
                        type: 'Post',
                        url: baseUrl + '/get-membership-phone-list',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            query: search
                        },

                        success: function(data) {
                            if (data) {
                                $('#submit-button').prop('disabled', false);
                                $('#resultmembership').html(data);
                                errorShown = false;
                            } else {
                                $('#submit-button').prop('disabled', true);
                                if (!errorShown) {
                                    toastr.error('Member User Not Found.');
                                    errorShown = true;
                                }
                            }

                        }
                    });
                } else {
                    $('#resultmembership').css("display", "none");
                    errorShown = false;
                }

            });
        });
    </script>

    <script>
        // Membership calculatePlan
        function calculatePlan(user) {
            if (user != "") {
                $.ajax({

                    type: 'GET',
                    url: baseUrl + '/get-plan',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        mobile: user
                    },
                    success: function(response) {
                        if (response && response.user) {
                            $('#submit-button').prop('disabled', false);
                            document.getElementById('nameCustomer').innerHTML = "" + response.user.name;
                            if (response.renewal_date) {
                                document.getElementById('planDate').innerHTML = "" + response.renewal_date;
                            } else {
                                document.getElementById('planDate').innerHTML = "";
                            }

                            document.getElementById("mobile_no").value = response.user_details.phone_no;
                            document.getElementById("credit_left").value = response.credit_left;
                            document.getElementById("kidsName2").value = response.user.name;
                            var hrs = document.getElementById('hrs').value;


                            document.getElementById("user_id").value = response.user.id;
                            document.getElementById("credit").value = hrs;
                            $('#resultmembership').css("display", "none");
                        } else {
                            $('#submit-button').prop('disabled', true);
                            toastr.error('No membership found for this user.');
                        }

                    }
                });
            }
        }



        $(document).on('change', '#hrs', function() {
            var hrs = document.getElementById('hrs').value;
            var numberKids = document.getElementById('numberKids').value;

            $("#credit").text(numberKids * hrs);
        });

        $(document).on('change', '#numberKids', function() {
            var hrs = document.getElementById('hrs').value;
            var numberKids = document.getElementById('numberKids').value;

            $("#credit").text(numberKids * hrs);
        });
    </script>

    <script>
        //Otp Verification for Membership
        var baseUrl = "{{ URL::to('/') }}";

        function verifyMember() {

            var mobile = document.getElementById('mobile_no').value;

            var user_id = document.getElementById('user_id').value;

            if (mobile != "") {
                $.ajax({
                    type: 'Post',
                    url: baseUrl + '/send-otp-membership',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        mobile: mobile,
                        user_id: user_id,
                    },
                    success: function(response) {

                        toastr.success('send OTP Successfully!');

                    },
                    error: function(xhr, status, error) {

                    }

                });
            } else {
                toastr.error('Mobile number can’t be empty');
            }

        }
    </script>


    <script>
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

        //Validation on date and Time
        dateInput.addEventListener('input', function() {
            validateDateTime();
        });

        timeInput.addEventListener('input', function() {
            validateDateTime();
        });

        function validateDateTime() {
            var date = new Date(dateInput.value);
            var time = timeInput.value.split(':');
            var hour = parseInt(time[0]);
            var minute = parseInt(time[1]);
            var now1 = new Date();
            var datetime = new Date(date.getFullYear(), date.getMonth(), date.getDate(), hour, minute);
            if (datetime < now1) {
                alert('Please select current or future date and time');

                dateInput.value = today;
                timeInput.value = timeString;
            }
        }
        $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif (Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });

        const button = document.getElementById('submit-button');

        // Add an event listener to the button click event
        button.addEventListener('click', (event) => {
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the form data
            const form = document.getElementById('my-form');
            const credit = form.querySelector('[name="credit_left"]').value;

            const hrs = form.querySelector('[name="hrs"]').value;

            // Validate the form data
            if (parseInt(credit) < parseInt(hrs)) {
                alert('Please renewal your membership.');
                return;
            }
            // Submit the form if the data is valid
            form.submit();
        });
    </script>
</body>

</html>
