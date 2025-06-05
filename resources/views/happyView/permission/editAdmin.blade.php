@include('happyView.includes.head')
<style>
    .h1 {
        background: #000;
        margin: 10px 0 10px 0;
        padding: 5px 10px;
        text-transform: uppercase;
        color: #fff;
        font-size: 12px;
    }
</style>

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
                                <div class="alert alert-{{ session()->get('class') }} alert-dismissible fade show" role="alert">
                                    {!! session()->get('msg') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                                </ol>
                            </nav>
                            {{ Form::open(['route' => ['admin.admin.edit', request()->route('id')], 'files' => false, 'method' => 'PUT']) }}
                            {{ Form::label('name', ucwords('name')) }}
                            {!! Form::text('name', $user->name, ['class' => 'form-control' . ($errors->has('name') ? ' form-error' : null)]) !!}
                            @error('name')
                                <span class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                            @enderror
                            {{ Form::label('email', ucwords('email')) }}
                            {!! Form::email('email', $user->email, [
                                'class' => 'form-control' . ($errors->has('email') ? ' form-error' : null),
                            ]) !!}
                            @error('email')
                                <span class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                            @enderror
                            {{ Form::label('password', ucwords('password')) }}
                            {!! Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' form-error' : null)]) !!}
                            @error('password')
                                <span class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                            @enderror
                            {{ Form::label('confirm_password', ucwords('confirm password')) }}
                            {!! Form::password('confirm_password', [
                                'class' => 'form-control' . ($errors->has('confirm_password') ? ' form-error' : null),
                            ]) !!}
                            @error('confirm_password')
                                <span class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                            @enderror

                            {{-- @if(!auth('admin')->user()->branch_id != null)
                                {{ Form::label('branch_id', 'Branch') }}
                                {!! Form::select('branch_id', $branches, $user->branch_id ?? $user->branch_id , ['class' => 'form-control']) !!}
                                @error('branch_id')
                                    <span class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                @enderror
                            @else
                                {!! Form::hidden('branch_id', auth('admin')->user()->branch_id ?? null) !!}
                            @endif
                            @if(!$user->isSuperAdmin)
                                {{ Form::label('roles', ucwords('Assign Role')) }}
                                {!! Form::select('roles', $roles, $userRole, [
                                    'class' => 'form-control' . ($errors->has('roles') ? ' form-error' : null),
                                ]) !!}
                                @error('roles')
                                    <span class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                    @enderrorger font-weight-bold text-capitalize">{{ $message }}</span>
                                @enderror
                            @endif --}}
                            <div class="d-flex gap-2 my-3">
                                <button type="submit" class="btn btn-primary">Update</button>

                                <a href="{{ route('admin.admin.permission') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        {!! Form::close() !!}
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

