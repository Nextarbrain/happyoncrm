<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Billing Invoice</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins';
        }
    </style>
</head>

<body>
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
                <td style="font-size: 15px;text-align: center;">GSTIN : 24AAGCH20761ZC</td>
            </tr>
            <tr>
                <td style="font-size: 15px;border-top: 1px solid #000;border-bottom: 1px solid #000;text-transform: capitalize;">Bill Name : {{ $openbilling->bill_name }}</td>
            </tr>
            <tr>
                <td style="line-height: 37px;">
                    <table cellpadding="0" cellspacing="0" style="width: 100%;    border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="font-size: 15px;">Date : {{ \Carbon\Carbon::parse($openbilling->created_at)->format('d M, Y') }}</td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;">{{ \Carbon\Carbon::parse($openbilling->created_at)->format('h:i a') }}</td>
                                <td style="font-size: 15px;text-align: right;"></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;">Cashier : HappyOn</td>
                                <td style="font-size: 15px;text-align: right;">Invoice No : {{ $openbilling->id }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="border-collapse: collapse;width: 100%;">
                        <tbody>
                            <tr>
                                <td>
                                    <table cellpadding="0" cellspacing="0" style="    border-collapse: collapse;width: 100%;">
                                        <tbody>
                                            <tr style="border-top: 1px solid #000;">
                                                <td style="width: 20%;font-size: 15px;text-align: left;">Item</td>
                                                <td style="font-size: 15px;width: 20%;text-align: center;">Price</td>
                                                <td style="font-size: 15px;width: 20%;text-align: center;">QTY</td>
                                                <td style="font-size: 15px;width: 20%;text-align: center;">Tax</td>
                                                <td style="font-size: 15px;width: 20%;text-align: right;">Total</td>
                                            </tr>
                                            @foreach ($openbillingitems as $item)
                                                <tr style="border-top: 1px solid #000;">
                                                    <td style="font-size: 15px;width: 20%;text-align: left;">{{ $item->item_name }} </td>
                                                    <td style="font-size: 15px;width: 20%;text-align: center;">{{ $item->item_price }}</td>
                                                    <td style="font-size: 15px;width: 20%;text-align: center;">{{ $item->item_qty }}</td>
                                                    <td style="font-size: 15px;width: 20%;text-align: center;">{{ $item->item_tax }}</td>
                                                    @php
                                                        $subtotal = $item->item_price * $item->item_qty;
                                                        $tax = ($subtotal * $item->item_tax) / 100;
                                                    @endphp
                                                    <td style="width: 20%;font-size: 15px;text-align:right;">{{ $subtotal }}</td>
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
                                                                <td style="font-size: 15px;text-align: right;">{{ $openbilling->subtotal }}</td>
                                                            </tr>
                                                            @if (isset($openbilling->couponcode) && $openbilling->couponcode != null)
                                                                <tr style="border-top: 1px solid #000;">
                                                                    <td style="font-size: 15px;">Coupon ({{ $openbilling->couponcode }}) :</td>
                                                                    <td style="font-size: 15px;text-align: right;"> {{ $openbilling->couponvalue }}</td>
                                                                </tr>
                                                            @endif
                                                            @if (isset($openbilling->gst18) && $openbilling->gst18 != '0.00')
                                                                <tr style="border-top: 1px solid #000;">
                                                                    <td style="font-size: 15px;">CGST @ 9% :</td>
                                                                    <td style="font-size: 15px;text-align: right;"> {{ round($openbilling->gst18 / 2, 2) }}</td>
                                                                </tr>
                                                                <tr style="border-bottom: 1px solid #000;">
                                                                    <td style="font-size: 15px;">SGST @ 9% :</td>
                                                                    <td style="font-size: 15px;text-align: right;"> {{ round($openbilling->gst18 / 2, 2) }}</td>
                                                                </tr>                                                               
                                                            @endif
                                                            @if (isset($openbilling->gst12) && $openbilling->gst12 != '0.00')
                                                                <tr style="border-top: 1px solid #000;">
                                                                    <td style="font-size: 15px;">CGST @ 6% :</td>
                                                                    <td style="font-size: 15px;text-align: right;">{{ round($openbilling->gst12 / 2, 2) }}</td>
                                                                </tr>
                                                                <tr style="border-bottom: 1px solid #000;">
                                                                    <td style="font-size: 15px;">SGST @ 6% :</td>
                                                                    <td style="font-size: 15px;text-align: right;">{{ round($openbilling->gst12 / 2, 2) }}</td>
                                                                </tr>
                                                            @endif
                                                            @if (isset($openbilling->gst5) && $openbilling->gst5 != '0.00')
                                                                <tr style="border-top: 1px solid #000;">
                                                                    <td style="font-size: 15px;">CGST @ 2.5% :</td>
                                                                    <td style="font-size: 15px;text-align: right;">{{ round($openbilling->gst5 / 2, 2) }}</td>
                                                                </tr>
                                                                <tr style="border-bottom: 1px solid #000;">
                                                                    <td style="font-size: 15px;">SGST @ 2.5% :</td>
                                                                    <td style="font-size: 15px;text-align: right;">{{ round($openbilling->gst5 / 2, 2) }}</td>
                                                                </tr>
                                                            @endif
                                                            <tr style="border-top: 1px solid #000;">
                                                                <td style="font-size: 15px;font-weight: 400;">Grant Total :</td>
                                                                <td style="font-size: 15px;text-align: right;"> {{ $openbilling->totalamount }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table style="width: 100%;border-collapse: collapse;">
                                                        <tbody>
                                                            <tr style="border-top: 1px solid #000;">
                                                                <td style="font-size: 12px;text-align: center;">Note: This is a System Generated Invoice and does not required Signature</td>
                                                            </tr>
                                                            <tr>
                                                                <td style="text-align: center;font-size: 15px;font-weight: 600;">
                                                                    Thank you for visiting HappyON Party & Play
                                                                </td>
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
    <script>
        window.print();
    </script>
</body>

</html>
