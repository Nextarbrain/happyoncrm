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
                            <button class="btn h_btn bg-primary text-light btn-sm" onclick="printReciept()">Print Reciept</button>
                            <div id="myContainer">
                                <table cellpadding="0" cellspacing="0" border-collapse="0" style="width:100%; height:700px;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;"><img src="{{ asset('assets/images/happyon_logo.png') }}" alt="logo" style="height:60px"></td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;font-weight: 600;text-align: center;">HappyOn Private Limited</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;text-align: center;">212 A, Times Square Grand, Sindhubhavan Road, Thaltej, <br>Ahmedabad -380059</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;text-align: center;">Phone Number : +91 9328672178</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;text-align: center;">GSTIN : 24AAGCH2076N1ZC</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 15px;border-top: 1px solid #000;border-bottom: 1px solid #000;text-transform: capitalize;">Name : {{ $guardian_name }}</td>
                                        </tr>
                                        <tr>
                                            <td style="line-height: 37px;">
                                                <table cellpadding="0" cellspacing="0" style="width: 100%;border-collapse: collapse;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-size: 15px;">Date : {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</td>
                                                            <td style="font-size: 15px;text-align: right;">Payment Method : {{ $payment_method }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 15px;">Hours :
                                                                @foreach ($distinctHours as $val)
                                                                    {{ $val }}
                                                                    {{ $val == 'Unlimited |' ? '' : 'hr |' }}
                                                                @endforeach
                                                            </td>
                                                            <td style="font-size: 15px;text-align: right;"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 15px;">In Time : {{ $time }}</td>
                                                            <td style="font-size: 15px;text-align: right;"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 15px;">Out Time : {{ $end_time }}</td>
                                                            <td style="font-size: 15px;text-align: right;"></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-size: 15px;">Cashier : HappyOn</td>
                                                            <td style="font-size: 15px;text-align: right;">Invoice No :{{ $invoiceNo }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table style="border-collapse: collapse;width: 100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <table cellpadding="0" cellspacing="0" style="border-collapse: collapse;width: 100%;">
                                                                    <tbody>
                                                                        <tr style="border-top: 1px solid #000;">
                                                                            <td style="width: 40%;font-size: 15px;">Item</td>
                                                                            <td style="font-size: 15px;width: 20%;text-align: center;">Qty</td>
                                                                            <td style="font-size: 15px;width: 20%;">Price</td>
                                                                            <td style="font-size: 15px;width: 20%;text-align: right;">Amount</td>
                                                                        </tr>
                                                                        @foreach ($kidsInvoice as $kidsInv)
                                                                            <tr style="border-top: 1px solid #000;">
                                                                                <td style="font-size: 15px;">{{ $kidsInv['hours'] }}</td>
                                                                                <td style="font-size: 15px;text-align: center;">{{ $kidsInv['quantity'] }}</td>
                                                                                <td style="font-size: 15px;">{{ $kidsInv['price'] }}</td>
                                                                                <td style="font-size: 15px;text-align: right;">
                                                                                    {{ $kidsInv['subtotal'] }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                        @foreach ($userProducts as $prod)
                                                                            @php
                                                                                $productTax = getTax($prod->product_name);
                                                                            @endphp
                                                                            <tr style="border-top: 1px solid #000;">
                                                                                <td style="font-size: 15px;">{{ $prod->product_name }} </td>
                                                                                @if ($prod->product_name == 'Socks')
                                                                                    <td style="font-size: 15px;text-align: center;">{{ ($prod->qty - $kidTotal) == 0 ?  $prod->qty : ($prod->qty - $kidTotal);}}</td>
                                                                                    {{-- <td style="font-size: 15px;text-align: center;">{{ $prod->qty - $kidTotal }}</td> --}}
                                                                                    <td style="font-size: 15px;">{{ $prod->price }}</td>                                                                                    
                                                                                    <td style="font-size: 15px;text-align: right;">{{ getAddedItemTotal($productTax, $prod->qty - $kidTotal, $prod->price) }}</td>
                                                                                @else
                                                                                    <td style="font-size: 15px;text-align: center;">{{ $prod->qty }}</td>
                                                                                    <td style="font-size: 15px;">{{ $prod->price }}</td>                                                                                    
                                                                                    <td style="font-size: 15px;text-align: right;">{{ getAddedItemTotal($productTax, $prod->qty, $prod->price) }}</td>
                                                                                @endif
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                                <table style="width: 100%;">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td>
                                                                                <table cellpadding="0" cellspacing="0" style="width: 100%;border-collapse: collapse;">
                                                                                    <tbody>
                                                                                        <tr style="border-top: 1px solid #000;">
                                                                                            <td style="font-size: 15px;">Sub Total :</td>
                                                                                            <td style="font-size: 15px;text-align: right;">{{ $subTotal }}</td>
                                                                                        </tr>
                                                                                        @if (isset($couponcode) && $couponcode != null)
                                                                                            <tr style="border-top: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">Coupon ({{ $couponcode }}) : </td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ $couponvalue }}</td>
                                                                                            </tr>
                                                                                        @endif
                                                                                        @if (isset($gst18) && $gst18 != null && $gst18 != '0.00')
                                                                                            <tr style="border-top: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">CGST @ 9% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst18 / 2, 2) }}</td>
                                                                                            </tr>
                                                                                            <tr style="border-bottom: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">SGST @ 9% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst18 / 2, 2) }}</td>
                                                                                            </tr>

                                                                                            <tr style="border-top: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">CGST @ 2.5% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst5 / 2, 2) }}</td>
                                                                                            </tr>
                                                                                            <tr style="border-bottom: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">SGST @ 2.5% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst5 / 2, 2) }}</td>
                                                                                            </tr>
                                                                                        @endif
                                                                                        @if (isset($gst12) && $gst12 != null && $gst12 != '0.00')
                                                                                            <tr style="border-top: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">CGST @ 6% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst12 / 2, 2) }}</td>
                                                                                            </tr>
                                                                                            <tr style="border-bottom: 1px solid #000;">
                                                                                                <td style="font-size: 15px;">SGST @ 6% :</td>
                                                                                                <td style="font-size: 15px;text-align: right;">{{ round($gst12 / 2, 2) }}</td>
                                                                                            </tr>
                                                                                        @endif
                                                                                        <tr style="border-top: 1px solid #000;">
                                                                                            <td style="font-size: 15px;font-weight: 400;">Grand Total :</td>
                                                                                            <td style="font-size: 15px;text-align: right;">{{ $totalAmount }}</td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <table style="width: 100%;border-collapse: collapse;">
                                                                                    <tbody>
                                                                                        <tr style="border-top: 1px solid #000;">
                                                                                            <td style="font-size: 12px;text-align: center;">
                                                                                                Note: This is a System Generated Invoice and
                                                                                                does not required Signature
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="text-align: center;font-size: 15px;font-weight: 600;">Thank you for visiting HappyON Party & Play</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="text-align: center;font-size: 15px;"><a href="www.happyonplay.com">www.happyonplay.com</a></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>
                                    <div>
                                        <script>
                                            function printReciept() {
                                                var container = document.getElementById('myContainer');
                                                var containerContent = container.innerHTML;
                                                var printWindow = window.open('', '', 'width=600,height=600');
                                                printWindow.document.write('<html><head><title>Print</title></head><body>');
                                                printWindow.document.write(containerContent);
                                                printWindow.document.write('</body></html>');
                                                printWindow.document.close();
                                                printWindow.print();
                                            }
                                        </script>
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
