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
                                    <li class="breadcrumb-item active" aria-current="page">Manage Role</li>
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
                                                    <th>Sl.No.</th>
                                                    <th>Name</th>
                                                    <th>Permissions</th>
                                                    {{-- <th>Guard</th> --}}
                                                    <th>Created</th>
                                                    <th>Last Updated</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sl = 1;
                                                @endphp
                                                @foreach ($roles as $role)
                                                    <tr>
                                                        <td>{{ $sl }}</td>
                                                        <td>{{ $role->name }}</td>
                                                        <td>
                                                            @foreach ($role->permissions->toArray() as $key => $value)
                                                                [{{ $value['name'] }}]
                                                            @endforeach
                                                        </td>
                                                        {{-- <td>{{ $role->guard_name }}</td> --}}
                                                        <td>{{ $role->created_at }}</td>
                                                        <td>{{ $role->updated_at }}</td>
                                                        <td>
                                                            <a href="{{ route('admin.admin.role.edit', ['id' => $role->id]) }}"
                                                                class="btn btn-info text-light rounded-btn"><i
                                                                    class="fa fa-edit"></i></a>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $sl++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <style>
                                        .h1 {
                                            background: green;
                                            margin: 10px 0 10px 0;
                                            padding: 5px 10px;
                                            text-transform: uppercase;
                                            color: #fff;
                                            font-size: 12px;
                                        }
                                        #example1 th, #example1 td {
                                            white-space: normal !important;
                                            word-wrap: break-word;
                                        }
                                        #example1 td:nth-child(3) {
                                            max-width: 250px;
                                        }
                                        .table-wrapper {
                                            overflow-x: auto;
                                        }
                                    </style>
                                    <div class="modal fade" id="addNew" tabindex="-1"
                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog  modal-xl">
                                            <div class="modal-content overflow-auto">
                                                {{ Form::open(['route' => 'admin.admin.role', 'files' => false, 'method' => 'post']) }}
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Create Role</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{ Form::label('name', ucwords('name')) }}
                                                    {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                                    @error('name')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                                    @enderror
                                                    @error('permission')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                                    @enderror
                                                    @if(!auth('admin')->user()->branch_id != null)
                                                        {{ Form::label('branch_id', 'Branch') }}
                                                        {!! Form::select('branch_id', $branches, null, ['class' => 'form-control']) !!}
                                                        @error('branch_id')
                                                            <span class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                                        @enderror
                                                    @else
                                                        {!! Form::hidden('branch_id', auth('admin')->user()->branch_id ?? null) !!}
                                                    @endif
                                                    @php
                                                        $current = null;
                                                        $index = 0;
                                                    @endphp
                                                    @foreach ($permissions as $key => $p)
                                                        @php
                                                            $array = explode('-', $p);
                                                        @endphp

                                                        @if ($array[0] != $current)
                                                </div>
                                                <p class="h1 small" style="font-size: 12px;">{{ $array[0] }}</p>
                                                <div class="d-flex flex-wrap">
                                                    @endif
                                                    <div style=" padding: 0.5em;">
                                                        {!! Form::checkbox('permission[]', $key, false, ['id' => "permission$key", 'class' => 'mt-3 ms-2']) !!}
                                                        {{ Form::label("permission$key", strtoupper($p)) }}
                                                    </div>

                                                    @php
                                                        $current = $array[0];
                                                    @endphp
                                                    @endforeach
                                                </div>
                                                <div class="modal-footer mt-3">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Create</button>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        </div>
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







    @error('name')
        <script>
            var permission = new bootstrap.Modal(document.getElementById('addNew'), {
                keyboard: false
            });
            permission.show();
        </script>
    @enderror
