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
                            @if (session()->has(['msg', 'class']))
                                <div class="alert alert-{{ session()->get('class') }} alert-dismissible fade show"
                                    role="alert">
                                    {!! session()->get('msg') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Trashed Items</li>
                                </ol>
                            </nav>
                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Users</h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Username</th>
                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->id }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ parseDateInHuman($user->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $user->id, 'model' => 'User'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $user->id, 'model' => 'User'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>




                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Membership
                                    Plan</h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Plan Name</th>
                                                    <th>price </th>
                                                    <th>Credit</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mps as $mp)
                                                    <tr>
                                                        <td>{{ $mp->id }}</td>
                                                        <td>{{ $mp->plan_name }}</td>
                                                        <td>{{ $mp->price }}</td>
                                                        <td>{{ $mp->credits }}</td>
                                                        <td>{{ parseDateInHuman($mp->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $mp->id, 'model' => 'MembershipPlan'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $mp->id, 'model' => 'MembershipPlan'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Membership
                                    History</h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Use Credit</th>
                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mhs as $mh)
                                                    <tr>
                                                        <td>{{ $mh->id }}</td>
                                                        <td>{{ $mh->singleUser->name }}</td>
                                                        <td>{{ $mh->singleUser->email }}</td>
                                                        <td>{{ $mh->use_credit }}</td>
                                                        <td>{{ parseDateInHuman($mh->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $mh->id, 'model' => 'MembershipHistory'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $mh->id, 'model' => 'MembershipHistory'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Membership Buy
                                </h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Plan</th>
                                                    <th>Total Credit</th>
                                                    <th>Credit Left</th>
                                                    <th>Amount</th>
                                                    <th>Payment Method</th>
                                                    <th>Date</th>
                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mbs as $mb)
                                                    <tr>
                                                        <td>{{ $mb->id }}</td>
                                                        <td>{{ $mb->singleUser->name }}</td>
                                                        <td>{{ $mb->singleUser->email }}</td>
                                                        <td>{{ $mb->plan }}</td>
                                                        <td>{{ $mb->total_credit }}</td>
                                                        <td>{{ $mb->credit_left }}</td>
                                                        <td>{{ $mb->amount }}</td>
                                                        <td>{{ $mb->payment_method }}</td>
                                                        <td>{{ $mb->date }}</td>
                                                        <td>{{ parseDateInHuman($mb->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $mb->id, 'model' => 'MembershipBuy'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $mb->id, 'model' => 'MembershipBuy'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Event Item
                                </h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Tax</th>

                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($eventItems as $eventItem)
                                                    <tr>
                                                        <td>{{ $eventItem->id }}</td>
                                                        <td>{{ $eventItem->item_name }}</td>
                                                        <td>{{ $eventItem->item_price }}</td>
                                                        <td>{{ $eventItem->item_qty }}</td>
                                                        <td>{{ $eventItem->item_tax }}</td>
                                                        <td>{{ parseDateInHuman($eventItem->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $eventItem->id, 'model' => 'EventItem'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $eventItem->id, 'model' => 'EventItem'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Event Booking
                                </h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Booking Date</th>
                                                    <th>Phone</th>
                                                    <th>Contact Person</th>
                                                    <th>Kid Name</th>
                                                    <th>Kid Dob</th>
                                                    <th>Status</th>
                                                    <th>Amount</th>

                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($events as $event)
                                                    <tr>
                                                        <td>{{ $event->id }}</td>
                                                        <td>{{ $event->bill_name }}</td>
                                                        <td>{{ $event->booking_date }}</td>
                                                        <td>{{ $event->phone }}</td>
                                                        <td>{{ $event->contact_person }}</td>
                                                        <td>{{ $event->kid_name }}</td>
                                                        <td>{{ $event->kid_dob }}</td>
                                                        <td>{{ $event->status }}</td>
                                                        <td>{{ $event->totalamount }}</td>
                                                        <td>{{ parseDateInHuman($event->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $event->id, 'model' => 'EventBooking'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $event->id, 'model' => 'EventBooking'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>



                                    </div>
                                </div>
                            </div>
                            <div class="container">
                                <h6 class="my-2 font-weight-bold fw-bold bg-primary text-light px-4 py-2">Open Billing
                                </h6>
                                <div class="row table-responsive">
                                    <div class="data_table">
                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Bill Name</th>
                                                    <th>Phone</th>
                                                    <th>payment Method</th>
                                                    <th>GST 18</th>
                                                    <th>GST 5</th>
                                                    <th>Amount</th>
                                                    <th>Deleted At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($billings as $billing)
                                                    <tr>
                                                        <td>{{ $billing->id }}</td>
                                                        <td>{{ $billing->bill_name }}</td>
                                                        <td>{{ $billing->phone }}</td>
                                                        <td>{{ $billing->payment_method }}</td>
                                                        <td>{{ $billing->gst18 }}</td>
                                                        <td>{{ $billing->gst5 }}</td>
                                                        <td>{{ $billing->totalamount }}</td>
                                                        <td>{{ parseDateInHuman($billing->deleted_at) }}</td>
                                                        <td>
                                                            <div class="d-flex">
                                                                <a href="{{route('restore', ['id' => $billing->id, 'model' => 'OpenBilling'])}}"
                                                                    class="btn-info text-dark rounded-btn"><i
                                                                        class="fa fa-refresh" title="restore"></i></a>
                                                                <a href="{{route('delete', ['id' => $billing->id, 'model' => 'OpenBilling'])}}"
                                                                    class="btn-info d-block  px-3 text-danger rounded-btn"><i
                                                                        class="fa fa-trash" title="delete"></i></a>
                                                            </div>
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
            <!--start overlay-->
            <div class="overlay toggle-icon"></div>
            <!--end overlay-->
            <!--Start Back To Top Button-->
            <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
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
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <!--app JS-->

    <script src="{{ asset('assets/js/app.js') }}"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
