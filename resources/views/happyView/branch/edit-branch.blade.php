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
                                                        <p class="heading">Edit Branch</p>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="tab-content">
                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                <form action="{{ url('/edit-branch-post') }}" method="post"
                                                    id="editBranchForm">
                                                    @csrf
                                                    <input type="hidden" value="{{ $branch->id }}"
                                                        name="id">
                                                    <input type="hidden" value="{{ $branch->admin &&  $branch->admin->id ?  $branch->admin->id : ''}}" name="admin_id">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Branch Name <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Branch Name" name="name" value="{{ $branch->name }}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="phone">Phone<span>*</span> </label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Phone" name="phone" id="phone"
                                                                    pattern="[0-9]{10}" value="{{ $branch->phone }}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Person Name <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Person Name" name="person_name" value="{{ $branch->admin &&  $branch->admin->name ?  $branch->admin->name : ''}}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Email <span>*</span></label>
                                                                <input type="email" class="form-control"
                                                                    placeholder="Email Name" name="email" value="{{ $branch->admin &&  $branch->admin->email ?  $branch->admin->email : ''}}" readonly>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Password <span>*</span></label>
                                                                <input type="password" class="form-control"
                                                                    placeholder="Password" name="password">

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Location <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Location" name="location" value="{{ $branch->location }}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="pincode">Pincode<span>*</span></label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Pincode" name="pincode" id="pincode"
                                                                    pattern="[0-9]{6}" value="{{ $branch->pincode }}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="gstno">Gstno<span>*</span></label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Gst No" name="gstno" id="gstno" value="{{ $branch->gstno }}" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="mb-4 input_wrap">
                                                                <label for="address">Address </label>
                                                                <textarea id="address" name="address" rows="4" cols="45">{{ $branch->address }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Type</label>
                                                                <select name="type" id="type" class="form-select">
                                                                    <option value="Branch" {{ $branch->type && $branch->type == 'Branch' ? 'selected' : '' }}>Branch</option>
                                                                    <option value="Franchise" {{ $branch->type && $branch->type == 'Franchise' ? 'selected' : '' }}>Franchise</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 ">
                                                            <div class="mb-4 input_wrap">
                                                                <label>Status</label>
                                                                <select name="status" id="status"
                                                                    class="form-select">
                                                                    <option value="Active" {{ $branch->status == 'Active' ? 'selected' : '' }}>Active</option>
                                                                    <option value="Inactive" {{ $branch->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="h_btn s_btn">
                                                            <button class="btn btn-primary">Submit</button>
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


</body>

</html>
