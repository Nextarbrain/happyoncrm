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
                                                    <a class="nav-link active rounded" data-bs-toggle="tab"
                                                        href="#paid" aria-selected="true" role="tab">
                                                        <p class="heading">Event Booking </p>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="tab-content">

                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                <form action="{{ url('/edit-event-post') }}" method="post"
                                                    id="my-form">
                                                    @csrf
                                                    <input type="hidden" value="{{ $eventbooking->id }}"
                                                        name="id">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Booking Date </label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="date"
                                                                    value="{{ date('Y-m-d', strtotime($eventbooking->created_at)) }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 input_wrap dark time">
                                                            <div class="mb-4">
                                                                <label>Booking Time</label>
                                                                <input type="time" class="form-control"
                                                                    placeholder="Time"
                                                                    value="{{ date('H:i', strtotime($eventbooking->created_at)) }}"
                                                                    required readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Event Date</label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="Booking Date" id="booking_date"
                                                                    name="booking_date"
                                                                    value="{{ $eventbooking->booking_date }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 input_wrap time">
                                                            <div class="mb-4">
                                                                <label>Booking From</label>
                                                                <input type="time" class="form-control"
                                                                    placeholder="Booking Time From" id="time_from"
                                                                    name="booking_time_from"
                                                                    value="{{ $eventbooking->booking_time_from }}"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 input_wrap time">
                                                            <div class="mb-4">
                                                                <label>Booking To</label>
                                                                <input type="time" class="form-control"
                                                                    placeholder="Booking Time To" id="time_to"
                                                                    name="booking_time_to"
                                                                    value="{{ $eventbooking->booking_time_to }}"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Phone"
                                                                    value="{{ $eventbooking->phone }}" name="phone"
                                                                    id="phone" pattern="[0-9]{10}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Billing Name <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Billing Name"
                                                                    value="{{ $eventbooking->bill_name }}"
                                                                    name="billing_name" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="contact">Contact Person
                                                                    <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Contact"value="{{ $eventbooking->contact_person }}"
                                                                    name="contact" required>

                                                            </div>
                                                        </div>


                                                        <div class="col-md-6 input_wrap">
                                                            <div class="mb-4">
                                                                <label>Kid name</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Kid Name"
                                                                    value="{{ $eventbooking->kid_name }}"
                                                                    name="kid_name" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Kid DOB</label>
                                                                <input type="date" class="form-control"
                                                                    placeholder="Kid DOB"
                                                                    value="{{ $eventbooking->kid_dob }}"
                                                                    name="kid_dob" required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12" id="item_box">
                                                            <h4><b>List Items</b></h4>
                                                            <br>
                                                            @php
                                                                $loop_count_num = 1;
                                                                $rowcount = count($eventitems);
                                                            @endphp
                                                            @foreach ($eventitems as $key => $item)
                                                                <div class="row items_{{ $loop_count_num }}">
                                                                    <input type="hidden"
                                                                        class="form-control event_item_ids"
                                                                        value="{{ $item->id }}" id="item_id"
                                                                        name="event_item_ids[]">

                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Item</label>
                                                                            <input type="text"
                                                                                class="form-control item_name"
                                                                                value="{{ $item->item_name }}"
                                                                                id="item_name" placeholder="Item"
                                                                                name="item_name[]"
                                                                                onkeyup="searchProductData(event)">
                                                                            <div id="searchResult"
                                                                                class="shadow phone-list">
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Prices</label>
                                                                            <input type="text"
                                                                                class="form-control item_price"
                                                                                value="{{ $item->item_price }}"
                                                                                id="item_price" placeholder="Prices"
                                                                                name="item_price[]">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Qty</label>
                                                                            <input type="number"
                                                                                class="form-control item_qty"
                                                                                id="item_qty"
                                                                                value="{{ $item->item_qty }}"
                                                                                placeholder="Qty" value="1"
                                                                                name="item_qty[]">
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-2 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Tax</label>
                                                                            <select name="item_tax[]" id="item_tax"
                                                                                class="form-select item_tax">
                                                                                @if ($item->item_tax == 18)
                                                                                    <option value="18" selected>18%
                                                                                    </option>
                                                                                    <option value="12">12%</option>
                                                                                    <option value="5">5% </option>
                                                                                    <option value="0">0% </option>
                                                                                @elseif($item->item_tax == 12)
                                                                                    <option value="18">18%</option>
                                                                                    <option value="12" selected>12%
                                                                                    </option>
                                                                                    <option value="5">5% </option>
                                                                                    <option value="0">0% </option>
                                                                                @elseif($item->item_tax == 5)
                                                                                    <option value="18">18%</option>
                                                                                    <option value="12">12%</option>
                                                                                    <option value="5" selected>5%
                                                                                    </option>
                                                                                    <option value="0">0% </option>
                                                                                @else
                                                                                    <option value="18">18%</option>
                                                                                    <option value="12">12%</option>
                                                                                    <option value="5">5% </option>
                                                                                    <option value="0" selected>0%
                                                                                    </option>
                                                                                @endif

                                                                            </select>

                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-1">

                                                                        @if ($loop_count_num == 1)
                                                                            <i class="fa fa-plus fs-4 btn btn-primary"
                                                                                onclick="add_item_more()"
                                                                                style="background:#0A5F59;"
                                                                                aria-hidden="true"></i>
                                                                        @else
                                                                            </a><i
                                                                                class="fa fa-trash fs-4 btn btn-danger"
                                                                                onclick="remove_image_more({{ $loop_count_num }}, {{ $item->id }})"
                                                                                aria-hidden="true"></i></a>
                                                                        @endif
                                                                    </div>

                                                                </div>
                                                                @php
                                                                    $loop_count_num++;
                                                                @endphp
                                                            @endforeach

                                                        </div>


                                                        <div class="row mb-4">
                                                            <div class="col-lg-12 col-md-12">
                                                                <a href="javascript:void(0)" class="btn btn-primary"
                                                                    onclick="calculate_items()"
                                                                    style="background:#0A5F59;">Calculate</a>
                                                            </div>

                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>COUPON CODE</label>
                                                                <div class="d-flex">
                                                                    <input type="text" name="couponcode"
                                                                        class="form-control" id="couponCode"
                                                                        placeholder="Any Discount Code"
                                                                        value="{{ $eventbooking->couponcode }}">
                                                                    <input type="hidden" name="couponvalue"
                                                                        id="couponValue"
                                                                        value="{{ $eventbooking->couponvalue }}">
                                                                    <button type="button" id="couponCodebutton"
                                                                        class="btn btn-primary">Apply</button>
                                                                </diV>
                                                                <p id="couponmsg"></p>
                                                            </div>

                                                        </div>



                                                        <div class="total_wrap">
                                                            <div class="subtotal">
                                                                <p>Subtotal</p>
                                                                <p id="subtotal">{{ $eventbooking->subtotal }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="couponCodeShow">Coupon
                                                                    ({{ $eventbooking->couponcode }})</span></p>
                                                                <p id="couponValueShow">
                                                                    {{ $eventbooking->couponvalue }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst">
                                                                <p id="gst18per">Includes Gst @ 18% on <span
                                                                        id="18peron"></span></p>
                                                                <p id="valueGst18">{{ $eventbooking->gst18 }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="gst12per">Includes Gst @12% on <span
                                                                        id="12peron"></span></p>
                                                                <p id="valueGst12">{{ $eventbooking->gst12 }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="gst5per">Includes Gst @5% on <span
                                                                        id="5peron"></span></p>
                                                                <p id="valueGst5">{{ $eventbooking->gst5 }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="amount">
                                                                <p>Total Amount</p>
                                                                <p id="totalAmount">{{ $eventbooking->totalamount }}
                                                                </p>
                                                                <input type="hidden"
                                                                    value="{{ $eventbooking->totalamount }}"
                                                                    name="totalAmount" id="chargeAmount">
                                                                <input type="hidden"
                                                                    value="{{ $eventbooking->subtotal }}"
                                                                    name="subtotal" id="subtotalSave">
                                                                <input type="hidden"
                                                                    value="{{ $eventbooking->gst18 }}" name="gst18"
                                                                    id="gst18">
                                                                <input type="hidden"
                                                                    value="{{ $eventbooking->gst12 }}" name="gst12"
                                                                    id="gst12">
                                                                <input type="hidden"
                                                                    value="{{ $eventbooking->gst5 }}" name="gst5"
                                                                    id="gst5">

                                                            </div>
                                                            <br>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Payment Mode</label>
                                                                <select name="payment_method" id="payment_method"
                                                                    class="form-select">
                                                                    @if ($eventbooking->payment_method == 'UPI')
                                                                        <option value="UPI" selected>UPI</option>
                                                                        <option value="Cash">Cash</option>
                                                                        <option value="Debit/Credit">Debit/Credit
                                                                        </option>
                                                                        <option value="Partial Payment">Partial Payment
                                                                        </option>
                                                                    @elseif($eventbooking->payment_method == 'Cash')
                                                                        <option value="UPI">UPI</option>
                                                                        <option value="Cash" selected>Cash</option>
                                                                        <option value="Debit/Credit">Debit/Credit
                                                                        </option>
                                                                        <option value="Partial Payment">Partial Payment
                                                                        </option>
                                                                    @elseif($eventbooking->payment_method == 'Debit/Credit')
                                                                        <option value="UPI">UPI</option>
                                                                        <option value="Cash">Cash</option>
                                                                        <option value="Debit/Credit" selected>
                                                                            Debit/Credit </option>
                                                                        <option value="Partial Payment">Partial Payment
                                                                        </option>
                                                                    @else
                                                                        <option value="UPI">UPI</option>
                                                                        <option value="Cash">Cash</option>
                                                                        <option value="Debit/Credit">Debit/Credit
                                                                        </option>
                                                                        <option value="Partial Payment" selected>
                                                                            Partial Payment </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12" id="advance_payment">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Advance Amount</label>
                                                                        <input type="number" class="form-control"
                                                                            value="{{ $eventbooking->advance_amount }}"
                                                                            placeholder="Advance Amount"
                                                                            id="advance_amount" name="advance_amount">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 input_wrap time">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Payment Method</label>
                                                                        <select name="advance_payment_method"
                                                                            class="form-select">

                                                                            @if ($eventbooking->advance_payment_method == 'UPI')
                                                                                <option value="UPI" selected>UPI
                                                                                </option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                                <option value="Pending">Pending
                                                                                </option>
                                                                            @elseif($eventbooking->advance_payment_method == 'Cash')
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash" selected>Cash
                                                                                </option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                                <option value="Pending">Pending
                                                                                </option>
                                                                            @elseif($eventbooking->advance_payment_method == 'Debit/Credit')
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit" selected>
                                                                                    Debit/Credit </option>
                                                                                <option value="Pending">Pending
                                                                                </option>
                                                                            @else
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                                <option value="Pending" selected>
                                                                                    Pending </option>
                                                                            @endif
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Due Amount</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $eventbooking->due_amount }}"
                                                                            placeholder="Due Amount"
                                                                            id="due_amount_show" disabled>
                                                                        <input type="hidden" id="due_amount"
                                                                            value="{{ $eventbooking->due_amount }}"
                                                                            name="due_amount">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 input_wrap time">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Payment in installment</label>
                                                                        <select name="payment_in_installment"
                                                                            id="payment_in_installment_method"
                                                                            class="form-select">
                                                                            @if ($eventbooking->payment_in_installment == 'No')
                                                                                <option value="No" selected>No
                                                                                </option>
                                                                                <option value="Yes">Yes</option>
                                                                            @else
                                                                                <option value="No">No</option>
                                                                                <option value="Yes" selected>Yes
                                                                                </option>
                                                                            @endif

                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row" id="installment_amount_box"
                                                                style="display:none">
                                                                <div class="col-md-6">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Installment Amount</label>
                                                                        <input type="number" class="form-control"
                                                                            class=""
                                                                            value="{{ $eventbooking->installment_amount }}"
                                                                            placeholder="Installment Amount"
                                                                            id="installment_amount"
                                                                            name="installment_amount">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 input_wrap time">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Payment Method</label>
                                                                        <select name="installment_amount_method"
                                                                            class="form-select">
                                                                            @if ($eventbooking->installment_amount_method == 'UPI')
                                                                                <option value="UPI" selected>UPI
                                                                                </option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                            @elseif($eventbooking->installment_amount_method == 'Cash')
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash" selected>Cash
                                                                                </option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                            @else
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit" selected>
                                                                                    Debit/Credit </option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Final Installment</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $eventbooking->final_installment }}"
                                                                            placeholder="Final Amount"
                                                                            id="final_amount"
                                                                            name="final_installment">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6 input_wrap time">
                                                                    <div class="mb-4 input_wrap">
                                                                        <label>Payment Method</label>
                                                                        <select name="final_installment_method"
                                                                            class="form-select">
                                                                            @if ($eventbooking->final_installment_method == 'UPI')
                                                                                <option value="UPI" selected>UPI
                                                                                </option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                            @elseif($eventbooking->final_installment_method == 'Cash')
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash" selected>Cash
                                                                                </option>
                                                                                <option value="Debit/Credit">
                                                                                    Debit/Credit </option>
                                                                            @else
                                                                                <option value="UPI">UPI</option>
                                                                                <option value="Cash">Cash</option>
                                                                                <option value="Debit/Credit" selected>
                                                                                    Debit/Credit </option>
                                                                            @endif
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>


                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Payment Status</label>
                                                                <select name="payment_status" id="payment_status"
                                                                    class="form-select">
                                                                    @if ($eventbooking->payment_status == 'Open')
                                                                        <option value="Open" selected>Open</option>
                                                                        <option value="Paid">Paid</option>
                                                                        <option value="Cancel">Cancel </option>
                                                                    @elseif($eventbooking->payment_status == 'Paid')
                                                                        <option value="Open">Open</option>
                                                                        <option value="Paid" selected>Paid</option>
                                                                        <option value="Cancel">Cancel </option>
                                                                    @else
                                                                        <option value="Open">Open</option>
                                                                        <option value="Paid">Paid</option>
                                                                        <option value="Cancel" selected>Cancel
                                                                        </option>
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="tearm_condition">
                                                            <input type="checkbox" id="" name=""
                                                                value="" checked="checked">
                                                            <p>By Agreeing to the <b>Terms and Condition</b> of HappyOn
                                                            </p>
                                                        </div>
                                                        <div class="h_btn s_btn">
                                                            <button class="btn btn-primary">Update Event</button>
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

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>

    <script>
        $(document).ready(function() {
            if ($('#payment_method').val() == "Partial Payment") {
                $('#advance_payment').css("display", "block");
            } else {
                $('#advance_payment').css("display", "none");
                $('#advance_amount').val(0);
                $('#due_amount').val(0);
                $('#due_amount_show').val(0);
                $('#final_amount').val(0);
            }

            if ($('#payment_in_installment_method').val() == "Yes") {
                $('#installment_amount_box').css("display", "flex");
            } else {
                $('#installment_amount_box').css("display", "none");
                $('#installment_amount').val(0);
            }


        });
    </script>


    <script>
        $('#payment_method').on('change', function() {

            if (this.value == "Partial Payment") {
                $('#advance_payment').css("display", "block");
                $('#advance_amount').on('blur', function() {
                    var advance_amount = parseFloat($(this).val().toFixed(2));
                    var totalAmount = parseFloat($('#chargeAmount').val().toFixed(2));
                    if (totalAmount > advance_amount && advance_amount > 0) {
                        var final_amount = totalAmount - advance_amount;
                        $('#final_amount').val(final_amount.toFixed(2));
                        $('#due_amount').val(final_amount.toFixed(2));
                        $('#due_amount_show').val(final_amount.toFixed(2));
                    } else {
                        $('#advance_amount').val(0);
                        $('#due_amount').val(0);
                        $('#due_amount_show').val(0);
                        $('#final_amount').val(0);
                    }
                });

            } else {
                $('#advance_payment').css("display", "none");
                $('#advance_amount').val(0);
                $('#due_amount').val(0);
                $('#due_amount_show').val(0);
                $('#final_amount').val(0);
            }
        });

        $('#payment_in_installment_method').on('change', function() {

            if (this.value == "Yes") {
                $('#installment_amount_box').css("display", "flex");

                $('#installment_amount').on('blur', function() {
                    var installment_amount = parseFloat($(this).val().toFixed(2));
                    var advance_amount = parseFloat($('#advance_amount').val().toFixed(2));
                    var totalAmount = parseFloat($('#chargeAmount').val().toFixed(2));

                    if (totalAmount > installment_amount && installment_amount > 0) {

                        var final_amount = totalAmount - advance_amount - installment_amount;
                        $('#final_amount').val(final_amount.toFixed(2));
                        $('#due_amount').val(final_amount.toFixed(2));
                        $('#due_amount_show').val(final_amount.toFixed(2));
                    } else {
                        $('#installment_amount').val(0);

                    }
                });

            } else {
                $('#installment_amount_box').css("display", "none");
                $('#installment_amount').val(0);
            }
        });


        $('#payment_status').on('change', function() {

            if (this.value == "Paid") {
                $('#due_amount').val(0);
                $('#due_amount_show').val(0);
            } else {
                if ($('#payment_in_installment_method').val() == 'Yes') {

                    var installment_amount = parseFloat($('#installment_amount').val().toFixed(2));
                    var advance_amount = parseFloat($('#advance_amount').val().toFixed(2));
                    var totalAmount = parseFloat($('#chargeAmount').val().toFixed(2));

                    if (totalAmount > installment_amount && installment_amount > 0) {

                        var final_amount = totalAmount - advance_amount - installment_amount;
                        $('#final_amount').val(final_amount.toFixed(2));
                        $('#due_amount').val(final_amount.toFixed(2));
                        $('#due_amount_show').val(final_amount.toFixed(2));
                    } else {
                        $('#installment_amount').val(0);

                    }
                } else {

                    var advance_amount = parseFloat($('#advance_amount').val().toFixed(2));
                    var totalAmount = parseFloat($('#chargeAmount').val().toFixed(2));
                    if (totalAmount > advance_amount && advance_amount > 0) {

                        var final_amount = totalAmount - advance_amount;
                        $('#final_amount').val(final_amount.toFixed(2));
                        $('#due_amount').val(final_amount.toFixed(2));
                        $('#due_amount_show').val(final_amount.toFixed(2));
                    } else {

                        $('#advance_amount').val(0);
                        $('#due_amount').val(0);
                        $('#due_amount_show').val(0);
                        $('#final_amount').val(0);
                    }
                }
            }
        });
    </script>


    <script>
        var baseUrl = "{{ URL::to('/') }}";
        $("#couponCodebutton").on("click", function() {
            $('#couponmsg').html(`<p class="text-danger"></p>`);
            var sum = $("#subtotalSave").val();
            var chargeAmount = $("#chargeAmount").val();
            $.ajax({
                url: baseUrl + '/get-coupon',
                type: 'GET',
                data: {
                    option: $("#couponCode").val()
                },
                success: function(data) {
                    console.log(data.status);
                    if (data.status==400) {
                        $('#couponmsg').html(`<p class="text-danger"> ${data.msg} </p>`);
                   } else {
                    var discount = 0;
                    // if (data != '') {
                    //     if (data.discount_type == 'flat') {
                    //         var discount = data.discount;
                    //         $('#couponValue').val(discount.toFixed(2));
                    //         $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                    //         $('#couponValueShow').html(discount.toFixed(2));
                    //         var sumamount = chargeAmount - discount;

                    //     } else if (data.discount_type == 'percent') {
                    //         var discount = data.discount;
                    //         console.log(discount);
                    //         var discount = (sum * discount) / 100;
                    //         $('#couponValue').val(discount.toFixed(2));
                    //         $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                    //         $('#couponValueShow').html(discount.toFixed(2));
                    //         var sumamount = chargeAmount - discount;
                    //        var sub= $('#subtotalSave').val();
                    //        var sub=sub-discount;
                    //         console.log(discount, sumamount,sub);

                    //     }

                    //     $('#chargeAmount').val(sumamount.toFixed(2));
                    //     // $('#totalAmount').html(sumamount.toFixed(2));
                    //     $('#totalAmount').text(sumamount.toFixed(2));
                    //     $('#couponmsg').html("<p class='text-success'>Apply Coupon Successfull!</p>");
                    // }
                    calculate_items_with_coupon(data);

                   }
                }
            });
        });



        function calculate_items_with_coupon(data) {
                    var item_name = $("input[name='item_name[]']")
                        .map(function() {
                            return $(this).val();
                        }).get();

                    var item_price = $("input[name='item_price[]']")
                        .map(function() {
                            return $(this).val();
                        }).get();

                    var item_qty = $("input[name='item_qty[]']")
                        .map(function() {
                            return $(this).val();
                        }).get();


                    var item_tax = $("select[name='item_tax[]']")
                        .map(function() {
                            return $(this).val();
                        }).get();

                    var subTotal = 0;

                    var discountTotal = 0;

                    var gst18total = 0;


                    var gst12total = 0;


                    var gst5total = 0;

                    var totalAmount = 0;

                    for (i = 0; i < item_name.length; i++) {


                        if (item_qty[i] == 0) {
                            var erroMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                            $('#errorMsg').html(erroMsg).fadeOut(9000);
                        } else {
                            subTotal += item_price[i] * item_qty[i];
                            var rowtotal = 0;
                            var discount = 0;
                            var gst18 = 0;
                            var gst12 = 0;
                            var gst5 = 0;
                            var rowtotal = item_price[i] * item_qty[i];

                            if (data.discount_type == 'flat') {
                                var discount = data.discount;
                                var rowtotal = rowtotal - discount;

                            } else if (data.discount_type == 'percent') {
                                var discount = data.discount;
                                var discount = (rowtotal * discount) / 100;
                                var rowtotal = rowtotal - discount;

                            }

                            if (item_tax[i] == 18) {

                                var gst18 = (rowtotal * item_tax[i]) / 100;

                            }
                            if (item_tax[i] == 12) {

                                var gst12 = (rowtotal * item_tax[i]) / 100;

                            }
                            if (item_tax[i] == 5) {

                                var gst5 = (rowtotal * item_tax[i]) / 100;

                            }
                        }

                        discountTotal += parseFloat(discount);
                        gst18total += parseFloat(gst18);
                        gst12total += parseFloat(gst12);
                        gst5total += parseFloat(gst5);

                    }

                    if (data.discount_type == 'flat') {
                        $('#couponValue').val(discountTotal.toFixed(2));
                        $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                        $('#couponValueShow').html(discountTotal.toFixed(2));
                    } else if (data.discount_type == 'percent') {
                        $('#couponValue').val(discountTotal.toFixed(2));
                        $('#couponCodeShow').html("Coupon Code (" + data.coupon_name + ")");
                        $('#couponValueShow').html(discountTotal.toFixed(2));
                    }

                    $('#couponmsg').html("<p>Apply Coupon Successfull!</p>");

                    var totalAmount = subTotal + gst18total + gst12total + gst5total - discountTotal;

                    $('#subtotal').text(subTotal.toFixed(2));


                    $('#valueGst18').text(gst18total.toFixed(2));

                    $('#valueGst12').text(gst12total.toFixed(2));

                    $('#valueGst5').text(gst5total.toFixed(2));
                    $('#totalAmount').text(totalAmount.toFixed(2));


                    $('#subtotalSave').val(subTotal.toFixed(2));
                    $('#gst18').val(gst18total.toFixed(2));
                    $('#gst12').val(gst12total.toFixed(2));
                    $('#gst5').val(gst5total.toFixed(2));
                    $('#chargeAmount').val(totalAmount.toFixed(2));

                    }
    </script>



    <script>
        //add more item
        var loop_item_count = {{ $rowcount }};

        function add_item_more() {

            loop_item_count++;

            var item_name = $("input[name='item_name[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_price = $("input[name='item_price[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_qty = $("input[name='item_qty[]']")
                .map(function() {
                    return $(this).val();
                }).get();


            var item_tax = $("select[name='item_tax[]']")
                .map(function() {
                    return $(this).val();
                }).get();



            var subTotal = 0;

            var gst18total = 0;
            // var peron18 = 0;

            var gst12total = 0;
            // var peron12 = 0

            var gst5total = 0;
            //  var peron5 = 0
            var totalAmount = 0;
            for (i = 0; i < item_name.length; i++) {

                if (item_qty[i] == 0) {
                    var erroMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                    $('#errorMsg').html(erroMsg).fadeOut(9000);
                } else {

                    var rowtotal = 0;
                    var gst18 = 0;
                    var gst12 = 0;
                    var gst5 = 0;
                    var rowtotal = item_price[i] * item_qty[i];
                    if (item_tax[i] == 18) {

                        var gst18 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 12) {

                        var gst12 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 5) {

                        var gst5 = (rowtotal * item_tax[i]) / 100;

                    }
                }

                subTotal += parseFloat(rowtotal.toFixed(2));
                gst18total += parseFloat(gst18.toFixed(2));
                gst12total += parseFloat(gst12.toFixed(2));
                gst5total += parseFloat(gst5.toFixed(2));

            }

            var totalAmount = subTotal + gst18total + gst12total + gst5total;

            $('#subtotal').text(subTotal);

            //$('#18peron').text(peron18);
            $('#valueGst18').text(gst18total);
            //$('#12peron').text(peron18);
            $('#valueGst12').text(gst12total);
            //$('#5peron').text(peron18);
            $('#valueGst5').text(gst5total);
            $('#totalAmount').text(totalAmount);


            $('#subtotalSave').val(subTotal);
            $('#gst18').val(gst18total);
            $('#gst12').val(gst12total);
            $('#gst5').val(gst5total);
            $('#chargeAmount').val(totalAmount);


            var html =
                '<div class="row items_' + loop_item_count +
                '"><input type="hidden" class="form-control event_item_ids" value="" id="item_id" name="event_item_ids[]"><div class="col-md-3 input_wrap"><div class="mb-4"><label>Item</label><input type="text" id="item_name' +
                loop_item_count +
                '" class="form-control" placeholder="Item" name="item_name[]" onkeyup="searchProductData(event)">  <div id="searchResult' +
                loop_item_count +
                '" class="shadow phone-list"></div></div></div><div class="col-md-3 input_wrap"><div class="mb-4"><label>Prices</label><input type="text" id="item_price' +
                loop_item_count +
                '" class="form-control" placeholder="Prices" name="item_price[]"></div></div><div class="col-md-3 input_wrap"><div class="mb-4"><label>Qty</label><input type="number" class="form-control" id="item_qty' +
                loop_item_count +
                '" placeholder="Qty" value="1" name="item_qty[]"></div></div><div class="col-md-2 input_wrap"><div class="mb-4"><label>Tax</label><select name="item_tax[]" id="item_tax' +
                loop_item_count +
                '" class="form-select"><option value="18">18%</option><option value="12">12%</option><option value="5">5% </option><option value="0">0% </option></select></div></div><div class="col-md-1"><i class="fa fa-trash fs-4 btn btn-danger" onclick="remove_image_more(' +
                loop_item_count + ',0)" aria-hidden="true"></i></div></div>';


            jQuery('#item_box').append(html);


        }

        function calculate_items() {


            var item_name = $("input[name='item_name[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_price = $("input[name='item_price[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_qty = $("input[name='item_qty[]']")
                .map(function() {
                    return $(this).val();
                }).get();


            var item_tax = $("select[name='item_tax[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var subTotal = 0;

            var gst18total = 0;
            // var peron18 = 0;

            var gst12total = 0;
            // var peron12 = 0

            var gst5total = 0;
            //  var peron5 = 0
            var totalAmount = 0;

            for (i = 0; i < item_name.length; i++) {
                if (item_qty[i] == 0) {
                    var erroMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                    $('#errorMsg').html(erroMsg).fadeOut(9000);
                } else {

                    var rowtotal = 0;
                    var gst18 = 0;
                    var gst12 = 0;
                    var gst5 = 0;
                    var rowtotal = item_price[i] * item_qty[i];
                    if (item_tax[i] == 18) {

                        var gst18 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 12) {

                        var gst12 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 5) {

                        var gst5 = (rowtotal * item_tax[i]) / 100;

                    }
                }

                subTotal += parseFloat(rowtotal.toFixed(2));
                gst18total += parseFloat(gst18.toFixed(2));
                gst12total += parseFloat(gst12.toFixed(2));
                gst5total += parseFloat(gst5.toFixed(2));

            }

            var totalAmount = subTotal + gst18total + gst12total + gst5total;

            $('#subtotal').text(subTotal);

            //$('#18peron').text(peron18);
            $('#valueGst18').text(gst18total);
            //$('#12peron').text(peron18);
            $('#valueGst12').text(gst12total);
            //$('#5peron').text(peron18);
            $('#valueGst5').text(gst5total);
            $('#totalAmount').text(totalAmount);


            $('#subtotalSave').val(subTotal);
            $('#gst18').val(gst18total);
            $('#gst12').val(gst12total);
            $('#gst5').val(gst5total);
            $('#chargeAmount').val(totalAmount);

        }

        function remove_image_more(loop_image_count, item_id) {
            jQuery('.items_' + loop_image_count).remove();

            if (item_id > 0) {

                var baseUrl = "{{ URL::to('/') }}";
                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-event-item',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        selectedItems: item_id
                    },
                    success: function(response) {
                        toastr.success('Event Item deleted successfully');
                    },
                    error: function(xhr, status, error) {

                    }

                });
            }

            var item_name = $("input[name='item_name[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_price = $("input[name='item_price[]']")
                .map(function() {
                    return $(this).val();
                }).get();

            var item_qty = $("input[name='item_qty[]']")
                .map(function() {
                    return $(this).val();
                }).get();


            var item_tax = $("select[name='item_tax[]']")
                .map(function() {
                    return $(this).val();
                }).get();


            var subTotal = 0;

            var gst18total = 0;
            // var peron18 = 0;

            var gst12total = 0;
            // var peron12 = 0

            var gst5total = 0;
            //  var peron5 = 0
            var totalAmount = 0;
            for (i = 0; i < item_name.length; i++) {

                if (item_qty[i] == 0) {
                    var erroMsg = '<span class="alert alert-danger ml-5">Minimum Qty should be 1 or More than 1</span>';
                    $('#errorMsg').html(erroMsg).fadeOut(9000);
                } else {

                    var rowtotal = 0;
                    var gst18 = 0;
                    var gst12 = 0;
                    var gst5 = 0;
                    var rowtotal = item_price[i] * item_qty[i];
                    if (item_tax[i] == 18) {

                        var gst18 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 12) {

                        var gst12 = (rowtotal * item_tax[i]) / 100;

                    }
                    if (item_tax[i] == 5) {

                        var gst5 = (rowtotal * item_tax[i]) / 100;

                    }
                }

                subTotal += parseFloat(rowtotal.toFixed(2));
                gst18total += parseFloat(gst18.toFixed(2));
                gst12total += parseFloat(gst12.toFixed(2));
                gst5total += parseFloat(gst5.toFixed(2));

            }

            var totalAmount = subTotal + gst18total + gst12total + gst5total;

            $('#subtotal').text(subTotal);
            //$('#18peron').text(peron18);
            $('#valueGst18').text(gst18total);
            //$('#12peron').text(peron18);
            $('#valueGst12').text(gst12total);
            //$('#5peron').text(peron18);
            $('#valueGst5').text(gst5total);
            $('#totalAmount').text(totalAmount);

            $('#subtotalSave').val(subTotal);
            $('#gst18').val(gst18total);
            $('#gst12').val(gst12total);
            $('#gst5').val(gst5total);
            $('#chargeAmount').val(totalAmount);

        }
        function searchProductData(event) {
            var search = event.target.value;
            if (search.length > 0) {
                if (loop_item_count > 1) {
                    $('#searchResult' + loop_item_count).css("display", "block");
                } else {
                    $('#searchResult').css("display", "block");
                }


                $.ajax({

                    type: 'get',
                    url: '{{ route('getProduct') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        query: search
                    },

                    success: function(data) {
                        console.log(loop_item_count);
                        if (loop_item_count > 1) {
                            document.querySelector('#searchResult' + loop_item_count).innerHTML = '';
                            data.data.forEach(element => {
                                document.querySelector('#searchResult' + loop_item_count).innerHTML +=
                                    `<a class="text-dark borderSearch" href="javascript:void(0)" onclick="appendProductData(${element.id})"><p class="py-2 px-3">${element.product_name}</p></a>`;
                            });
                        } else {
                            document.querySelector('#searchResult').innerHTML = '';
                            data.data.forEach(element => {
                                document.querySelector('#searchResult').innerHTML +=
                                    `<a class="text-dark borderSearch" href="javascript:void(0)" onclick="appendProductData(${element.id})"><p class="py-2 px-3">${element.product_name}</p></a>`;
                            });
                        }

                    }
                });
            } else {
                $('#searchResult').css("display", "none");
            }
        }

        function appendProductData(id) {
            $.ajax({
                type: "get",
                url: '{{ route('productByID') }}',
                data: {
                    id: id
                },
                success: function(response) {
                    if (loop_item_count > 1) {
                        $('#item_name' + loop_item_count).val(response.product_name);
                        $('#item_price' + loop_item_count).val(response.product_price);
                        $('#item_qty' + loop_item_count).val(response.product_qty);
                        $('#item_tax' + loop_item_count).val(response.product_tax);
                        $('#searchResult' + loop_item_count).css("display", "none");
                    } else {
                        $('#item_name').val(response.product_name);
                        $('#item_price').val(response.product_price);
                        $('#item_qty').val(response.product_qty);
                        $('#item_tax').val(response.product_tax);
                        $('#searchResult').css("display", "none");
                    }

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
            console.log(datetime);
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

            // Submit the form if the data is valid
            form.submit();
        });
    </script>
</body>

</html>
