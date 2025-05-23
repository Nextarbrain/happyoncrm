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
                                    <li class="breadcrumb-item active" aria-current="page">Permission</li>
                                </ol>
                            </nav>
                            <button type="button" class="btn btn-primary btn-sm my-2" data-bs-toggle="modal"
                                data-bs-target="#addNew">
                                Add New
                            </button>

                            <div class="container">
                                <div class="row table-responsive">
                                    <div class="data_table">

                                        <table id="example1" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    {{-- <th>Guard</th> --}}
                                                    <th>Created</th>
                                                    <th>Last Updated</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissions as $permission)
                                                    <tr>
                                                        <td>{{ $permission->id }}</td>
                                                        <td>{{ $permission->name }}</td>
                                                        {{-- <td>{{ $permission->guard_name }}</td> --}}
                                                        <td>{{ $permission->created_at }}</td>
                                                        <td>{{ $permission->updated_at }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            {!! $permissions->links() !!}
                        </div>
                        <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    {{ Form::open(['route' => ['permission.store'], 'files' => false, 'method' => 'POST']) }}
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Permission Name</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ Form::label('Name', ucwords('Name')) }}
                                        {{ Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' form-error' : null), 'placeholder' => 'Enter Permission Name', "required" => true]) }}
                                        @error('name')
                                            <span class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                    {!! Form::close() !!}
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
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<!--app JS-->

	<script src="{{asset('assets/js/app.js')}}"></script>
	{{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}





