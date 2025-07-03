<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Invoice</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins';
        }
    </style>
</head>

<body>
    <div>
        <table cellpadding="0" cellspacing="0" border-collapse="0" style="width:100%; height:700px;">
            <tbody>
                <tr>
                    <td style="text-align: center;"><img src="{{ asset('assets/images/happyon_logo.png') }}"
                            alt="logo" style="height:60px"></td>
                </tr>
                <tr>
                    <td style="
    font-size: 15px;
    font-weight: 600;
    text-align: center;
">HappyOn Private
                        Limited</td>

                </tr>
                <tr>
                    <td style="
    font-size: 15px;
    text-align: center;
">212 A, Times Square Grand, Sindhubhavan
                        Road, Thaltej, <br>Ahmedabad -380059
                    </td>

                </tr>
                <tr>
                    <td style="
    font-size: 15px;
    text-align: center;
">Phone Number : +91 9328672178</td>

                </tr>
                <tr>
                    <td style="
    font-size: 15px;
    text-align: center;
">GSTIN : 24AAGCH2076N1ZC</td>

                </tr>
                <tr>
                    <td
                        style="
    font-size: 15px;
    /* text-align: center; */
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    text-transform: capitalize;
">
                        Name : {{ $guardian_name }}</td>

                </tr>
                <tr>
                    <td style="
    line-height: 37px;
">
                        <table cellpadding="0" cellspacing="0" style="
    width: 100%;    border-collapse: collapse;
">
                            <tbody>
                                <tr>
                                    <td style="
    font-size: 15px;
">Date :
                                        {{ \Carbon\Carbon::parse($date)->format('d M, Y') }}</td>
                                    <td style="
    font-size: 15px;
    text-align: right;
">Payment Method :
                                        {{ $payment_method }}</td>
                                </tr>

                                <tr>
                                    <td style="
    font-size: 15px;
">Cashier : HappyOn</td>
                                    <td style="
    font-size: 15px;
    text-align: right;
">Invoice No :
                                        {{ $invoiceNo }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table style="    border-collapse: collapse;
    width: 100%;
">

                            <tbody>
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0"
                                            style="    border-collapse: collapse;
    width: 100%;
">
                                            <tbody>
                                                <tr style="
    border-top: 1px solid #000;
">
                                                    <td style="
    width: 40%;
    font-size: 15px;
">Membership Plan
                                                    </td>

                                                    <td
                                                        style="
    font-size: 15px;
    width: 20%;
    text-align: right;
">
                                                        Amount</td>
                                                </tr>
                                                <tr style="
    border-top: 1px solid #000;
">
                                                    <td style="
    font-size: 15px;
">{{ $package_name }} -
                                                        {{ $user->membership->total_credit ?? null }} Entry</td>

                                                    <td style="
    font-size: 15px;
    text-align: right;
">
                                                        {{ $user->membership->amount ?? null }}</td>

                                                </tr>


                                            </tbody>
                                        </table>
                                        <table style="
    width: 100%;
">


                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <table cellpadding="0" cellspacing="0"
                                                            style="
    width: 100%;
    border-collapse: collapse;
">
                                                            <tbody>
                                                                <tr style="
    border-top: 1px solid #000;
">
                                                                    <td style="
    font-size: 15px;
">Sub Total :</td>
                                                                    <td
                                                                        style="
    font-size: 15px;
    text-align: right;
">
                                                                        {{ $subTotal }}</td>
                                                                </tr>

                                                                @if (isset($couponcode) && $couponcode != null)
                                                                    <tr style="
    border-top: 1px solid #000;
">
                                                                        <td style="
    font-size: 15px;
">Coupon
                                                                            ({{ $couponcode }}) :</td>
                                                                        <td
                                                                            style="
    font-size: 15px;
    text-align: right;
">
                                                                            {{ $couponvalue }}</td>


                                                                    </tr>
                                                                @endif

                                                                @if (isset($gst18) && $gst18 != null && $gst18 != '0.00')
                                                                    <tr style="
    border-top: 1px solid #000;
">
                                                                        <td style="
    font-size: 15px;
">Exclude Gst
                                                                            @ 18% :</td>
                                                                        <td
                                                                            style="
    font-size: 15px;
    text-align: right;
">
                                                                            {{ $gst18 }}</td>


                                                                    </tr>
                                                                @endif

                                                                <tr style="
    border-top: 1px solid #000;
">
                                                                    <td
                                                                        style="
    font-size: 15px;
    font-weight: 400;
">
                                                                        Grant Total :</td>
                                                                    <td
                                                                        style="
    font-size: 15px;
    text-align: right;
">
                                                                        {{ $totalAmount }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                        <table
                                                            style="
    width: 100%;
    border-collapse: collapse;
">


                                                            <tbody>


                                                                <tr style="
    border-top: 1px solid #000;
">
                                                                    <td
                                                                        style="
    font-size: 12px;
    text-align: center;
">
                                                                        Note: This is a System Generated Invoice and
                                                                        does not required Signature</td>
                                                                </tr>
                                                                <tr>
                                                                    <td
                                                                        style="
    text-align: center;
    font-size: 15px;
    font-weight: 600;
">
                                                                        Thank you for visiting HappyON Party & Play
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td
                                                                        style="
    text-align: center;
    font-size: 15px;
   ">
                                                                        <a
                                                                            href="www.happyonplay.com">www.happyonplay.com</a>
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
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <script>
                window.print();
            </script>
</body>

</html>
