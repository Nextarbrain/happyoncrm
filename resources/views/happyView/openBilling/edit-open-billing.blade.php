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
                                                    <a class="nav-link active rounded" data-bs-toggle="tab" href="#paid" aria-selected="true" role="tab" style="pointer-events: none;">
                                                        <p class="heading">Open Billing</p>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                <form action="{{ url('/edit-open-billing-post') }}" method="post" id="my-form">
                                                    @csrf
                                                    <input type="hidden" value="{{ $open_billing->id }}" name="id">
                                                    @if (auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Branch <span>*</span></label>
                                                                <select name="branch_id" id="branchId" class="form-select" disabled>
                                                                    @foreach ($branches as $branch)
                                                                        <option value="{{ $branch['id'] }}" {{ isset($open_billing['branch_id']) && $open_billing['branch_id'] === $branch['id'] ? 'selected' : '' }}>{{ $branch['name'] }}</option>
                                                                    @endforeach

                                                                </select>
                                                                <input type="hidden" name="branch_id" value="{{ $open_billing['branch_id'] }}">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <input type="hidden" id="branchId" name="branch_id" value="{{ auth('admin')->user()->branch_id }}">
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap dark">
                                                                <label>Current Date</label>
                                                                <input type="date" class="form-control" placeholder="date" value="{{ $open_billing->created_at->format('Y-m-d') }}" id="date" required readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 input_wrap dark time">
                                                            <div class="mb-4">
                                                                <label>current Time</label>
                                                                <input type="time" class="form-control" placeholder="Time" id="time" value="{{ $open_billing->created_at->format('h:i') }}" required readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Billing Name <span>*</span></label>
                                                                <input type="text" class="form-control" placeholder="Billing Name" value="{{ $open_billing->bill_name }}" name="billing_name" required>
                                                                @error('billing_name')
                                                                    <span class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control" placeholder="Phone" value="{{ $open_billing->phone }}" name="phone" id="phone" pattern="[0-9]{10}" maxlength="10" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" id="item_box">
                                                            <h4><b>List Items</b></h4>
                                                            <br>
                                                            @php
                                                                $loop_count_num = 1;
                                                                $rowcount = count($open_billing_items);
                                                            @endphp
                                                            @foreach ($open_billing_items as $key => $item)
                                                                <div class="row items_{{ $loop_count_num }}">
                                                                    <input type="hidden" class="form-control open_billing_item_ids" value="{{ $item->id }}" id="item_id" name="open_billing_item_ids[]">
                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Item</label>
                                                                            <input type="text" class="form-control item_name" value="{{ $item->item_name }}" id="item_name_{{ $loop_count_num }}" placeholder="Item" name="item_name[]" onkeyup="searchProductData(event)" required>
                                                                            <div id="searchResult" class="shadow phone-list">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Prices</label>
                                                                            <input type="text" class="form-control item_price" value="{{ $item->item_price }}" id="item_price_{{ $loop_count_num }}" placeholder="Prices" name="item_price[]" oninput="validatePrice(this)">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Qty</label>
                                                                            <input type="number" class="form-control item_qty" id="item_qty_{{ $loop_count_num }}" value="{{ $item->item_qty }}" placeholder="Qty" name="item_qty[]" min="1" oninput="validity.valid||(value='');">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-2 input_wrap">
                                                                        <div class="mb-4">
                                                                            <label>Tax</label>
                                                                            @php
                                                                                $taxOptions = [18, 5, 0];
                                                                            @endphp
                                                                            <select name="item_tax[]" id="item_tax_{{ $loop_count_num }}" class="form-select item_tax">
                                                                                @foreach ($taxOptions as $tax)
                                                                                    <option value="{{ $tax }}" {{ $item->item_tax == $tax ? 'selected' : '' }}>
                                                                                        {{ $tax }}%
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-1">
                                                                        @if ($loop_count_num == 1)
                                                                            <i class="fa fa-plus fs-4 btn btn-primary" onclick="add_item_more()" style="background:#0A5F59;" aria-hidden="true"></i>
                                                                        @else
                                                                            </a><i class="fa fa-trash fs-4 btn btn-danger" onclick="remove_image_more({{ $loop_count_num }}, {{ $item->id }})" aria-hidden="true"></i></a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $loop_count_num++;
                                                                @endphp
                                                            @endforeach
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                                                <a href="javascript:void(0)" class="btn btn-primary" onclick="calculate_amount()" style="background:#0A5F59;">Calculate</a>
                                                                <div id="errorMsg"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>COUPON CODE</label>
                                                                <div class="d-flex">
                                                                    <input type="text" name="couponcode" class="form-control" id="couponCode" placeholder="Any Discount Code" value="{{ $open_billing->couponcode }}">
                                                                    <input type="hidden" name="couponvalue" id="couponValue" value="{{ $open_billing->couponvalue }}">
                                                                    <button type="button" id="couponCodebutton" class="btn btn-primary" onclick="calculate_amount()">Apply</button>
                                                                </diV>
                                                                <p id="couponmsg"></p>
                                                            </div>
                                                        </div>
                                                        <div class="total_wrap">
                                                            <div class="subtotal">
                                                                <p>Subtotal</p>
                                                                <p id="subtotal">{{ $open_billing->subtotal }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst1">
                                                                <p id="couponCodeShow">Coupon ({{ $open_billing->couponcode }})</p>
                                                                <p id="couponValueShow"> {{ $open_billing->couponvalue }}</p>
                                                            </div>
                                                            <hr>
                                                            <div class="gst" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst18Label">CGST @ 9%</p>
                                                                    <p id="cgst18Total">{{ round($open_billing->gst18 / 2, 2) }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst18Label">SGST @ 9%</p>
                                                                    <p id="sgst18Total">{{ round($open_billing->gst18 / 2, 2) }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>                                                            
                                                            {{-- <div class="gst1" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst12Label">CGST @ 6%</p>
                                                                    <p id="cgst12Total">{{ round($open_billing->gst12 / 2, 2) }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst12Label">SGST @ 6%</p>
                                                                    <p id="sgst12Total">{{ round($open_billing->gst12 / 2, 2) }}</p>
                                                                </div>
                                                            </div> --}}
                                                            <hr>                                                           
                                                            <div class="gst1" style="display: flex; flex-direction: column;">
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="cgst5Label">CGST @ 2.5%</p>
                                                                    <p id="cgst5Total">{{ round($open_billing->gst5 / 2, 2) }}</p>
                                                                </div>
                                                                <div style="display: flex; justify-content: space-between;">
                                                                    <p id="sgst5Label">SGST @ 2.5%</p>
                                                                    <p id="sgst5Total">{{ round($open_billing->gst5 / 2, 2) }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="amount">
                                                                <p>Total Amount</p>
                                                                <p id="totalAmount">{{ $open_billing->totalamount }}</p>
                                                                <input type="hidden" value="{{ $open_billing->totalamount }}" name="totalAmount" id="chargeAmount">
                                                                <input type="hidden" value="{{ $open_billing->subtotal }}" name="subtotal" id="subtotalSave">
                                                                <input type="hidden" value="{{ $open_billing->gst18 }}" name="gst18" id="gst18">
                                                                <input type="hidden" value="{{ $open_billing->gst12 }}" name="gst12" id="gst12">
                                                                <input type="hidden" value="{{ $open_billing->gst5 }}" name="gst5" id="gst5">
                                                            </div>
                                                            <br>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Payment Mode</label>
                                                                @php
                                                                    $paymentMethods = ['UPI', 'Cash', 'Debit/Credit'];
                                                                @endphp
                                                                <select name="payment_method" id="payment_method" class="form-select">
                                                                    @foreach ($paymentMethods as $method)
                                                                        <option value="{{ $method }}" {{ $open_billing->payment_method == $method ? 'selected' : '' }}>
                                                                            {{ $method }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="tearm_condition">
                                                            <input type="checkbox" id="term" name="term" value="" checked="checked">
                                                            <p>By Agreeing to the <b>Terms and Condition</b> of HappyOn
                                                            </p>
                                                        </div>
                                                        <div class="h_btn s_btn">
                                                            <button class="btn btn-primary">Update Bill</button>
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

    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    {{-- {{NEW CALCULATION FLOW...START}} --}}
    <script>
        function calculate_amount() {
            const items = getItemDetails();

            // check coupon code appied or not.
            const raw = $("#couponCode").val();
            const couponCode = raw ? raw.trim() : '';
            if (couponCode) {
                const baseUrl = "{{ URL::to('/') }}";
                $.ajax({
                    url: baseUrl + '/get-coupon',
                    type: 'GET',
                    data: {
                        option: $("#couponCode").val()
                    },
                    success: function(data) {
                        if (data.status == 400) {
                            $('#couponmsg').html(`<p class="text-danger">${data.msg}</p>`);
                            const totals = calculateTotals(items);
                            if (totals === false) return;
                            updateTotalsOnUI(totals);
                        } else {
                            $('#couponmsg').html("<p>Apply Coupon Successfull!</p>");
                            let discountTotal = 0;
                            if (data.discount_type === 'flat') {
                                discountTotal = data.discount;
                                const totals = calculateTotals(items, data);
                                if (totals === false) return;
                                updateTotalsOnUI(totals);
                            } else if (data.discount_type === 'percent') {
                                const totals = calculateCouponTotals(items, data);
                                if (totals === false) return;
                                discountTotal = totals.totalDiscount;
                                updateTotalsOnUI(totals, discountTotal, data);
                            }
                        }
                    }
                });
            } else {
                const totals = calculateTotals(items);
                if (totals === false) return;
                updateTotalsOnUI(totals);
            }
        }

        function getItemDetails() {
            return {
                item_name: $("input[name='item_name[]']").map(function() {
                    return $(this).val();
                }).get(),
                item_price: $("input[name='item_price[]']").map(function() {
                    return $(this).val();
                }).get(),
                item_qty: $("input[name='item_qty[]']").map(function() {
                    return $(this).val();
                }).get(),
                item_tax: $("select[name='item_tax[]']").map(function() {
                    return $(this).val();
                }).get()
            };
        }

        function calculateTotals({
            item_price,
            item_qty,
            item_tax
        }, couponData = null) {
            let subTotal = 0,
                gst18total = 0,
                gst12total = 0,
                gst5total = 0,
                totalDiscount = 0;

            for (let i = 0; i < item_price.length; i++) {
                if (item_qty[i] == 0 || item_price[i] == 0) {
                    $('#errorMsg').html('<span class="alert alert-danger">Minimum Qty/Price should be 1.</span>').fadeOut(8000);
                } else {
                    let qty = parseFloat(item_qty[i]) || 0;
                    let price = parseFloat(item_price[i]) || 0;
                    let taxRate = parseFloat(item_tax[i]) || 0;

                    let rowtotal = price * qty;
                    let gstAmt = 0;
                    let basicAmt = 0;

                    const taxRatesList = {
                        '18': 1.18,
                        '12': 1.12,
                        '5': 1.05,
                        '0': 1
                    };
                    if (taxRatesList.hasOwnProperty(taxRate)) {
                        basicAmt = rowtotal / taxRatesList[taxRate];
                        gstAmt = rowtotal - basicAmt;
                    }
                    subTotal += basicAmt;

                    if (taxRate === 18) gst18total += gstAmt;
                    else if (taxRate === 12) gst12total += gstAmt;
                    else if (taxRate === 5) gst5total += gstAmt;
                }
            }
            if (couponData !== null) {
                subTotal = subTotal - couponData.discount;
            }
            return {
                subTotal,
                gst18total,
                gst12total,
                gst5total,
                totalDiscount
            };
        }

        function calculateCouponTotals({
            item_price,
            item_qty,
            item_tax
        }, data) {
            let subTotal = 0,
                gst18total = 0,
                gst12total = 0,
                gst5total = 0,
                totalDiscount = 0,
                discountRate = data.discount;
            for (let i = 0; i < item_price.length; i++) {
                if (item_qty[i] == 0 || item_price[i] == 0) {
                    $('#errorMsg').html('<span class="alert alert-danger">Minimum Qty/Price should be 1.</span>').fadeOut(8000);
                } else {
                    let qty = parseFloat(item_qty[i]) || 0;
                    let price = parseFloat(item_price[i]) || 0;
                    let taxRate = parseFloat(item_tax[i]) || 0;

                    let rowtotal = price * qty;
                    let gstAmt = 0;
                    let basicAmt = 0;
                    let discountAmt = 0;

                    const taxRatesList = {
                        '18': 1.18,
                        '12': 1.12,
                        '5': 1.05,
                        '0': 1
                    };
                    if (taxRatesList.hasOwnProperty(taxRate)) {
                        basicAmt = rowtotal / taxRatesList[taxRate];
                        discountAmt = (basicAmt * discountRate) / 100;
                        basicAmt = basicAmt - discountAmt;
                        gstAmt = (basicAmt * taxRate) / 100;
                    }
                    subTotal += basicAmt;
                    totalDiscount += discountAmt;
                    if (taxRate === 18) gst18total += gstAmt;
                    else if (taxRate === 12) gst12total += gstAmt;
                    else if (taxRate === 5) gst5total += gstAmt;
                }
            }
            return {
                subTotal,
                gst18total,
                gst12total,
                gst5total,
                totalDiscount
            };
        }

        function updateTotalsOnUI({
            subTotal,
            gst18total,
            gst12total,
            gst5total,
            totalDiscount
        }, discountTotal = 0, couponData = null) {
            const totalAmount = subTotal + gst18total + gst12total + gst5total;

            $('#subtotal').text(subTotal.toFixed(2));
            $('#subtotalSave').val(subTotal.toFixed(2));

            updateGST(gst18total, 18);
            updateGST(gst12total, 12);
            updateGST(gst5total, 5);

            $('#totalAmount').text(totalAmount.toFixed(2));
            $('#chargeAmount').val(totalAmount.toFixed(2));

            if (couponData) {
                $('#couponValue').val(discountTotal.toFixed(2));
                $('#couponCodeShow').html(`Coupon Code (${couponData.coupon_name})`);
                $('#couponValueShow').html(discountTotal.toFixed(2));
            }
        }
        function updateGST(gstTotal, gstRate) {
            const halfGst = (gstTotal / 2).toFixed(2);
            $(`#cgst${gstRate}Total`).text(halfGst);
            $(`#sgst${gstRate}Total`).text(halfGst);
            $(`#gst${gstRate}`).val(gstTotal.toFixed(2));
        }
    </script>

    {{-- {{NEW CALCULATION FLOW...END}} --}}
    <script>
        let loop_item_count = {{ $rowcount }};

        function add_item_more() {
            loop_item_count++;
            var html =
                '<div class="row items_' + loop_item_count +
                '"><input type="hidden" class="form-control open_billing_item_ids" value="" id="item_id" name="open_billing_item_ids[]"><div class="col-md-3 input_wrap"><div class="mb-4"><label>Item</label><input type="text" id="item_name' +
                loop_item_count +
                '" class="form-control" placeholder="Item" name="item_name[]" onkeyup="searchProductData(event)" required> <div id="searchResult' +
                loop_item_count +
                '" class="shadow phone-list"></div></div></div><div class="col-md-3 input_wrap"><div class="mb-4"><label>Prices</label><input type="text" id="item_price' +
                loop_item_count +
                '" class="form-control" placeholder="Prices" name="item_price[]" oninput="validatePrice(this)"></div></div><div class="col-md-3 input_wrap"><div class="mb-4"><label>Qty</label><input type="number" class="form-control" id="item_qty' +
                loop_item_count +
                '" placeholder="Qty" value="1" min="1" name="item_qty[]" oninput="validity.valid || (value = \'\');"></div></div><div class="col-md-2 input_wrap"><div class="mb-4"><label>Tax</label><select name="item_tax[]" id="item_tax' +
                loop_item_count +
                '" class="form-select"><option value="18">18%</option><option value="5">5% </option><option value="0">0% </option></select></div></div><div class="col-md-1"><i class="fa fa-trash fs-4 btn btn-danger" onclick="remove_image_more(' +
                loop_item_count + ',0)" aria-hidden="true"></i></div></div>';
            jQuery('#item_box').append(html);
        }

        function validatePrice(input) {
            // Remove non-numeric and non-dot characters
            input.value = input.value.replace(/[^0-9.]/g, '');

            // Convert to float and check value
            const value = parseFloat(input.value);
            if (value <= 0 || isNaN(value)) {
                input.setCustomValidity("Price must be greater than 0");
                input.reportValidity();
            } else {
                input.setCustomValidity("");
            }
        }

        function searchProductData(event) {
            var search = event.target.value;
            var branchId = $('#branchId').val();
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
                        query: search,
                        branchId: branchId
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

        function remove_image_more(loop_image_count, item_id) {
            jQuery('.items_' + loop_image_count).remove();
            if (item_id > 0) {
                var baseUrl = "{{ URL::to('/') }}";
                $.ajax({
                    type: 'Delete',
                    url: baseUrl + '/delete-open-billing-item',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        selectedItems: item_id
                    },
                    success: function(response) {
                        toastr.success('Open Billing Item deleted successfully');
                    },
                    error: function(xhr, status, error) {}
                });
            }
            calculate_amount();
        }
        //var today1 =  new Date().toISOString().substr(0,10);
        var today = new Date();
        var day = ("0" + today.getDate()).slice(-2);
        var month = ("0" + (today.getMonth() + 1)).slice(-2);
        var year = today.getFullYear();
        var formattedDate = year + '-' + month + '-' + day;
        // document.getElementById('date').value = formattedDate;

        // Current Time
        var dateInput = document.getElementById('date');
        var timeInput = document.getElementById('time');
        var now = new Date();
        var hours = now.getHours().toString().padStart(2, '0');
        var minutes = now.getMinutes().toString().padStart(2, '0');
        var timeString = hours + ":" + minutes;
        // timeInput.value = timeString;

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
    </script>
</body>

</html>
