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
                                <div class="alert alert-{{ session()->get('class') }} alert-dismissible fade show" role="alert">
                                    {!! session()->get('msg') !!}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif                            
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
                                                    <th>Email</th>
                                                    <th>Created</th>
                                                    <th>Last Updated</th>
                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sl = 1;
                                                @endphp
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $sl }}</td>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->created_at }}</td>
                                                        <td>{{ $user->updated_at }}</td>
                                                         <td>{{ optional($user['branch'])->name ?? '' }}</td>
                                                        <td>
                                                       <a href="{{ route('admin.admin.edit', ['id' => $user->id]) }}"
                                                                    class="btn-info text-dark rounded-btn"><i
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
                                    <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Create User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                        <form method="GET" action="{{ route('admin.admin.role') }}">
                                                            <label>Branch <span style="color: red">*</span></label>
                                                            <select name="branch_id" id="manageBranchId"
                                                                class="form-select" required>
                                                                @foreach ($branches as $branch)
                                                                    <option value="{{ $branch['id'] }}" {{ $branch['id'] == $branchId  ? 'selected' : ""}}>{{ $branch['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </form>
                                                    @endif
                                                    {{ Form::open(['route' => 'admin.admin.permission', 'files' => false, 'method' => 'post']) }}
                                                    {{ Form::label('name', ucwords('name')) }}
                                                    {!! Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' form-error' : null)]) !!}
                                                    @error('name')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                                    @enderror
                                                    @if(auth('admin')->user()->branch_id === null && auth('admin')->user()->isSuperAdmin == 1)
                                                        <input type="hidden" name="branch_id" id="hiddenBranchId">
                                                    @endif
                                                    {{ Form::label('email', ucwords('email')) }}
                                                    {!! Form::email('email', null, ['class' => 'form-control' . ($errors->has('email') ? ' form-error' : null)]) !!}
                                                    @error('email')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                                    @enderror
                                                    {{ Form::label('password', ucwords('password')) }}
                                                    {!! Form::password('password', ['class' => 'form-control' . ($errors->has('password') ? ' form-error' : null)]) !!}
                                                    @error('password')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                                    @enderror
                                                    {{ Form::label('confirm_password', ucwords('confirm password')) }}
                                                    {!! Form::password('confirm_password', [
                                                        'class' => 'form-control' . ($errors->has('confirm_password') ? ' form-error' : null),
                                                    ]) !!}
                                                    @error('confirm_password')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                                    @enderror
                                                    {{-- @if(!auth('admin')->user()->branch_id != null)
                                                        {{ Form::label('branch_id', 'Branch') }}
                                                        {!! Form::select('branch_id', $branches, null, ['class' => 'form-control']) !!}
                                                        @error('branch_id')
                                                            <span class="text-danger font-weight-bold text-capitalize">{{ $message }}</span>
                                                        @enderror
                                                    @else
                                                        {!! Form::hidden('branch_id', auth('admin')->user()->branch_id ?? null) !!}
                                                    @endif --}}
                                                    {{ Form::label('roles', ucwords('Assign Role')) }}
                                                    {!! Form::select('roles', $roles, null, [
                                                        'class' => 'form-control' . ($errors->has('roles') ? ' form-error' : null),
                                                    ]) !!}
                                                    @error('roles')
                                                        <span
                                                            class="text-danger font-weight-bold text-capitalize d-block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer">
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
    @error('name')
        <script>
            var permission = new bootstrap.Modal(document.getElementById('addNew'), {
                keyboard: false
            });
            permission.show();
        </script>

    @enderror
    <script>
        $(document).ready(function () {
            $('#manageBranchId').on('change', function () {
                let branchId = $(this).val();
                if (!branchId) return;
                $('#hiddenBranchId').val(branchId);
                $.ajax({
                    url: '{{ route("getBranchRole") }}',
                    type: 'GET',
                    data: { branch_id: branchId },
                    success: function (res) {
                        let rolesSelect = $('select[name="roles"]');
                        rolesSelect.empty();
                        rolesSelect.append('<option value="">Select Role</option>');
                        $.each(res.data, function (key, value) {
                            rolesSelect.append(`<option value="${value.name}">${value.name}</option>`);
                        });
                    },
                    error: function (err) {
                        console.error("AJAX error:", err);
                    }
                });
            });
        });
    </script>
