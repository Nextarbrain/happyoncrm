@include('happyView.includes.head')

<body>

    <!--wrapper-->
    <div class="wrapper">
        @include('happyView.includes.sidebar')
        <!--start header -->
        <header>
            <div id="message_container"></div>
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
        <div class="page-wrapper">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="dashboard_left">
                            <!-- data table  -->
                            <div class="container">
                                <div class="row">
                                    @if (can('admin-hours'))
                                        <div class="col-md-6">
                                            <div>
                                                <div id="choosetheme" class="container tab-pane active" role="tabpanel">
                                                    <br>
                                                    <div class="tab_wrap_theme">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link left active" data-bs-toggle="tab"
                                                                    href="#paid" aria-selected="true" role="tab">
                                                                    <p class="heading">WeekDays </p>
                                                                </a>
                                                            </li>
                                                            <li class="nav-item" role="presentation">
                                                                <a class="nav-link right" data-bs-toggle="tab"
                                                                    href="#free" aria-selected="false" role="tab"
                                                                    tabindex="-1">
                                                                    <p class="heading">Weekend Hours</p>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="tab-content">
                                                        <div id="paid" class="container tab-pane active show"
                                                            role="tabpanel"><br>
                                                            <div class="data_table admin_table table-responsive">
                                                                @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                                    @if (can('admin-hours-edit'))
                                                                        <div class="count" id="day_price_edit"
                                                                            style="text-align: right">
                                                                            <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                                <div class="modal fade" id="editDayPriceModal"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="editDayPriceLabel"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="editDayPriceLabel">Edit Weekdays
                                                                                    Prices</h5>
                                                                                <button type="button" class="close"
                                                                                    onClick="modalClose();"
                                                                                    aria-label="Close">
                                                                                    <span
                                                                                        aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form
                                                                                action="{{ url('/editDayPricePost') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <div class="modal-body">
                                                                                    <label for="Monday">Monday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="monday"
                                                                                        placeholder="Monday"
                                                                                        name="monday" required>
                                                                                    <label
                                                                                        for="Tuesday">Tuesday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="tuesday"
                                                                                        placeholder="Tuesday"
                                                                                        name="tuesday" required>
                                                                                    <label
                                                                                        for="Wednesday">Wednesday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="wednesday"
                                                                                        placeholder="Wednesday"
                                                                                        name="wednesday" required>
                                                                                    <label
                                                                                        for="Thursday">Thursday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="thursday"
                                                                                        placeholder="Thursday"
                                                                                        name="thursday" required>

                                                                                    <input type="hidden"
                                                                                        id="dayPriceId"
                                                                                        name="dayPriceId">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        onClick="modalClose();">Cancel</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <table class="table table-striped text-center"
                                                                    style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><input type="checkbox"
                                                                                    onclick="checkWeekendAll(this)"></th>
                                                                            <th>Hour</th>
                                                                            <th>Monday</th>
                                                                            <th>Tue</th>
                                                                            <th>Wed</th>
                                                                            <th>Thru</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($hourPlan as $hourplans)
                                                                            <tr>
                                                                                <td><input type="checkbox"
                                                                                        name="day_price[]"
                                                                                        class="day_price_checkbox"
                                                                                        value="{{ $hourplans->id }}"
                                                                                        data-item-id="{{ $hourplans->id }}">
                                                                                </td>
                                                                                <td>{{ $hourplans->Hour }}</td>
                                                                                <td>{{ $hourplans->monday }}</td>
                                                                                <td>{{ $hourplans->tuesday }}</td>
                                                                                <td>{{ $hourplans->wednesday }}</td>
                                                                                <td>{{ $hourplans->thursday }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div id="free" class="container tab-pane fade"
                                                            role="tabpanel"><br>
                                                            <div class="data_table admin_table table-responsive">
                                                                <div class="count" id="weekend_edit"
                                                                    style="text-align: right">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                </div>
                                                                <div class="modal fade" id="editWeekendModal"
                                                                    tabindex="-1" role="dialog"
                                                                    aria-labelledby="editWeekendLabel"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog" role="document">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="editWeekendLabel">Edit Weekend
                                                                                    Prices</h5>
                                                                                <button type="button" class="close"
                                                                                    onClick="modalClose();"
                                                                                    aria-label="Close">
                                                                                    <span
                                                                                        aria-hidden="true">&times;</span>
                                                                                </button>
                                                                            </div>
                                                                            <form
                                                                                action="{{ url('/editWeekendPost') }}"
                                                                                method="post">
                                                                                @csrf
                                                                                <div class="modal-body">
                                                                                    <label
                                                                                        for="Friday">Friday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="friday"
                                                                                        placeholder="Friday"
                                                                                        name="friday" required>
                                                                                    <label
                                                                                        for="Saturday">Saturday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="saturday"
                                                                                        placeholder="Saturday"
                                                                                        name="saturday" required>
                                                                                    <label
                                                                                        for="Sunday">Sunday</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="sunday"
                                                                                        placeholder="Sunday"
                                                                                        name="sunday" required>

                                                                                    <input type="hidden"
                                                                                        id="weekendId"
                                                                                        name="weekendId">
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        onClick="modalClose();">Cancel</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-primary">Submit</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <table class="table table-striped text-center"
                                                                    style="width:100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><input type="checkbox"
                                                                                    onclick="checkAll(this)"></th>
                                                                            <th>Hour</th>
                                                                            <th>Friday</th>
                                                                            <th>Sat</th>
                                                                            <th>Sunday</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($hourPlan as $hourpln)
                                                                            <tr>
                                                                                <td><input type="checkbox"
                                                                                        name="weekend[]"
                                                                                        class="weekend_checkbox"
                                                                                        value="{{ $hourpln->id }}"
                                                                                        data-item-id="{{ $hourpln->id }}">
                                                                                </td>
                                                                                <td>{{ $hourpln->Hour }}</td>
                                                                                <td>{{ $hourpln->friday }}</td>
                                                                                <td>{{ $hourpln->saturday }}</td>
                                                                                <td>{{ $hourpln->sunday }}</td>
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
                                    @endif
                                    @if(can('admin-membership'))
                                    <div class="col-md-6">
                                        <div class="data_table admin_table table-responsive">
                                            <div class="table_header">
                                                <div class="a_add_btn">
                                                @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                    @if(can('admin-membership-add'))
                                                    <button type="button" class="btn" data-toggle="modal"
                                                        data-target="#membershipModal">Add Membership Plan +</button>
                                                    @endif
                                                @endif
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="membershipModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="membershipModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="membershipModalLabel">
                                                                        Add New Membership Plan</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/membershipPostData') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <label for="planModalForm">Plan</label>
                                                                        <input type="text" class="form-control"
                                                                            id="planModalForm" placeholder="Plan"
                                                                            name="plan">
                                                                        <label for="priceModalForm">Price</label>
                                                                        <input type="number" class="form-control"
                                                                            id="priceModalForm" placeholder="Price"
                                                                            name="price">
                                                                        <label for="creditModalForm">Entry</label>
                                                                        <input type="number" class="form-control"
                                                                            id="creditModalForm" placeholder="Credit"
                                                                            name="credit">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                 @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                    @if(can('admin-membership-delete'))
                                                    <div class="count" id="membership_div">
                                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                    </div>
                                                    @endif
                                                @endif
                                                 @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                    @if(can('admin-membership-edit'))
                                                    <div class="count" id="membership_edit">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </div>
                                                    @endif
                                                @endif
                                                <div class="modal fade" id="editMemberModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editMemberModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editMemberModalLabel">Edit
                                                                    Membership Plan</h5>
                                                                <button type="button" class="close"
                                                                    onClick="modalClose();" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ url('/editMemberPost') }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <label for="editProductName">Plan</label>
                                                                    <input type="text" class="form-control"
                                                                        id="editPlanName" placeholder="Plan Name"
                                                                        required readonly>
                                                                    <label for="editpriceModalForm">Price</label>
                                                                    <input type="number" class="form-control"
                                                                        id="editPlanPrice" placeholder="Price"
                                                                        name="editPlanPrice" step="0.01" required>
                                                                    <label for="editquantityModalForm">Entry</label>
                                                                    <input type="number" class="form-control"
                                                                        id="editPlanEntry" placeholder="Entry"
                                                                        name="editPlanEntry" required>
                                                                    <input type="hidden" id="memberId"
                                                                        name="memberId">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        onClick="modalClose();">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onclick="checkMemberShipAll(this)"></th>
                                                        <th>Plan</th>
                                                        <th>Price</th>
                                                        <th>Entry</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($membership as $memberships)
                                                        <tr>
                                                            <td><input type="checkbox" name="membership[]"
                                                                    class="membership_checkbox"
                                                                    value="{{ $memberships->id }}"
                                                                    data-item-id="{{ $memberships->id }}"></td>
                                                            <td>{{ $memberships->plan_name }}</td>
                                                            <td>{{ $memberships->price }}</td>
                                                            <td>{{ $memberships->credits }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">

                                    @if(can('admin-products'))
                                    <div class="col-md-6">
                                        <div class="data_table admin_table table-responsive">
                                            <div class="table_header">
                                                <div class="a_add_btn">
                                                    @if(can('admin-products-add'))
                                                    <button type="button" class="btn" data-toggle="modal"
                                                        data-target="#productModal">ADD Products +</button>
                                                    @endif
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="productModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="productModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="productModalLabel">Add
                                                                        New Product</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/productPostData') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <label>Branch <span style="color: red">*</span></label>
                                                                        <select name="branch_id" id="addProductBranchId"
                                                                            class="form-select" required>
                                                                            @foreach ($branches as $branch)
                                                                                <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <label for="productNameModalForm">Product
                                                                            Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="productNameModalForm"
                                                                            placeholder="Extra Time, Adult, Socks"
                                                                            name="product_name" required>
                                                                        <label for="priceModalForm">Price</label>
                                                                        <input type="text" class="form-control"
                                                                            id="priceModalForm" placeholder="Price"
                                                                            name="price" required>
                                                                        <label for="quantityModalForm">Qty</label>
                                                                        <input type="number" class="form-control"
                                                                            id="quantityModalForm" placeholder="qty"
                                                                            name="qty" required>
                                                                        <label for="taxModalForm">Tax</label>
                                                                        <input type="number" class="form-control"
                                                                            id="taxModalForm" placeholder="Tax"
                                                                            name="tax">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(can('admin-products-delete'))
                                                <div class="count" id="product_div">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </div>
                                                @endif
                                                @if(can('admin-products-edit'))
                                                <div class="count" id="product_edit">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </div>
                                                @endif
                                                <div class="modal fade" id="editproductModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editproductModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editproductModalLabel">
                                                                    Edit Product</h5>
                                                                <button type="button" class="close"
                                                                    onClick="modalClose();" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ url('/editProductPost') }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <label>Branch <span style="color: red">*</span></label>
                                                                    <select name="branch_id" id="editProductBranchId"
                                                                        class="form-select" required>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="editProductName">Product Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="editProductName"
                                                                        placeholder="Product Name" required readonly>
                                                                    <label for="editpriceModalForm">Price</label>
                                                                    <input type="number" class="form-control"
                                                                        id="editpriceModalForm" placeholder="Price"
                                                                        name="editprice" step="0.01" required>
                                                                    <label for="editquantityModalForm">Qty</label>
                                                                    <input type="number" class="form-control"
                                                                        id="editquantityModalForm" placeholder="qty"
                                                                        name="editqty" required>
                                                                    <label for="edittaxModalForm">Tax</label>
                                                                    <input type="number" class="form-control"
                                                                        id="edittaxModalForm" placeholder="editTax"
                                                                        name="tax">
                                                                    <input type="hidden" id="productId"
                                                                        name="productId">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        onClick="modalClose();">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onclick="checkProductAll(this)"></th>
                                                        <th>Product Name</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Tax</th>
                                                        <th>Branch</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product as $productList)
                                                        <tr>
                                                            <td><input type="checkbox" name="product[]"
                                                                    class="product_checkbox"
                                                                    value="{{ $productList->id }}"
                                                                    data-item-id="{{ $productList->id }}"></td>
                                                            <td>{{ $productList->product_name }}</td>
                                                            <td>{{ $productList->product_price }}</td>
                                                            <td>{{ $productList->product_qty }}</td>
                                                            <td>{{ $productList->product_tax }} %</td>
                                                            <td>{{ optional($productList['branch'])->name ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                    @if(can('admin-coupon'))
                                    <div class="col-md-6">
                                        <div class="data_table admin_table table-responsive">
                                            <div class="table_header">
                                                <div class="a_add_btn">
                                                    @if(can('admin-coupon-add'))
                                                    <button type="button" class="btn" data-toggle="modal"
                                                        data-target="#couponModal">Add New Coupon +</button>
                                                    @endif
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="couponModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="couponModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="couponModalLabel">Add
                                                                        New Coupon</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/couponPostData') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <label>Branch <span style="color: red">*</span></label>
                                                                        <select name="branch_id" id="addCoupanBranchId"
                                                                            class="form-select" required>
                                                                            @foreach ($branches as $branch)
                                                                                <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <label for="couponModalFormName">Coupon
                                                                            Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="couponModalFormName"
                                                                            placeholder="Coupon Name" name="name"
                                                                            required>
                                                                        <label for="couponModalFormType">Discount
                                                                            Type</label>
                                                                        <select name="type" class="form-control"
                                                                            id="couponModalFormType" required>
                                                                            <option value="">Discount Type
                                                                            </option>
                                                                            <option value="flat">Flat</option>
                                                                            <option value="percent">Percent</option>
                                                                        </select>

                                                                        <label
                                                                            for="couponModalFormDiscount">Discount</label>
                                                                        <input type="number" class="form-control"
                                                                            id="couponModalFormDiscount"
                                                                            placeholder="Discount" name="discount"
                                                                            required>
                                                                        <label for="couponModalFormStart">Start
                                                                            Date</label>
                                                                        <input type="date" class="form-control"
                                                                            id="couponModalFormStart"
                                                                            placeholder="Start Date"
                                                                            name="start_date">
                                                                        <label for="couponModalFormValid">Valid
                                                                            Till</label>
                                                                        <input type="date" class="form-control"
                                                                            id="couponModalFormValid"
                                                                            placeholder="Valid Till"
                                                                            name="valid_till">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if(can('admin-coupon-delete'))
                                                <div class="count" id="coupon_div">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </div>
                                                @endif
                                                @if(can('admin-coupon-edit'))
                                                <div class="count" id="coupon_edit">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </div>
                                                @endif
                                                <div class="modal fade" id="editCouponModal" tabindex="-1"
                                                    role="dialog" aria-labelledby="editCouponModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editCouponModalLabel">Edit
                                                                    Coupon</h5>
                                                                <button type="button" class="close"
                                                                    onClick="modalClose();" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form action="{{ url('/editCouponPost') }}"
                                                                method="post">
                                                                @csrf
                                                                <div class="modal-body">
                                                                    <label>Branch <span style="color: red">*</span></label>
                                                                    <select name="branch_id" id="editCoupanBranchId"
                                                                        class="form-select" required>
                                                                        @foreach ($branches as $branch)
                                                                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="couponName">Coupon Name</label>
                                                                    <input type="text" class="form-control"
                                                                        id="couponName" placeholder="Coupon Name"
                                                                        name="couponName" required>
                                                                    <label for="couponType">Discount Type</label>

                                                                    <select name="couponType" class="form-control"
                                                                        id="couponType" required>
                                                                        <option value="">Discount Type</option>
                                                                        <option value="flat">Flat</option>
                                                                        <option value="percent">Percent</option>
                                                                    </select>

                                                                    <label for="couponDiscount">Discount</label>
                                                                    <input type="number" class="form-control"
                                                                        id="couponDiscount" placeholder="Discount"
                                                                        name="couponDiscount" required>
                                                                    <label for="couponStart">Start Date</label>
                                                                    <input type="text" class="form-control"
                                                                        id="couponStart" placeholder="Start Date"
                                                                        name="couponStart">
                                                                    <label for="couponValid">Valid Till</label>
                                                                    <input type="text" class="form-control"
                                                                        id="couponValid" placeholder="Valid Till"
                                                                        name="couponValid">
                                                                    <input type="hidden" id="couponId"
                                                                        name="couponId">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        onClick="modalClose();">Cancel</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="table table-striped text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onclick="checkCoupanAll(this)"></th>
                                                        <th>Coupon Name</th>
                                                        <th>Discount Type</th>
                                                        <th>Discount</th>
                                                        <th>Coupon Use</th>
                                                        <th>Start From</th>
                                                        <th>Valid Till</th>
                                                        <th>Branch</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($coupon as $coupons)
                                                        <tr>
                                                            <td><input type="checkbox" name="coupon[]"
                                                                    class="coupon_checkbox"
                                                                    value="{{ $coupons->id }}"
                                                                    data-item-id="{{ $coupons->id }}"></td>
                                                            <td>{{ $coupons->coupon_name }}</td>
                                                            <td>{{ $coupons->discount_type }}</td>
                                                            <td>{{ $coupons->discount }}</td>
                                                            <td>{{ $coupons->coupon_count }}</td>
                                                            <td>{{ $coupons->start_from }} </td>
                                                            <td>{{ $coupons->valid_till }}</td>
                                                            <td>{{ optional($coupons['branch'])->name ?? '' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-6 d-none">
                                        <div class="data_table admin_table table-responsive">
                                            <div class="table_header">
                                                <div class="a_add_btn">
                                                    <button type="button" class="btn" data-toggle="modal"
                                                        data-target="#userModal">Add New User +</button>
                                                    <!-- Modal -->
                                                    <div class="modal fade" id="userModal" tabindex="-1"
                                                        role="dialog" aria-labelledby="userModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="userModalLabel">Add
                                                                        New User</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <form action="{{ url('/userPostData') }}"
                                                                    method="post">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <label for="userModalFormName">Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="userModalFormName" placeholder="Name"
                                                                            name="name">
                                                                        <label for="userNameModalForm">User
                                                                            Name</label>
                                                                        <input type="text" class="form-control"
                                                                            id="userNameModalForm"
                                                                            placeholder="User Name" name="user_name">
                                                                        <label for="assignModalForm">Assign</label>
                                                                        <input type="text" class="form-control"
                                                                            id="assignModalForm" placeholder="Assign"
                                                                            name="assign">
                                                                        <label for="accessModalForm">Access</label>
                                                                        <input type="text" class="form-control"
                                                                            id="accessModalForm" placeholder="Access"
                                                                            name="access">
                                                                        <input type="hidden" class="form-control"
                                                                            id="roleModalForm" name="role"
                                                                            value="0">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Cancel</button>
                                                                        <button type="submit"
                                                                            class="btn btn-primary">Submit</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="count" id="user_div">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                            <table class="table table-striped text-center" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" onclick="checkAll(this)"></th>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Assign</th>
                                                        <th>Access</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        <tr>
                                                            <td><input type="checkbox" name="user[]"
                                                                    class="user_checkbox" value="{{ $user->id }}"
                                                                    data-item-id="{{ $user->id }}"></td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->username }}</td>
                                                            <td>{{ $user->assign }}</td>
                                                            <td>{{ $user->acess }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{--
										<div class="col-md-6">
										<div class="data_table admin_table table-responsive">
											<div class="table_header">
												<div class="a_add_btn">
													<button type="button" class="btn" data-toggle="modal" data-target="#eventItemModal">ADD Event Item  +</button>
													<!-- Modal -->
													<div class="modal fade" id="eventItemModal" tabindex="-1" role="dialog" aria-labelledby="eventItemModalLabel" aria-hidden="true">
														<div class="modal-dialog" role="document">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title" id="eventItemModalLabel">Add Event Item</h5>
																	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																	<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<form action="{{url('/eventItemPostData')}}" method="post">
																	@csrf
																	<div class="modal-body">
																		<label for="eventItemNameModalForm">Event Item Name</label>
																		<input type="text" class="form-control" id="eventItemNameModalForm" placeholder="Event Item Name" name="item_name" required>
																		<label for="priceModalForm">Price</label>
																		<input type="number" class="form-control" id="priceModalForm" placeholder="Price" name="price" required>
																		<label for="quantityModalForm">Qty</label>
																		<input type="number" class="form-control" id="quantityModalForm" placeholder="qty" name="qty" required>
																		<label for="taxModalForm">Tax</label>
																		<input type="number" class="form-control" id="taxModalForm" placeholder="Tax" name="tax">
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
																		<button type="submit" class="btn btn-primary">Submit</button>
																	</div>
																</form>
															</div>
														</div>
													</div>
												</div>
												<div class="count" id="eventitem_div">
													<i class="fa fa-trash-o" aria-hidden="true"></i>
												</div>
												<div class="count" id="eventitem_edit">
													<i class="fa fa-pencil" aria-hidden="true"></i>
												</div>
												<div class="modal fade" id="editeventItemModal" tabindex="-1" role="dialog" aria-labelledby="editeventItemModalLabel" aria-hidden="true">
													<div class="modal-dialog" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="editeventItemModalLabel">Edit event item</h5>
																<button type="button" class="close" onClick="modalClose();" aria-label="Close">
																<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<form action="{{url('/editEventItemPost')}}" method="post">
																@csrf
																<div class="modal-body">
																	<label for="editeventItemName">eventItem Name</label>
																	<input type="text" class="form-control" id="editeventItemName" placeholder="Event Item Name" required readonly>
																	<label for="editpriceModalForm">Price</label>
																	<input type="number" class="form-control" id="editeventItempriceModalForm" placeholder="Price" name="editprice" step="0.01" required>
																	<label for="editquantityModalForm">Qty</label>
																	<input type="number" class="form-control" id="editeventItemquantityModalForm" placeholder="qty" name="editqty" required>
																	<label for="edittaxModalForm">Tax</label>
																	<input type="number" class="form-control" id="editeventItemtaxModalForm" placeholder="editTax" name="tax">
																	<input type="hidden" id="eventItemId" name="eventItemId">
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-secondary" onClick="modalClose();">Cancel</button>
																	<button type="submit" class="btn btn-primary">Submit</button>
																</div>
															</form>
														</div>
													</div>
												</div>
											</div>
											<table  class="table table-striped text-center" style="width:100%">
												<thead>
													<tr>
														<th><input type="checkbox" onclick="checkAll(this)"></th>
														<th>Event Item Name</th>
														<th>Price</th>
														<th>Qty</th>
														<th>Tax</th>
													</tr>
												</thead>
												<tbody>
												     @foreach ($eventitem as $eventItemList)
													<tr>
														<td><input type="checkbox" name="eventitem[]" class="eventitem_checkbox" value="{{$eventItemList->id}}" data-item-id="{{$eventItemList->id}}"></td>
														<td>{{$eventItemList->item_name}}</td>
														<td>{{$eventItemList->item_price}}</td>
														<td>{{$eventItemList->item_qty}}</td>
														<td>{{$eventItemList->item_tax}} %</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
									--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <!--end page wrapper -->
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <script>
                function checkCoupanAll(bx) {
                    var cbs = document.getElementsByClassName('coupon_checkbox');
                    for (var i = 0; i < cbs.length; i++) {
                        if (cbs[i].type == 'checkbox') {
                            cbs[i].checked = bx.checked;
                        }
                    }
                }

                function checkProductAll(bx) {
                    var cbs = document.getElementsByClassName('product_checkbox');
                    for (var i = 0; i < cbs.length; i++) {
                        if (cbs[i].type == 'checkbox') {
                            cbs[i].checked = bx.checked;
                        }
                    }
                }

                function checkMemberShipAll(bx) {
                    var cbs = document.getElementsByClassName('membership_checkbox');
                    for (var i = 0; i < cbs.length; i++) {
                        if (cbs[i].type == 'checkbox') {
                            cbs[i].checked = bx.checked;
                        }
                    }
                }



                function checkWeekendAll(bx) {
                    // var cbs = document.getElementsByClassName('day_price_checkbox');
                    // for (var i = 0; i < cbs.length; i++) {
                    //     if (cbs[i].type == 'checkbox') {
                    //         cbs[i].checked = bx.checked;
                    //     }
                    // }
                }


        </script>

    </div>
    <!--end wrapper-->


    @include('happyView.includes.footer')
