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
		
		
		<!--end header -->

        <div class="page-wrapper">
			<div class="page-content">
				<div class="row">
                <img src="{{ $qr_code }}" alt="QR code">
                </div>
            </div>
        </div>

    

    </div>
	<!--end wrapper-->

@include('happyView.includes.footer')