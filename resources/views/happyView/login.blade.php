@include('happyView.includes.head')

<body>


    <!--end header -->
    <!--start page wrapper -->
    
    <div class="container" style="height:100vh">
        
            <div class="row d-flex justify-content-center align-items-center">
                <div class="col-md-6">
                    <div class="d-flex justify-content-center mb-5 mt-5">
                        <img src="{{asset('assets/images/happyon_logo.png')}}" class="logo-icon img-thumbnail" alt="logo">
                    </div>
                    
                    <form action="{{ url('/auth') }}" method="post">
                        @csrf
                        <div class="row d-flex justify-content-center align-items-center">
        
                            <div class="col-md-8">
                                <div class="mb-4 input_wrap">
                                    <label>Email <span>*</span></label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-4 input_wrap">
                                    <label>Password <span>*</span></label>
                                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                                </div>
                            </div>
        
                            <div class="col-md-8">
                            <div class="s_btn">
                                <button type="submit" class="btn">Login</button>
                            </div>
                            </div>
                        </div>
                    </form>
                </div>
           
            
     
        </div>

    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>

    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>

    <script src="assets/js/index.js"></script>
    <!--app JS-->
    <script src="assets/js/circle-progress.min.js"></script>
    <script src="assets/js/app.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>


     <script>
            $(document).ready(function() {
            toastr.options.timeOut = 10000;
            @if (Session::has('error'))
                toastr.error('{{ Session::get('error') }}');
            @elseif(Session::has('success'))
                toastr.success('{{ Session::get('success') }}');
            @endif
        });
        </script>
</body>

</html>
