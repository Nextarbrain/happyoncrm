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
                                                    <a class="nav-link active left" data-bs-toggle="tab" href="#paid"
                                                        aria-selected="true" role="tab">
                                                        <p class="heading">Customer </p>
                                                    </a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link right" data-bs-toggle="tab" href="#free"
                                                        aria-selected="false" tabindex="-1" role="tab">
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
                                                <form action="{{ url('/update-tickets') }}" method="post">
                                                    @csrf
                                                    <div class="row">
                                                        <input type="hidden" value="{{ $user->id }}"
                                                            name="id">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Date</label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="date" name="date" id="date"
                                                                    value="{{ isset($user['event']->booking_date) ? $user['event']->booking_date : '' }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 input_wrap dark time">
                                                            <div class="mb-4">
                                                                <label>Time</label>
                                                                <input type="time" class="form-control"
                                                                    placeholder="Time" name="time" id="time"
                                                                    value="{{ isset($user['event']->Hours) ? $user['event']->Hours : '' }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Guardian Name <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Name of Kid" name="name_of_kid"
                                                                    id="nameOfKid"
                                                                    value="{{ isset($user->name) ? $user->name : '' }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Phone" name="phone" id="phone"
                                                                    pattern="[0-9]{10}"
                                                                    value="{{ isset($user['userDetails']->phone_no) ? $user['userDetails']->phone_no : '' }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Number of kids <span>*</span></label>
                                                                <select name="kids" id="kidsQty"
                                                                    class="form-select" required readonly>
                                                                    <option value="" selected disabled>
                                                                        {{ isset($user['userDetails']->number_of_kids) ? $user['userDetails']->number_of_kids : '' }}
                                                                    </option>
                                                                    <input type='hidden' name='kids_value'
                                                                        value='{{ isset($user['userDetails']->number_of_kids) ? $user['userDetails']->number_of_kids : '' }}'>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Kid DOB <span>*</span></label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="DOB" name="dob" id="dob"
                                                                    value="{{ isset($user['userDetails']->dob) ? $user['userDetails']->dob : '' }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-12" id="parent-div">
                                                            @if ($kid)
                                                                @foreach ($kid as $value)
                                                                    <input type="hidden" name="kidids[]"
                                                                        value="{{ $value->id }}">
                                                                    <div class="row child-div">
                                                                        <div class="col-md-3">
                                                                            <div class="mb-4 input_wrap dark">
                                                                                <label>Kid Name <span>*</span></label>
                                                                                <input type="text"
                                                                                    class="form-control"
                                                                                    name="kidName[]"
                                                                                    value="{{ $value->name }}"
                                                                                    readonly>

                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="mb-3 input_wrap dark">
                                                                                <label>Price</label>
                                                                                <input type="number"
                                                                                    class="form-control kid_price"
                                                                                    placeholder="Price"
                                                                                    name="kid_price[]"
                                                                                    value="{{ isset($value->kidPrice) ? $value->kidPrice : '' }}"
                                                                                    readonly>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 ">
                                                                            <div class="mb-4 input_wrap">
                                                                                <label>Hours <span>*</span></label>
                                                                                <select name="hours[]"
                                                                                    id="kid_hrs'+i+'"
                                                                                    class="form-select hours">
                                                                                    @if ($value->hours == 1)
                                                                                        <option value="1"
                                                                                            selected>1</option>
                                                                                        <option value="2">2
                                                                                        </option>
                                                                                        <option value="Unlimited">
                                                                                            Unlimited
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $value->hours }}">
                                                                                            {{ $value->hours }}
                                                                                        </option>
                                                                                    @elseif($value->hours == 2)
                                                                                        <option value="1">1
                                                                                        </option>
                                                                                        <option value="2"
                                                                                            selected>2</option>
                                                                                        <option value="Unlimited">
                                                                                            Unlimited
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $value->hours }}">
                                                                                            {{ $value->hours }}
                                                                                        </option>
                                                                                    @elseif($value->hours == 'Unlimited')
                                                                                        <option value="1">1
                                                                                        </option>
                                                                                        <option value="2">2
                                                                                        </option>
                                                                                        <option value="Unlimited"
                                                                                            selected>Unlimited
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $value->hours }}">
                                                                                            {{ $value->hours }}
                                                                                        </option>
                                                                                    @else
                                                                                        <option value="1">1
                                                                                        </option>
                                                                                        <option value="2">2
                                                                                        </option>
                                                                                        <option value="Unlimited">
                                                                                            Unlimited
                                                                                        </option>
                                                                                        <option
                                                                                            value="{{ $value->hours }}"
                                                                                            selected>
                                                                                            {{ $value->hours }}
                                                                                        </option>
                                                                                    @endif
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <div class="mb-3 input_wrap dark">
                                                                                <label>Extra Time</label>
                                                                                <select name="extra_time_select[]"
                                                                                    class="form-select"
                                                                                    onchange="AddExtratime(event)">
                                                                                    <option value="">Select Here
                                                                                    </option>
                                                                                    <option value="0.5">30 Min
                                                                                    </option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            @endif

                                                        </div>

                                                        <div class="col-md-12 add_adults">
                                                            {{--  @if ($product)
                                                                @foreach ($product as $adult_products)
                                                                    @if ($adult_products->product_name == 'Adult' && $adult_products->price > 0)
                                                                        <div class="row">
                                                                            <input type="hidden"
                                                                                name="adult_product_id"
                                                                                value="{{ $adult_products->id }}">
                                                                            <div class="col-md-4 ">
                                                                                <div class="mb-4 input_wrap">
                                                                                    <label>{{ $adult_products->product_name }}
                                                                                        Qty</label>
                                                                                    <select name="adult_qty[]"
                                                                                        id="adult_qty"
                                                                                        class="form-select" readonly>
                                                                                        <?php
																				for($i=0; $i<=10; $i++){
																					if($i == $adult_products->qty){
																		?>
                                                                                        <option
                                                                                            value="{{ $adult_products->qty }}"
                                                                                            selected>
                                                                                            {{ $adult_products->qty }}
                                                                                        </option>

                                                                                        <?php }else{
																					?>
                                                                                        <option
                                                                                            value="{{ $i }}">
                                                                                            {{ $i }}
                                                                                        </option>

                                                                                        <?php
																				}

																			} ?>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <div class="mb-4 input_wrap dark">
                                                                                    <label>Price</label>
                                                                                    <input type="number"
                                                                                        class="form-control"
                                                                                        placeholder="Price"
                                                                                        name="price[]"
                                                                                        id="adult_price"
                                                                                        value="{{ $adult_products->price }}"
                                                                                        readonly>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-4 ">
                                                                                <div class="mb-4 input_wrap dark">
                                                                                    <label>Tax</label>
                                                                                    <select name="tax[]"
                                                                                        id="adult_tax"
                                                                                        class="form-select">
                                                                                        <option value="18">18%
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            @endif  --}}

                                                        </div>

                                                        <div class="col-md-12 add_sock">


                                                        </div>
                                                        <table id="dataRow">
                                                            @if ($product)
                                                                @foreach ($product as $socks_products)
                                                                    @if (
                                                                        $socks_products->qty == $user['userDetails']->number_of_kids &&
                                                                            strtolower($socks_products->product_name) == 'socks')
                                                                        @continue
                                                                    @elseif(strtolower($socks_products->product_name) == 'socks')
                                                                        @php
                                                                            $socks_products->qty -= $user['userDetails']->number_of_kids;
                                                                        @endphp
                                                                    @endif
                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden"
                                                                                value="{{ getProductPrice($socks_products->product_name) }}"
                                                                                class="product_price">
                                                                            <div class="row mb-3">
                                                                                <input type="hidden"
                                                                                    name="extra_product_id[]"
                                                                                    value="{{ $socks_products->id }}"
                                                                                    class="extra_product_id">
                                                                                <div class="row mb-3">
                                                                                    <input type="hidden"
                                                                                        value="{{ $socks_products->product_name }}"
                                                                                        name="product_name[]">
                                                                                    <div class="col-md-3">
                                                                                        <div class=" input_wrap">
                                                                                            <label>{{ $socks_products->product_name }}
                                                                                                Qty</label>
                                                                                            <select
                                                                                                name="product_quantity[]"
                                                                                                onchange="calculateTotal()"
                                                                                                class="form-select">
                                                                                                <option value="0"
                                                                                                    {{ $socks_products->qty == 0 ? 'selected' : null }}>
                                                                                                    0</option>
                                                                                                <option value="1"
                                                                                                    {{ $socks_products->qty == 1 ? 'selected' : null }}>
                                                                                                    1</option>
                                                                                                <option value="2"
                                                                                                    {{ $socks_products->qty == 2 ? 'selected' : null }}>
                                                                                                    2</option>
                                                                                                <option value="3"
                                                                                                    {{ $socks_products->qty == 3 ? 'selected' : null }}>
                                                                                                    3</option>
                                                                                                <option value="4"
                                                                                                    {{ $socks_products->qty == 4 ? 'selected' : null }}>
                                                                                                    4</option>
                                                                                                <option value="5"
                                                                                                    {{ $socks_products->qty == 5 ? 'selected' : null }}>
                                                                                                    5</option>
                                                                                                <option value="6"
                                                                                                    {{ $socks_products->qty == 6 ? 'selected' : null }}>
                                                                                                    6</option>
                                                                                                <option value="7"
                                                                                                    {{ $socks_products->qty == 7 ? 'selected' : null }}>
                                                                                                    7</option>
                                                                                                <option value="8"
                                                                                                    {{ $socks_products->qty == 8 ? 'selected' : null }}>
                                                                                                    8</option>
                                                                                                <option value="9"
                                                                                                    {{ $socks_products->qty == 9 ? 'selected' : null }}>
                                                                                                    9</option>
                                                                                                <option value="10"
                                                                                                    {{ $socks_products->qty == 10 ? 'selected' : null }}>
                                                                                                    10</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Price</label>
                                                                                            <input type="number"
                                                                                                class="form-control"
                                                                                                placeholder="Price"
                                                                                                value="{{ $socks_products->price }}"
                                                                                                name="price[]"
                                                                                                readonly>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Tax</label>
                                                                                            <select name="tax[]"
                                                                                                class="form-select">
                                                                                                <option
                                                                                                    value="{{ getTax($socks_products->product_name) }}">
                                                                                                    {{ getTax($socks_products->product_name) }}%
                                                                                                </option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-3">
                                                                                        <div class="input_wrap dark">
                                                                                            <label>Total</label>
                                                                                            <input type="text"
                                                                                                value="{{ number_format($socks_products->price + $socks_products->price * (getTax($socks_products->product_name) / 100), 2) }}"
                                                                                                class="final_price form-control">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @endif
                                                        </table>
                                                        <div class="col-md-12" id="myDiv" style="display:none">
                                                            @if ($kid)
                                                                @foreach ($kid as $value)
                                                                    <input class="add_exta" type="checkbox"
                                                                        id="add_extra" name="add_extra[]"
                                                                        value="{{ $value->id }}">
                                                                    <label
                                                                        for="add_extra">{{ $value->name }}</label><br>
                                                                @endforeach
                                                            @endif
                                                        </div>


                                                        <div class="button_wrap">
                                                            @foreach ($productData as $pro)
                                                                @if ($pro->product_name == 'Extra Time' || $pro->product_name == strtolower('socks'))
                                                                    @continue
                                                                @endif
                                                                @if (!hasTwoProduct(request()->route('id'), $pro->product_name, $user['userDetails']->number_of_kids))
                                                                    <div class="h_btn">
                                                                        <a href="javascript:void(0)"
                                                                            onclick="AddItems(event, '{{ $pro->product_name }}', '{{ $pro->product_price }}', '{{ $pro->product_tax }}', {{ $pro->id }})"
                                                                            class="more_adult">+
                                                                            {{ $pro->product_name }}</a>
                                                                    </div>
                                                                @endif
                                                            @endforeach

                                                        </div>

                                                        <hr>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Payment Mode</label>

                                                                <select name="payment_mode" id="services"
                                                                    class="form-select">
                                                                    @if (isset($paymentMethod->payment_method) && $paymentMethod->payment_method != '')
                                                                        @if ($paymentMethod->payment_method == 'Cash')
                                                                            <option value="2" selected>Cash
                                                                            </option>
                                                                            <option value="1">UPI</option>
                                                                            <option value="3">Debit/Credit
                                                                            </option>
                                                                        @elseif($paymentMethod->payment_method == 'UPI')
                                                                            <option value="2">Cash</option>
                                                                            <option value="1" selected>UPI
                                                                            </option>
                                                                            <option value="3">Debit/Credit
                                                                            </option>
                                                                        @else
                                                                            <option value="2">Cash</option>
                                                                            <option value="1">UPI</option>
                                                                            <option value="3" selected>
                                                                                Debit/Credit </option>
                                                                        @endif
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
                                                                    <input type="text" name="couponcode"
                                                                        class="form-control" id="couponCode"
                                                                        placeholder="Any Discount Code"
                                                                        value="{{ $paymentMethod->couponcode }}">
                                                                    <input type="hidden" name="couponvalue"
                                                                        id="couponValue"
                                                                        value="{{ $paymentMethod->couponvalue }}">
                                                                    <button type="button" id="couponCodebutton"
                                                                        class="btn btn-primary">Apply</button>
                                                                </diV>
                                                                <p id="couponmsg"></p>
                                                            </div>

                                                        </div>
                                                        <div class="total_wrap">
                                                            <div class="subtotal">
                                                                <p>Subtotal</p>
                                                                @if ($extraAdded)
                                                                    <p id="subtotal">
                                                                        {{ $paymentMethod->subTotal + $extraAdded->price }}
                                                                    </p>
                                                                @else
                                                                    <p id="subtotal">{{ $paymentMethod->subTotal }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="couponCodeShow">Coupon
                                                                    ({{ $paymentMethod->couponcode }})</span></p>
                                                                <p id="couponValueShow">
                                                                    {{ $paymentMethod->couponvalue }}</p>
                                                            </div>

                                                            <hr>
                                                            <div class="gst">
                                                                <p id="gstOn">Includes Gst @ 18% on KidPrice &
                                                                    Adults</p>
                                                                <p id="gstValue">{{ $paymentMethod->gst18 }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="gstSock">Includes Gst @5% on Socks</p>
                                                                <p id="valueGst">{{ $paymentMethod->gst12 }}</p>
                                                            </div>
                                                            <hr>

                                                            <div class="amount">
                                                                <p>Total Amount</p>
                                                                <p id="totalAmount">{{ $paymentMethod->amounts }}</p>
                                                                <input type="hidden" name="totalAmount"
                                                                    id="chargeAmount"
                                                                    value={{ $paymentMethod->amounts }}>
                                                                <input type="hidden" name="subTotal"
                                                                    id="subtotlSave"
                                                                    value="{{ $paymentMethod->subTotal }}">
                                                                <input type="hidden" name="gst18" id="gst18"
                                                                    value="{{ $paymentMethod->gst18 }}">
                                                                <input type="hidden" name="gst5" id="gst5"
                                                                    value="{{ $paymentMethod->gst5 }}">
                                                                <input type="hidden" name="gst12" id="gst12"
                                                                    value="{{ $paymentMethod->gst12 }}">
                                                                <input type="hidden" id="adult_price_backend"
                                                                    value="{{ isset($result[0]['Adult']) ? $result[0]['Adult'] : 200 }}">
                                                                <input type="hidden" id="socks_price_backend"
                                                                    value="{{ isset($result[0]['Socks']) ? $result[0]['Socks'] : '80' }}">
                                                                @if ($extraAdded)
                                                                    <input type="hidden" id="extra_time"
                                                                        name="extra_time"
                                                                        value="{{ $extraAdded->price }}">
                                                                @else
                                                                @if(isset($extraItems->product_price))
                                                                    <input type="hidden" id="extra_time"
                                                                        name="extra_time"
                                                                        value="{{ $extraItems->product_price }}">
                                                                        @else
                                                                        <input type="hidden" id="extra_time"
                                                                        name="extra_time"
                                                                        value="">
                                                                        @endif
                                                                @endif
                                                            </div>
                                                            <hr>

                                                            <div class="tearm_condition">
                                                                <input type="checkbox" id="" name=""
                                                                    value="" checked="checked">
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
                                            <form action="{{ url('/update-membership') }}" method="post"
                                                id="my-form">
                                                @csrf
                                                <div class="col-md-12">
                                                    <div class="mb-4 input_wrap">
                                                        <label>Phone Number</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="+91 0123456789" onkeyup="calculatePlan()"
                                                            id="mobile_no">
                                                        <div id="resultmembership" class="shadow phone-list">
                                                        </div>
                                                        <div class="happy_card">
                                                            <div class="card_header">
                                                                <div class="card_logo">
                                                                    <img src="{{ asset('assets/images/happyon_logo.png') }}"
                                                                        alt="logo">
                                                                </div>
                                                                <div class="card_symbol">
                                                                    <svg width="48" height="44"
                                                                        viewBox="0 0 48 44" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <g opacity="0.3">
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M0.194806 35.4381C0.208372 39.6656 3.65516 43.0672 7.89746 43.0503L16.0337 43.0178L16.0063 34.4678L0.191895 34.5309L0.194806 35.4381Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M0.183275 31.8143L15.9929 31.7512L15.9649 23.0255L0.155273 23.0885L0.183275 31.8143Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M0.106817 8.02616L0.109744 8.93816L15.9241 8.87512L15.8966 0.315622L7.7604 0.348055C3.51811 0.364966 0.0932657 3.80341 0.106817 8.02616Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M40.0707 0.219304L18.6206 0.30481L18.7576 43.0073L29.6664 42.9638L29.5568 8.82082L47.7763 8.74819L47.7733 7.83619C47.7598 3.61344 44.3082 0.202412 40.0707 0.219304Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M0.14641 20.3755L15.956 20.3125L15.928 11.5868L0.118408 11.6498L0.14641 20.3755Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M32.3197 20.2472L47.8144 20.1855L47.7864 11.4597L32.2917 11.5215L32.3197 20.2472Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M32.3564 31.6861L47.851 31.6243L47.823 22.8986L32.3284 22.9603L32.3564 31.6861Z"
                                                                                fill="#878773" />
                                                                            <path fill-rule="evenodd"
                                                                                clip-rule="evenodd"
                                                                                d="M32.3927 42.9527L40.2092 42.9216C44.4467 42.9047 47.8763 39.4757 47.8628 35.2482L47.8599 34.3409L32.3652 34.4027L32.3927 42.9527Z"
                                                                                fill="#878773" />
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
                                                                    <img src="{{ asset('assets/images/Mastercard.png') }}"
                                                                        alt="mastercard">
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Credit Left</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="22 hrs" id="credit_left"
                                                                    name="credit_left" readonly>
                                                                <input type="hidden" id="user_id" name="user_id">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Kids Name</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Kids Name" id="kidsName2"
                                                                    name="kidsName2" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Number of Kids</label>
                                                                <select name="number_of_kids" id="numberKids"
                                                                    class="form-select">
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
                                                                <label>Number of Hrs</label>
                                                                <select name="hrs" id="hrs"
                                                                    class="form-select">
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
                                                                    <label>Credit Left</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter OTP" required>
                                                                    <div class="h_btn">
                                                                        <a href="#"
                                                                            onclick="verifyMember()">Send</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="h_btn s_btn">
                                                                <button class="btn btn-primary" type="submit"
                                                                    id="submit-button">Use <span
                                                                        id="credit"></span>
                                                                    hours Credit</button>

                                                                {{-- <button class="btn btn-dark" type="submit"
                                                                id="left_credit"> left Credit </button> --}}
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
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.min.js"></script>
    <script src="assets/plugins/chartjs/js/Chart.extension.js"></script>
    <script src="assets/js/index.js"></script>
    <!--app JS-->
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/js/app.js"></script>

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>


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
                    option: $(this).val()
                },
                success: function(data) {

                    // Update the input tags with the received data
                    inputTag.val(data);
                    var sum = 0;
                    $('.kid_price').each(function() {
                        var val = parseFloat($(this).val()) || 0;
                        sum += val;
                    });

                    // Get the value of the first select box
                    var select1Value = parseFloat($('#adult_qty').val());
                    var adult_price = document.getElementById('adult_price_backend').value;
                    var finalAdultPrice = parseFloat(adult_price) * parseFloat(select1Value);

                    // Get the value of the second select box
                    var select2Value = parseFloat($('#socks_qty').val());
                    var socks_price = document.getElementById('socks_price_backend').value;
                    var finalSocksPrice = parseFloat(socks_price) * parseFloat(select2Value);
                    // console.log(select2Value);
                    $("#socks_price").val(finalSocksPrice.toFixed(2));

                    // Add the values of the select boxes to the sum

                    if (isNaN(finalSocksPrice)) {
                        finalSocksPrice = 0;

                        if (isNaN(finalAdultPrice)) {
                            finalAdultPrice = 0;
                            var taxSum = sum + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sum += finalAdultPrice;
                            alert(sum);
                            $("#adult_price").val(finalAdultPrice.toFixed(2));
                        } else {
                            var taxSum = sum + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sum += finalAdultPrice;
                            alert(sum);
                            $("#adult_price").val(finalAdultPrice.toFixed(2));

                        }
                    } else if (isNaN(finalAdultPrice)) {
                        finalAdultPrice = 0;
                        var taxSum = sum + finalAdultPrice;
                        var gstKid = (taxSum * 18) / 100;
                        sum += (finalAdultPrice + finalSocksPrice);

                        $("#adult_price").val(finalAdultPrice.toFixed(2));
                    } else {
                        var taxSum = sum + finalAdultPrice;
                        var gstKid = (taxSum * 18) / 100;
                        $("#adult_price").val(finalAdultPrice.toFixed(2));
                        sum += (finalAdultPrice + finalSocksPrice);
                    }


                    $('#subtotal').text("₹ " + sum.toFixed(2));
                    $('#subtotlSave').val(sum.toFixed(2));
                    document.getElementById('gstOn').innerHTML = "Includes Gst @18% on " + sum.toFixed(
                        2);
                    var gstKid = (sum * 18) / 100;
                    document.getElementById('gstValue').innerHTML = '' + gstKid.toFixed(2);
                    var finalAmount = sum + gstKid;
                    document.getElementById('totalAmount').innerHTML = '₹ ' + finalAmount.toFixed(2);
                    $("#chargeAmount").val(finalAmount.toFixed(2));
                    $('#gst18').val(gstKid.toFixed(2));

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>


    <script>
        //Coupon Apply code
        $("#couponCodebutton").on("click", function() {

            $.ajax({
                url: baseUrl + '/get-coupon',
                type: 'GET',
                data: {
                    option: $("#couponCode").val()
                },
                success: function(data) {

                var sum = 0;
                    $('.kid_price').each(function() {
                        var val = parseFloat($(this).val()) || 0;
                        sum += val;
                    });

                    var discount = 0;
                    if (data != '') {
                        if (data.discount_type == 'flat') {
                            var discount = data.discount;
                            $('#couponValue').val(discount.toFixed(2));
                            $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                            $('#couponValueShow').html(discount.toFixed(2));
                            var sumamount = sum - discount;

                        } else if (data.discount_type == 'percent') {
                            var discount = data.discount;
                            var discount = (sum * discount) / 100;
                            $('#couponValue').val(discount.toFixed(2));
                            $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                            $('#couponValueShow').html(discount.toFixed(2));
                            var sumamount = sum - discount;
                        }
                        $('#couponmsg').html(
                            "<p>Apply Coupon Successfull!</p>");

                        // Get the value of the first select box
                        var select1Value = parseFloat($('#adult_qty')
                            .val());
                        var adult_price = document.getElementById(
                            'adult_price_backend').value;
                        var finalAdultPrice = parseFloat(adult_price) *
                            parseFloat(select1Value);

                        //   var taxSum = sum+finalAdultPrice;
                        // 	$("#adult_price").val(finalAdultPrice);

                        // Get the value of the second select box
                        var select2Value = parseFloat($('#socks_qty')
                            .val());
                        var socks_price = document.getElementById(
                            'socks_price_backend').value;
                        var finalSocksPrice = parseFloat(socks_price) *
                            parseFloat(select2Value);
                        // console.log(select2Value);
                        $("#socks_price").val(finalSocksPrice.toFixed(
                            2));


                        if (isNaN(finalSocksPrice) && isNaN(
                                finalAdultPrice)) {
                            finalSocksPrice = 0;
                            finalAdultPrice = 0;


                            var taxSum = sum + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sumamount += (finalAdultPrice +
                                finalSocksPrice);
                            //alert(sum);
                            $("#adult_price").val(finalAdultPrice
                                .toFixed(2));
                        } else if (!isNaN(finalSocksPrice) && !isNaN(
                                finalAdultPrice)) {

                            var taxSum = sum + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sumamount += (finalAdultPrice +
                                finalSocksPrice);
                            //alert(sum);
                            $("#adult_price").val(finalAdultPrice
                                .toFixed(2));
                        } else if (!isNaN(finalAdultPrice)) {
                            //finalAdultPrice = 0;
                            var taxSum = sum + finalAdultPrice;
                            var gstKid = (taxSum * 18) / 100;
                            sumamount += finalAdultPrice;
                            //alert(finalAdultPrice);

                            $("#adult_price").val(finalAdultPrice
                                .toFixed(2));
                        } else if (!isNaN(finalSocksPrice)) {

                            sumamount += finalSocksPrice;
                        }

                        // Display the sum on the screen
                        //$('#total').text(sum);
                        $('#subtotal').text("₹ " + sumamount.toFixed(
                            2));
                        $('#subtotlSave').val(sumamount.toFixed(2));
                        document.getElementById('gstOn').innerHTML =
                            "Includes Gst @18% on " + sumamount.toFixed(
                                2);
                        var gstKid = (sumamount * 18) / 100;
                        document.getElementById('gstValue').innerHTML =
                            '' + gstKid.toFixed(2);
                        var finalAmount = sumamount + gstKid;
                        document.getElementById('totalAmount')
                            .innerHTML = '₹ ' + finalAmount.toFixed(2);
                        $("#chargeAmount").val(finalAmount.toFixed(2));
                        $('#gst18').val(gstKid.toFixed(2));

                    } else {


                        // Get the value of the first select box
                        var select1Value = parseFloat($('#adult_qty')
                            .val());
                        var adult_price = document.getElementById(
                            'adult_price_backend').value;
                        var finalAdultPrice = parseFloat(adult_price) *
                            parseFloat(select1Value);

                        //   var taxSum = sum+finalAdultPrice;
                        // 	$("#adult_price").val(finalAdultPrice);

                        // Get the value of the second select box
                        var select2Value = parseFloat($('#socks_qty')
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
                        // Display the sum on the screen
                        //$('#total').text(sum);

                        $('#subtotal').text("₹ " + sum.toFixed(2));



                        $('#subtotlSave').val(sum.toFixed(2));
                        document.getElementById('gstOn').innerHTML =
                            "Includes Gst @18% on " + sum.toFixed(2);
                        var gstKid = (sum * 18) / 100;
                        document.getElementById('gstValue').innerHTML =
                            '' + gstKid.toFixed(2);
                        var finalAmount = sum + gstKid;
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
        function AddItems(event, name, price, tax, id) {
            var table = document.querySelector('#dataRow');
            var newRow = document.createElement("tr");
            var cell1 = document.createElement("td");

            data =
                `   <input type="hidden" name="extra_product_id[]"
                    value="${id}"
                    class="extra_product_id">
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
</div>`;
            if (name == 'Extra Time') {
                alert('This Product Not Allowed Here');
            } else {
                cell1.innerHTML = data;
                newRow.appendChild(cell1);
                table.appendChild(newRow);
                event.target.style.display = 'none';
            }

        }

        $(document).ready(function() {
            $(document).on('change', '#adult_qty', function() {
                calculateTotal();
            });
            // Listen for changes to the second select box
            $(document).on('change', '#socks_qty', function() {
                calculateTotal();
            });

        });

        function AddExtratime(event) {
            var hours = event.target.parentElement.parentElement.parentElement.querySelector('select[name="hours[]"]');
            var price = event.target.parentElement.parentElement.parentElement.querySelector('input[name="kid_price[]"]');
            if (hours.value != 'Unlimited') {
                if (event.target.value == '') {
                    hours.value = 1;
                    price.value = parseFloat(price.value) - 169.49;
                    calculateTotal();
                } else {
                    let deisredValue = parseFloat(hours.value) + parseFloat(event.target.value);
                    for (var i = 0; i < hours.options.length; i++) {
                        if (hours.options[i].value === deisredValue) {
                            break;
                        } else {
                            var newOption = document.createElement("option");
                            newOption.value = deisredValue;
                            newOption.text = deisredValue + " hours";
                            hours.appendChild(newOption);
                            break;
                        }
                    }
                    hours.value = deisredValue;
                    price.value = parseFloat(price.value) + 169.49;
                    calculateTotal();
                }
            } else {
                alert('Extra Time Not Allowed on unlimited Hours');
            }
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
            $('input[name="price[]"]').each(function(index) {
                var finalAdultPrice = parseFloat(select1Value[index]) * parseFloat(adult_price[index]);
                totalPrice += finalAdultPrice;
                finalPriceEach.push(finalAdultPrice);
                $(this).val(finalAdultPrice);
            });
            $('input[name="kid_price[]"]').each(function(index) {
                var val = parseFloat($(this).val()) || 0;
                totalPrice += val;
            });
            var tax = [];
            $('select[name="tax[]"]').each(function(index) {
                var val = parseFloat($(this).val()) || 0;
                tax.push(val);
            });
            $('input[name="kid_price[]"]').each(function(index) {
                var val = parseFloat($(this).val()) || 0;
                var taxAmount = (val * 18) / 100;
                taxAmount18 += taxAmount;
            });
            $('.final_price').each(function(index) {
                var taxAmount = (finalPriceEach[index] * tax[index]) / 100;
                if (tax[index] == 18) {
                    taxAmount18 += taxAmount;
                }
                if (tax[index] == 5) {
                    taxAmount5 += taxAmount;
                }
                if (tax[index] == 12) {
                    taxAmount12 += taxAmount;
                }
                var total = (finalPriceEach[index] + taxAmount).toFixed(2);
                $(this).val(total);
            });
            $('#subtotal').text("₹ " + totalPrice.toFixed(2));
            $('input[name="subTotal"]').val(totalPrice.toFixed(2));
            $('input[name="totalAmount"]').text(totalPrice.toFixed(2));

            $('#gstValue').text("₹ " + taxAmount18.toFixed(2));


            $('input[name="gst18"]').val(taxAmount18.toFixed(2));


            $('#valueGst').text("₹ " + taxAmount5.toFixed(2));
            $('input[name="gst5"]').val(taxAmount5.toFixed(2));


            $('#gst12PercentVal').text("₹ " + taxAmount12.toFixed(2));
            $('input[name="gst12"]').val(taxAmount12.toFixed(2));

            var totalwithTax = (totalPrice + taxAmount18 + taxAmount5 + taxAmount12).toFixed(2);
            $('#totalAmount').text("₹ " + totalwithTax);
            $('input[name="totalAmount"]').val(totalwithTax);
            $('input[name="subTotal"]').text(totalPrice.toFixed(2));
        }
    </script>

    <script>
        //Add Extra Hours
        function extraFunction() {

            var div = document.getElementById("myDiv");
            var button = document.getElementById('extra');

            button.style.display = "none";
            div.style.display = "block";
        }

        var checkboxes = document.getElementsByName('add_extra[]');

        var result = document.getElementById('extra_time').value;
        for (var i = 0; i < checkboxes.length; i++) {

            checkboxes[i].addEventListener('change', updateResult);
        }
        //add_exta
        function updateResult() {

            // Calculate the result based on the checkbox values
            var checkedCount = 0;
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {

                    checkedCount++;
                }
            }

            var results = checkedCount;

            var extra = parseFloat(result) * parseFloat(results);
            var subTotal = parseFloat(document.getElementById('subtotlSave').value);

            var SubTotalExtra = (subTotal + extra).toFixed(2);

            var gstPer18 = parseFloat(document.getElementById('gst18').value);
            var gstPer12 = parseFloat(document.getElementById('gst12').value);
            $('#extra_time').val(extra);


            var gstKid = (parseFloat(result) * 18) / 100;
            var gstKid = gstPer18 + gstKid;

            //var totalAmount = document.getElementById('chargeAmount').value;

            if (gstPer12 > 0) {

                var totalExtra = parseFloat(SubTotalExtra) + parseFloat(gstKid) + parseFloat(gstPer12);
            } else {
                var totalExtra = parseFloat(SubTotalExtra) + parseFloat(gstKid);
            }
            document.getElementById('gstValue').innerHTML = '' + gstKid.toFixed(2);

            // Calculate the final amount and display it on the screen
            document.getElementById('subtotal').innerHTML = "₹ " + SubTotalExtra;
            document.getElementById('totalAmount').innerHTML = '₹ ' + totalExtra.toFixed(2);
            $("#chargeAmount").val(totalExtra);
            $('#gst18').val(gstKid);
            document.getElementById('gstOn').innerHTML = "Includes Gst @18% on " + gstKid.toFixed(2);
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
                            $('#resultmembership').html(data);
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
            // Prevent the default form submission behavior
            event.preventDefault();

            // Get the form data
            const form = document.getElementById('my-form');
            const credit = form.querySelector('[name="credit_left"]').value;

            const hrs = form.querySelector('[name="hrs"]').value;
            // Validate the form data
            if (parseFloat(credit) < parseFloat(hrs)) {
                alert('Please renewal your membership.');
                return;
            }
            // Submit the form if the data is valid
            form.submit();
        });
    </script>
</body>

</html>
