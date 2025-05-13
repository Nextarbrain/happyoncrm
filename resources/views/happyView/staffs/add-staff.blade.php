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
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#paid"
                                                        aria-selected="true" role="tab">
                                                        <p class="heading">Manage Staff </p>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="tab-content">

                                            <div id="paid" class="container tab-pane active" role="tabpanel"><br>
                                                <form action="{{ url('/add-staff-post') }}" method="post"
                                                    id="my-form">
                                                    @csrf
                                                    <div class="row">

                                                        <div class="col-md-4 input_wrap">
                                                            <div class="mb-4">
                                                                <label>Name <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Name" id="name" name="name"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 input_wrap">
                                                            <div class="mb-4">
                                                                <label>Email <span>*</span></label>
                                                                <input type="email" class="form-control"
                                                                    placeholder="Email" id="email" name="email"
                                                                    required>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 input_wrap">
                                                            <div class="mb-4">
                                                                <label for="phone">Phone <span>*</span></label>
                                                                <input type="tel" class="form-control"
                                                                    placeholder="Phone" name="phone" id="phone"
                                                                    pattern="[0-9]{10}" required>
                                                            </div>
                                                        </div>



                                                        <div class="col-md-4 input_wrap">
                                                            <div class="mb-4">
                                                                <label for="password">Password <span>*</span></label>
                                                                <input type="password" class="form-control"
                                                                    placeholder="Password" name="password" required>

                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 input_wrap">
                                                            <div class="mb-4">
                                                                <label for="role">Role <span>*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Role" name="role" required>

                                                            </div>
                                                        </div>



                                                    </div>


                                                    <div class="h_btn s_btn">
                                                        <button class="btn btn-primary">Add Staff</button>
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
