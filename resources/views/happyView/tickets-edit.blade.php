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
                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                    <form method="GET" action="{{ route('tickets') }}">
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Branch <span>*</span></label>
                                                                <select name="branch_id" id="branchId" class="form-select" onchange="this.form.submit()" disabled>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch['id'] }}" {{ $branch['id'] == $user['branch_id'] ? 'selected' : '' }}>{{ $branch['name'] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif
                                                <form action="{{ url('/update-tickets') }}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                            <input type="hidden" name="branch_id" value="{{ $user['branch_id'] }}" id="hiddenBranchId">
                                                        @endif
                                                        <input type="hidden" value="{{ $user->id }}" name="id">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Date</label>
                                                                <input type="date" class="form-control" placeholder="date" name="date" id="date" value="{{ isset($user['event']->booking_date) ? $user['event']->booking_date : '' }}" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 input_wrap dark time">
                                                            <div class="mb-4">
                                                                <label>Time</label>
                                                                <input type="time" class="form-control" placeholder="Time" name="time" id="time" value="{{ isset($user['event']->Hours) ? $user['event']->Hours : '' }}" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control" placeholder="Phone" name="phone" id="phone" pattern="[0-9]{10}" value="{{ isset($user['userDetails']->phone_no) ? $user['userDetails']->phone_no : '' }}" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Guardian Name <span>*</span></label>
                                                                <input type="text" class="form-control" placeholder="Name of Kid" name="name_of_kid" id="nameOfKid" value="{{ isset($user->name) ? $user->name : '' }}" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Number of kids <span>*</span></label>
                                                                <select name="kids" id="kidsQty" class="form-select" required readonly>
                                                                    <option value="" selected disabled>
                                                                        {{ isset($user['userDetails']->number_of_kids) ? $user['userDetails']->number_of_kids : '' }}
                                                                    </option>
                                                                    <input type='hidden' name='kids_value' value='{{ isset($user['userDetails']->number_of_kids) ? $user['userDetails']->number_of_kids : '' }}'>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Kid DOB <span>*</span></label>
                                                                <input type="date" class="form-control" placeholder="DOB" name="dob" id="dob" value="{{ isset($user['userDetails']->dob) ? $user['userDetails']->dob : '' }}" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" id="parent-div">
                                                            @if ($kid)
                                                                @foreach ($kid as $i => $value)
                                                                    <input type="hidden" name="kidids[]" value="{{ $value['id'] }}">
                                                                    <div class="row child-div">
                                                                        <div class="col-md-3">
                                                                            <div class="mb-4 input_wrap dark">
                                                                                <label>Kid Name <span>*</span></label>
                                                                                <input type="text" class="form-control" name="kidName[]" value="{{ $value['name'] }}" readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="mb-3 input_wrap dark">
                                                                                <label>Price</label>
                                                                                <input type="number" class="form-control" id="k_price_{{ $i }}" placeholder="Price" name="kid_price[]" value="{{ $value['kidPrice'] }}" readonly>
                                                                                <input type="hidden" id="kh_price_{{ $i }}" name="old_price[]" value="{{ $value['kidPrice'] }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 ">
                                                                            <div class="mb-4 input_wrap dark">
                                                                                <label>Hours <span>*</span></label>
                                                                                <input type="text" class="form-control hours" id="k_hour_{{ $i }}" name="hours[]" value="{{ $value['hours'] }}" readonly>
                                                                                <input type="hidden" name="old_hour[]" id="kh_hour_{{ $i }}" value="{{ $value['hours'] }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="mb-3 input_wrap dark">
                                                                                <label>Extra Time</label>
                                                                                @php
                                                                                    $bookingDate = $user['event']->booking_date;
                                                                                    $now = new DateTime();
                                                                                    $isPastDate = $bookingDate < $now->format('Y-m-d');
                                                                                @endphp
                                                                                <select name="extra_time_select[]" class="form-select" id="time_{{ $i }}" onchange="AddExtratime(event)" {{ $isPastDate ? 'disabled' : '' }}>
                                                                                    <option value="">Select Here </option>
                                                                                    <option value="30">30 Min </option>
                                                                                    <option value="60">60 Min </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <table id="dataRow">
                                                            @if ($product)
                                                                @foreach ($product as $socks_products)
                                                                    @if ($socks_products->qty == $user['userDetails']->number_of_kids && strtolower($socks_products->product_name) == 'socks')
                                                                        @continue
                                                                    @elseif(strtolower($socks_products->product_name) == 'socks')
                                                                        @php
                                                                            $socks_products->qty -= $user['userDetails']->number_of_kids;
                                                                        @endphp
                                                                    @endif
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" value="{{ getProductPrice($socks_products->product_name, $user['branch_id']) }}" class="product_price">
                                                                            <div class="row mb-3">
                                                                                <input type="hidden" name="extra_product_id[]" value="{{ $socks_products->id }}" class="extra_product_id">
                                                                                <div class="row mb-3">
                                                                                    <input type="hidden" value="{{ $socks_products->product_name }}" name="product_name[]">
                                                                                    <div class="col-md-3">
                                                                                        <div class=" input_wrap">
                                                                                            <label>{{ $socks_products->product_name }} Qty </label>
                                                                                            <input type="text" class="form-control" name="product_quantity[]" value="{{ isset($socks_products->qty) ? $socks_products->qty : '' }}" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Price</label>
                                                                                            <input type="number" class="form-control" placeholder="Price" value="{{ $socks_products->price }}" name="price[]" readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Tax</label>
                                                                                            @php
                                                                                                $productTax = getTax($socks_products->product_name);
                                                                                            @endphp
                                                                                            <select name="tax[]" class="form-select" disabled>
                                                                                                <option value="{{ $productTax }}">
                                                                                                    {{ $productTax }}%
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Total</label>
                                                                                            @php
                                                                                                $productTax = getTax($socks_products->product_name);
                                                                                            @endphp
                                                                                            <input type="text" value="{{ getAddedItemTotal($productTax, $socks_products->qty, $socks_products->price) }}" class="final_price form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>
                                                        <div class="button_wrap">
                                                            @foreach ($productData as $pro)
                                                                @if (!hasTwoProduct(request()->route('id'), $pro->product_name, $user['userDetails']->number_of_kids))
                                                                    <div class="h_btn">
                                                                        <a href="javascript:void(0)" class="more_adult" @disabled(true) style="pointer-events: none;">+
                                                                            {{ $pro->product_name }}</a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                        <hr>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Payment Mode</label>
                                                                <select name="payment_mode" id="services" class="form-select">
                                                                    @if (isset($paymentMethod->payment_method) && $paymentMethod->payment_method != '')
                                                                        @php
                                                                            $methods = [
                                                                                2 => 'Cash',
                                                                                1 => 'UPI',
                                                                                3 => 'Debit/Credit',
                                                                            ];
                                                                        @endphp
                                                                        @foreach ($methods as $key => $label)
                                                                            <option value="{{ $key }}" {{ $paymentMethod->payment_method == $label ? 'selected' : '' }}>{{ $label }}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="2">Cash</option>
                                                                        <option value="1">UPI</option>
                                                                        <option value="3">Debit/Credit </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>COUPON CODE</label>
                                                                <div class="d-flex">
                                                                    <input type="text" name="couponcode" class="form-control" id="couponCode" placeholder="Any Discount Code" value="{{ $paymentMethod->couponcode }}">
                                                                    <input type="hidden" name="couponvalue" id="couponValue" value="{{ $paymentMethod->couponvalue }}">
                                                                    <button type="button" id="couponCodebutton" class="btn btn-primary" disabled>Apply</button>
                                                                </diV>
                                                                <p id="couponmsg"></p>
                                                            </div>
                                                        </div>
                                                        <div class="total_wrap">
                                                            <div class="subtotal">
                                                                <p>Subtotal</p>
                                                                <p id="subtotal">{{ $paymentMethod->subTotal }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="couponCodeShow">Coupon
                                                                    ({{ $paymentMethod->couponcode }})</span></p>
                                                                <p id="couponValueShow">
                                                                    {{ $paymentMethod->couponvalue }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst gst9" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst18Label">CGST @ 9%</p>
                                                                    <p id="cgst18Total">{{ round($paymentMethod->gst18 / 2, 2) }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst18Label">SGST @ 9%</p>
                                                                    <p id="sgst18Total">{{ round($paymentMethod->gst18 / 2, 2) }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="gst gst12 {{ $paymentMethod->gst12 > 0 ? '' : 'd-none' }}" id="gst12Container" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst12Label">CGST @ 6%</p>
                                                                    <p id="cgst12Total">{{ round($paymentMethod->gst12 / 2, 2) }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst12Label">SGST @ 6%</p>
                                                                    <p id="sgst12Total">{{ round($paymentMethod->gst12 / 2, 2) }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="gst gst25" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst5Label">CGST @ 2.5%</p>
                                                                    <p id="cgst5Total">{{ isset($paymentMethod->gst5) ? round($paymentMethod->gst5 / 2, 2) : '' }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst5Label">SGST @ 2.5%</p>
                                                                    <p id="sgst5Total">{{ isset($paymentMethod->gst5) ? round($paymentMethod->gst5 / 2, 2) : '' }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="amount">
                                                                <p>Total Amount </p>
                                                                <p id="totalAmount">{{ $paymentMethod->amounts }}</p>
                                                                <input type="hidden" id="newTotalAmount" name="newTotalAmount" value={{ $paymentMethod->amounts }}>
                                                                <input type="hidden" id="oldTotalAmt" value={{ $paymentMethod->amounts }}>

                                                                <input type="hidden" id="newSubAmt" name="newSubAmt" value={{ $paymentMethod->subTotal }}>
                                                                <input type="hidden" id="oldSubAmt" value={{ $paymentMethod->subTotal }}>
                                                            </div>
                                                            <hr>
                                                            <div class="tearm_condition">
                                                                <input type="checkbox" id="" name="" value="" checked="checked">
                                                                <p>By Agreeing to the <b>Terms and Condition</b> of
                                                                    HappyOn</p>
                                                            </div>
                                                            <div class="h_btn s_btn">
                                                                <button class="btn btn-primary" href="#">Update
                                                                    Payment & Timer</button>
                                                            </div>
                                                        </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div id="free" class="container tab-pane fade" role="tabpanel"><br>
                                            <form action="{{ url('/update-membership') }}" method="post" id="my-form">
                                                @csrf
                                                <div class="col-md-12">
                                                    <div class="mb-4 input_wrap">
                                                        <label>Phone Number</label>
                                                        <input type="text" class="form-control" placeholder="+91 0123456789" onkeyup="calculatePlan()" id="mobile_no" maxlength="10">
                                                        <div id="resultmembership" class="shadow phone-list">
                                                        </div>
                                                        <div class="happy_card">
                                                            <div class="card_header">
                                                                <div class="card_logo">
                                                                    <img src="{{ asset('assets/images/happyon_logo.png') }}" alt="logo">
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
                                                                    <img src="{{ asset('assets/images/Mastercard.png') }}" alt="mastercard">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Credit Left</label>
                                                                <input type="text" class="form-control" placeholder="22 hrs" id="credit_left" name="credit_left" readonly>
                                                                <input type="hidden" id="user_id" name="user_id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Kids Name</label>
                                                                <input type="text" class="form-control" placeholder="Kids Name" id="kidsName2" name="kidsName2" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Number of Kids</label>
                                                                <select name="number_of_kids" id="numberKids" class="form-select">
                                                                    @for ($i = 1; $i <= 10; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Number of Hrs</label>
                                                                <select name="hrs" id="hrs" class="form-select">
                                                                    @for ($i = 1; $i <= 10; $i++)
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="input_button">
                                                                <div class="mb-4 input_wrap">
                                                                    <label>Credit Left</label>
                                                                    <input type="text" class="form-control" placeholder="Enter OTP" required>
                                                                    <div class="h_btn">
                                                                        <a href="#" onclick="verifyMember()">Send</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="h_btn s_btn">
                                                                <button class="btn btn-primary" type="submit" id="submit-button">Use <span id="credit"></span>
                                                                    hours Credit</button>
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
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script> <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script>
        function AddExtratime(event) {
            const extraTimeDDown = event.target;
            const extraTimeValue = extraTimeDDown.value;
            const addedPrice = extraTimeValue == 30 ? 250 : 400;
            const addedHour = extraTimeValue == 30 ? 0.5 : 1;

            const selectId = extraTimeDDown.id;
            const kidIndex = parseInt(selectId.split("_")[1]);

            let inputId = `k_price_${kidIndex}`;
            let inputIdHour = `k_hour_${kidIndex}`;

            const targetHourElement = document.getElementById(inputIdHour);
            const hiddenHourElement = document.getElementById(`kh_hour_${kidIndex}`);

            if (hiddenHourElement.value == 'Unlimited') {
                alert('Extra Time Not Allowed on unlimited Hours');
                return false;
            }
            const targetPriceElement = document.getElementById(inputId);
            const hiddenPriceElement = document.getElementById(`kh_price_${kidIndex}`);

            if (extraTimeValue > 0) {
                targetHourElement.value = parseFloat(hiddenHourElement.value) + addedHour + " hours";
                targetPriceElement.value = parseFloat(hiddenPriceElement.value) + addedPrice;
            } else {
                targetPriceElement.value = parseFloat(hiddenPriceElement.value);
                targetHourElement.value = parseFloat(hiddenHourElement.value);
            }
            calculateTotalAmount();
        }

        function calculateTotalAmount() {
            const totalAmtElement = document.getElementById("totalAmount");
            const newTotalAmount = document.getElementById("newTotalAmount");
            const hiddenTotalAmtElement = document.getElementById("oldTotalAmt");

            const subAmtElement = document.getElementById("subtotal");
            const newSubAmount = document.getElementById("newSubAmt");
            const hiddenSubAmtElement = document.getElementById("oldSubAmt");

            let hiddenOldPriceAmt = 0;
            $('input[name="old_price[]"]').each(function() {
                let val = parseFloat($(this).val()) || 0;
                hiddenOldPriceAmt += val;
            });
            let priceAmt = 0;
            $('input[name="kid_price[]"]').each(function() {
                let valP = parseFloat($(this).val()) || 0;
                priceAmt += valP;
            });
            let differenceAmt = 0;
            if (hiddenOldPriceAmt !== priceAmt) {
                differenceAmt = priceAmt - hiddenOldPriceAmt;
            }
            let newTotal = parseFloat(hiddenTotalAmtElement.value) + differenceAmt;
            newTotalAmount.value = newTotal;
            totalAmtElement.innerHTML = newTotal;

            let newSubA = parseFloat(hiddenSubAmtElement.value) + differenceAmt;
            newSubAmount.value = newSubA;
            subAmtElement.innerHTML = newSubA;
        }
    </script>

    <script>
        //Auto Fetch Membership Program
        $(document).ready(function() {
            var baseUrl = "{{ URL::to('/') }}";
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
                            } else {
                                $('#submit-button').prop('disabled', true);
                                toastr.error('Member User Not Found.');
                            }
                        }
                    });
                } else {
                    $('#resultmembership').css("display", "none");
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
                    error: function(xhr, status, error) {}
                });
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

        const button = document.getElementById('submit-button');
        // Add an event listener to the button click event
        button.addEventListener('click', (event) => {
            event.preventDefault();
            const form = document.getElementById('my-form');
            const credit = form.querySelector('[name="credit_left"]').value;

            const hrs = form.querySelector('[name="hrs"]').value;
            // Validate the form data
            if (parseFloat(credit) < parseFloat(hrs)) {
                alert('Please renewal your membership.');
                return;
            }
            form.submit();
        });
    </script>
</body>

</html>
