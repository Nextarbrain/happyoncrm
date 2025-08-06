<?php

namespace App\Http\Controllers;

use Carbon;
use Exception;
use App\Models\Kid;
use App\Models\User;
use App\Models\Event;
use App\Models\Product;
use App\Models\DayPrice;
use App\Models\UserDetail;
use App\Models\Branch;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;
//import model admin
use App\DataTables\UserDataTable;
use App\DataTables\CustomerDataTable;

use Illuminate\Support\Carbon as LaravelCarbon;


//import class hash password
use Illuminate\Support\Facades\Hash;

class TicketController extends Controller
{
    public function ticketsView(Request $request)
    {
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        $branchId = $request->get('branch_id') ? (int) $request->get('branch_id') : auth('admin')->user()->branch_id;
        if ($branchId === null) {
            $branchId = $branches[0]['id'] ?? null;
        }
        if (can('tickets') || can('tickets-add') || can('customers')) {
            $product = Product::select('product_name', 'product_price', 'product_tax');
            if ($branchId) {
                $product->where('branch_id', $branchId);
            }
            $product = $product->get();
            $result = [];
            $data = [];
            foreach ($product as $products) {
                $data[$products->product_name] = $products->product_price;
            }
            $result[] = $data;
            return view('happyView.tickets', compact('product', 'result', 'branches', 'branchId'));
        } else {

            abort(401);
        }
    }

    function upcomingBirthdaysView(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $currentDate = LaravelCarbon::today();
        $endDate = $currentDate->copy()->addDays(60);
        $user = UserDetail::whereRaw("DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT('{$currentDate}', '%m-%d') AND DATE_FORMAT('{$endDate}', '%m-%d')");
        if ($branchId) {
            $user->where('branch_id', $branchId);
        }
        $user = $user->orderByRaw("DATE_FORMAT(dob, '%m-%d')")->get();
        return view('happyView.upcomingbirthdays', compact('user'));
    }

    function filterCustomer(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        if (isset($request->key)) {
            $user = User::with('membership', 'kid', 'userDetails', 'event', 'paymentInfo')->where('role', '!=', 0);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->latest()->get();

            $data = [];
            foreach ($user as $u) {
                if ($u['paymentInfo']->payment_method == $request->key) {
                    array_push($data, $u);
                }
            }
            $user = $data;
        } else {
            $user = User::with('membership', 'kid', 'userDetails', 'event', 'paymentInfo')->where('role', '!=', 0);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->latest()->get();
        }

        return view('happyView.customerfilter', compact('user'));
    }

    public function addTickets(Request $request)
    {
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        if (!$branchId) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }
        $userSave = new User();
        $userSave->name = ucfirst($request->input('name_of_kid'));
        $userSave->branch_id = $branchId;
        $userSave->email = $request->input('name_of_kid') . rand(10000, 999999);
        $userSave->role = 1;
        $userSave->save();
        if ($userSave->id) {
            $event = new Event();
            $event->user_id = $userSave->id;
            $event->branch_id = $branchId;
            $event->booking_date = $request->input('date');
            $event->Hours =  now()->format('H:i');
            $event->save();

            $userDetails = new UserDetail();
            $userDetails->user_id = $userSave->id;
            $userDetails->phone_no = $request->input('phone');
            $userDetails->branch_id = $branchId;
            $userDetails->dob = $request->input('dob');
            $userDetails->number_of_kids = $request->input('kids');
            $userDetails->save();

            $kidsName = $request->input('kidName');
            if ($kidsName) {
                $i = 0;
                $num_of_kid = 1;
                foreach ($kidsName as $row) {
                    $kidsDetails = new Kid();
                    $kidsDetails->user_id = $userSave->id;
                    $kidsDetails->branch_id = $branchId;
                    $kidsDetails->name = ucfirst($row);
                    $kidsDetails->kidPrice = $request->input('kid_price')[$i];
                    $kidsDetails->hours = $request->input('hours')[$i];
                    if ($request->input('hours')[$i] > 0) {
                        $end_time = Carbon\Carbon::parse(now()->format('H:i'))->addHour($request->input('hours')[$i]);
                        $time_str = $end_time->toTimeString();
                        $kidsDetails->end_time = $time_str;
                    } elseif ($request->input('hours')[$i] == "Unlimited") {
                        $kidsDetails->end_time = "Unlimited";
                    }
                    $kidsDetails->save();
                    $num_of_kid++;
                    $i++;
                }
            }
            /* add on product in user_product table */
            if (isset($request->product_quantity)) {
                foreach ($request->product_quantity as $key => $val) {
                    if ($val !== "0") {
                        $product_add = new UserProduct();
                        $product_add->user_id = $userSave->id;
                        $product_add->branch_id = $branchId;
                        $product_add->product_name = $request->product_name[$key];
                        $product_add->price = $request->final_price[$key] / $val;
                        $product_add->qty = ($request->product_name[$key] == 'Socks') ? $val + count($kidsName) : $val;
                        $product_add->save();
                    }
                }
            }
            if (!isset($request->product_name) || isset($request->product_name) && !in_array('Socks', $request->product_name)) {
                $product_add = new UserProduct();
                $product_add->user_id = $userSave->id;
                $product_add->branch_id = $branchId;
                $product_add->product_name = 'Socks';
                $product_add->price = 0;
                $product_add->qty = count($kidsName);
                $product_add->save();
            }
            $amount = new PaymentMethod();
            $amount->user_id = $userSave->id;
            $amount->branch_id = $branchId;
            if ($request->input('payment_mode') == 1) {
                $amount->payment_method = "UPI";
            } elseif ($request->input('payment_mode') == 2) {
                $amount->payment_method = "Cash";
            } else {
                $amount->payment_method = "Debit/Credit";
            }
            $amount->gst18 = $request->input('gst18');

            if ($request->input('couponcode')) {
                $amount->couponcode = $request->input('couponcode');
                couponCountUpdate($request->input('couponcode'));
                $amount->couponvalue = $request->input('couponvalue');
            }

            $amount->gst12 = $request->input('gst12');
            $amount->gst5 = $request->input('gst5');
            $amount->subTotal = $request->input('subTotal');

            $amount->amounts = $request->input('totalAmount');
            $amount->save();

            //invoice generate
            $id = $userSave->id;
            $user = User::with('membership', 'userDetails', 'event', 'kid', 'paymentInfo')->where('id', $id)->first();
            $paymentMethod = PaymentMethod::where('user_id', $id)->first();

            $kidValue = Kid::where('user_id', $id);
            $kidTotal = $kidValue->count();
            $totalHours = $kidValue->first();
            $totalKidPrice = $kidValue->sum('kidPrice');
            $KidPrices = $kidValue->select('kidPrice')->first();

            $userProducts = UserProduct::where(['user_id' => $id])->latest()->get();

            $in_time = strtotime($user['event']->Hours);
            $in_time = date("h:i A", $in_time);

            if (isset($user['kid']->end_time)) {
                if ($user['kid']->end_time == "Unlimited") {
                    $out_time = "Unlimited";
                } else {
                    $out_time = strtotime($user['kid']->end_time);
                    $out_time = date("h:i A", $out_time);
                }
            } else {
                $out_time = "";
            }
            $distinctHour = Kid::where('user_id', $id)->distinct()->pluck('hours');
            $kidsInvoice = [];
            foreach ($distinctHour as $dist) {
                $kidTempData = Kid::where('user_id', $id);
                $KidPrices = $kidTempData->where('hours', $dist)->select('kidPrice')->first();
                $totalKidPrice = $kidTempData->where('hours', $dist)->sum('kidPrice');
                $kidTotal = $kidTempData->where('hours', $dist)->count();
                array_push($kidsInvoice, ['hours' => $dist, 'quantity' => $kidTotal, 'price' => $KidPrices->kidPrice, 'subtotal' => $totalKidPrice]);
            }
            $subTotal = $paymentMethod->subTotal + ($paymentMethod->couponvalue ?? 0);
            $data = [
                'guardian_name' => $user->name,
                "payment_method" => $paymentMethod->payment_method,
                "date" => $user['event']->booking_date,
                "time" => $in_time,
                "end_time" => $out_time,
                "hours" => $user['kid'] && $user['kid']->hours ? $user['kid']->hours : 0,
                "couponcode" => $user['paymentInfo']->couponcode,
                "couponvalue" => $user['paymentInfo']->couponvalue,
                "invoiceNo" => $paymentMethod->id,
                'gst5' => $paymentMethod->gst5,
                "kidTotal" => $kidTotal,
                "totalKidPrice" => $totalKidPrice,
                "KidPrice" => $KidPrices['kidPrice'],
                "playtime" => $totalHours,
                // "subTotal" => $subTotal,
                "subTotal" => $paymentMethod->subTotal,
                "gst18" => $paymentMethod->gst18,
                "gst12" => $paymentMethod->gst12,
                "totalAmount" => $paymentMethod->amounts,
                'kidsInvoice' => $kidsInvoice,
                "distinctHours" => $distinctHour,
                "userProducts" => $userProducts
            ];
            $mobile = "91" . $request->input('phone');
            $time_in = now()->format('h:i:s A');

            if ($user['kid']->end_time == "Unlimited") {
                $time_out = "Unlimited";
            } else {
                $time_out = strtotime($user['kid']->end_time);
                $time_out = date("h:i A", $time_out);
            }

            $date = $request->input('date');
            $amount = $request->input('totalAmount');
            $msg = "Welcome to HappyOn Party & Play! You have been registered with the below details: Date: $date, Time-In: $time_in, Time-Out: $time_out Amount Paid: $amount., Have a Gadget Free time at HappyOn Play!";
            $template_id = "1707168001511714072";
            try {
                sendsms($mobile, $msg, $template_id);
            } catch (Exception $e) {
            }
            $user__id = User::max('id');
            $customerKey = str_pad($user__id, 10, '0', STR_PAD_LEFT);
            $items = [];
            if (isset($request->kidName)) {
                for ($i = 0; $i < count($request["kidName"]); $i++) {
                    $items[] = [
                        "name" => $request["kidName"][$i],
                        "id" => uniqid(),
                        "rate" => floatval($request["kid_price"][$i]),
                        "quantity" => intval($request["hours"][$i]),
                        "subtotal" => floatval($request["kid_price"][$i]),
                        "category" => "",
                        "addon" => []
                    ];
                }
            }
            if (isset($request->product_name)) {
                for ($i = 0; $i < count($request["product_name"]); $i++) {
                    $items[] = [
                        "name" => $request["product_name"][$i],
                        "id" => uniqid(),
                        "rate" => floatval($request["final_price"][$i]),
                        "quantity" => intval($request["product_quantity"][$i]),
                        "subtotal" => floatval($request["final_price"][$i]) * intval($request["product_quantity"][$i]),
                        "category" => "",
                        "addon" => []
                    ];
                }
            }

            $taxes = [];
            if (isset($request->product_name)) {
                for ($i = 0; $i < count($request["product_name"]); $i++) {
                    $tax_api = $request["tax"][$i] / 100;
                    $taxes[] = [
                        "name" => "GST",
                        "percentage" => $request['tax'][$i],
                        "amount" => $request["final_price"][$i] * $tax_api,
                    ];
                }
            }

            $dob_api = date('d/m/Y', strtotime($request->dob));
            $postData = [
                "customer_key" => $paymentMethod->id,
                "merchant_id" => env('MERCHANT_ID'),
                "customer" => [
                    "name" =>  $user->name,
                    "mobile" => $request->phone,
                    "dob" => $dob_api,
                    "anniversary_date" => '',
                    "email" => ""
                ],
                "transaction" => [
                    "id" =>  $paymentMethod->id,
                    "number" => $paymentMethod->id,
                    "type" => "dine_in",
                    "source" => "online",
                    "payment_type" => $paymentMethod->payment_method,
                    "gross_amount" => $request->totalAmount,
                    "net_amount" => $request->subTotal,
                    "amount" => $request->totalAmount,
                    "charges" =>  [
                        [
                            "name" => "Service charge",
                            "percentage" => 10,
                            "amount" => 200
                        ],
                        [
                            "name" => "Delivery charge",
                            "amount" => 10
                        ]
                    ],

                    "taxes" => $taxes,
                    "sale_return" => false,
                    "payments" => [],
                    "items" => $items,
                    "redemption" => [
                        "reward_id" => "",
                        "redeemed_amount" => ""
                    ],
                    "discount" => 0,
                    "order_time" => $userSave->created_at,
                    "order_from" => "POS"
                ]
            ];
            // Make the POST request
            $response = Http::post('https://api.reelo.io/v1/pos/integrations/e7499eb1-3ef1-4162-8dfb-8b5a55d3e6ba/bill/customer', $postData);
            //invoice generate end
            return view('receipt.index', $data);
        }
    }

    public function ticketEdit(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        if (can('tickets-edit')) {
            $id = $request->id;
            $user = User::with('membership', 'userDetails', 'event', 'paymentInfo')->where('id', $id)->first();
            if ($branchId === null) {
                $branchId = $user['branch_id'] ?? null;
            }
            $kid = Kid::where('user_id', $id)->get();
            $kidCount = count($kid);

            $product = UserProduct::where('user_id', $id)->where('qty', '>', 0)->where('product_name', '!=', 'Extra Time')->get();

            $productData = Product::select('product_name', 'product_price', 'product_tax', 'id')
                ->where('product_name', '!=', 'Extra Time');
            if ($branchId) {
                $productData->where('branch_id', $branchId);
            }
            $productData = $productData->get();
            $paymentMethod = PaymentMethod::where('user_id', $id)->first();
            return view('happyView.tickets-edit', compact('user', 'kid', 'product', 'paymentMethod', 'productData', 'branches', 'branchId'));
        } else {
            abort(401);
        }
    }

    public function updateTickets(Request $request)
    {
        hasPermission('tickets-edit');
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        if (!$branchId) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }
        $id = $request->input('id');
        $user = User::where('id', $id)->first();
        $userDetail = UserDetail::where('user_id', $id)->first();
        $kidId = $request->input('add_extra');
        if ($kidId) {
            foreach ($kidId as $row) {
                $kidUpdatehrs = Kid::where('id', $row)->first();
                $kidUpdatehrs->hours += .5;
                $end_time = strtotime($kidUpdatehrs->end_time);
                $kidUpdatehrs->end_time = date("H:i:s", strtotime('+30 minutes', $end_time));

                $kidUpdatehrs->save();
            }
        }

        $kidids = $request->input('kidids');
        if ($kidids) {
            $i = 0;
            $num_of_kid = 1;
            foreach ($kidids as $row) {
                if ($request->input('hours')[$i] != 0) {
                    if ($kidId) {
                        if (in_array($row, $kidId)) {
                            $kidsDetails = Kid::where('id', $row)->first();

                            $kidsDetails->user_id = $id;
                            $kidsDetails->name = ucfirst($request->input('kidName')[$i]);
                            $kidsDetails->kidPrice = $request->input('kid_price')[$i];
                            $kidsDetails->save();
                            $num_of_kid++;
                        } else {
                            $kidsDetails = Kid::where('id', $row)->first();

                            $kidsDetails->user_id = $id;
                            $kidsDetails->name = ucfirst($request->input('kidName')[$i]);
                            $kidsDetails->kidPrice = $request->input('kid_price')[$i];
                            $kidsDetails->hours = $request->input('hours')[$i];
                            $end_time = Carbon\Carbon::parse(now()->format('H:i'))->addHour($request->input('hours')[$i]);
                            $time_str = $end_time->toTimeString();
                            $kidsDetails->end_time = $time_str;
                            $kidsDetails->save();
                            $num_of_kid++;
                        }
                    } else {
                        $kidsDetails = Kid::where('id', $row)->first();

                        $kidsDetails->user_id = $id;
                        $kidsDetails->name = ucfirst($request->input('kidName')[$i]);
                        $kidsDetails->kidPrice = $request->input('kid_price')[$i];
                        $kidsDetails->hours = $request->input('hours')[$i];
                        $end_time = Carbon\Carbon::parse(now()->format('H:i'))->addHour($request->input('hours')[$i]);
                        $time_str = $end_time->toTimeString();
                        $kidsDetails->end_time = $time_str;
                        $kidsDetails->save();
                        $num_of_kid++;
                    }
                }
                $i++;
            }
        }

        $addExtra = PaymentMethod::where('user_id', $id)->first();

        if ($request->input('payment_mode') == 1) {
            $addExtra->payment_method = "UPI";
        } elseif ($request->input('payment_mode') == 2) {
            $addExtra->payment_method = "Cash";
        } else {
            $addExtra->payment_method = "Debit/Credit";
        }
        $addExtra->gst18 = $request->input('gst18');
        if ($request->input('couponcode')) {
            $addExtra->couponcode = $request->input('couponcode');
            $addExtra->couponvalue = $request->input('couponvalue');
        }
        $addExtra->gst12 = $request->input('gst12');


        $addExtra->subTotal = $request->input('subTotal');

        $addExtra->amounts = $request->input('totalAmount');
        $addExtra->save();

        if ($request->input('add_extra')) {
            $product_add2 = new UserProduct();
            $product_add2->user_id = $id;
            $product_add2->branch_id = $branchId;
            $product_add2->product_name = 'Extra Time';
            $product_add2->price = $request->input('extra_time');
            $product_add2->save();

            $user = User::with('kid', 'event')->where('id', $id)->first();

            $start_datetime = isset($user['event']->Hours) ? $user['event']->Hours : null;
            $end_datetime = isset($user['kid']->end_time) ? $user['kid']->end_time : null;
            $booking_date = isset($user['event']->booking_date) ? $user['event']->booking_date : null;
            $start_date_time = $booking_date . ' ' . $start_datetime . ':00';

            $start_time_unix = strtotime($start_date_time);

            if (isset($user['kid']->hours) && $user['kid']->hours == 'Unlimited') {
                $hourQty = 1;
            } else {
                $hourQty = isset($user['kid']->hours) ? $user['kid']->hours : '';
            }

            if ($hourQty == "1.5") {
                $totalSecond = $hourQty * 3600;
            } else {
                $totalSecond = (int)$hourQty * 3600;
            }

            $end_time_unix_plus_one_hour = $start_time_unix + $totalSecond;
            $remaining_seconds = $end_time_unix_plus_one_hour - time();

            if ($remaining_seconds <= 0) {
                $remaining_seconds = 0;
            }

            $remaining_hours = floor($remaining_seconds / 3600);
            $remaining_minutes = floor(($remaining_seconds % 3600) / 60);
            $remaining_seconds = $remaining_seconds % 60;

            $remaining_time = $remaining_hours . " : " . $remaining_minutes . " : " . $remaining_seconds;

            $mobile = "91" . $userDetail->phone_no;
            $time_in = $start_datetime;
            $time_out = $end_datetime;
            $added_time = "0:30:00";
            $date = $booking_date;

            $msg = "Your Playtime has been extended Updated details are: Date: $date Time-In: $time_in Updated Time-Out: $time_out Added Time: $added_time Have a Gadget Free time of HappyOn Play!	";
            $template_id = "1707168001515557376";
            sendsms($mobile, $msg, $template_id);
        }

        if ($request->input('adult_qty')) {
            if ($request->input('adult_product_id') > 0) {
                $product_add = UserProduct::find($request->input('adult_product_id'));
            } else {
                $product_add = new UserProduct();
            }
            $product_add->user_id = $id;
            $product_add->branch_id = $branchId;
            if ($request->input('adult_qty') != null) {
                $product_add->product_name = 'Adult';
            }
            $price_adult = $request->input('price');
            if ($price_adult) {
                foreach ($price_adult as $priceAdult) {
                    $product_add->price = $priceAdult;
                }
            }

            $adult_qty = $request->input('adult_qty');
            if ($adult_qty) {
                foreach ($adult_qty as $adultQty) {
                    $product_add->qty = $adultQty;
                }
            }

            $product_add->save();
        }

        if (isset($request->product_quantity)) {
            foreach ($request->product_quantity as $key => $val) {
                $product_add = UserProduct::find($request->extra_product_id[$key]);
                if (is_null($product_add)) {
                    $product_add = new UserProduct();
                }
                $product_add->user_id = $id;
                $product_add->branch_id = $branchId;
                $product_add->product_name = $request->product_name[$key];
                $product_add->price = $request->price[$key];
                if ($request->product_name[$key] == 'Socks') {
                    $product_add->qty =  $val + count($kidids);
                } else {
                    $product_add->qty =  $val;
                }

                $product_add->save();
            }
        }

        $user__id = User::max('id');
        $customerKey = str_pad($user__id, 10, '0', STR_PAD_LEFT);
        $items = [];

        if (isset($request["product_name"]) && is_array($request["product_name"])) {
            for ($i = 0; $i < count($request["product_name"]); $i++) {
                $items[] = [
                    "name" => $request["product_name"][$i],
                    "id" => uniqid(),
                    "rate" => floatval($request["price"][$i]),
                    "quantity" => intval($request["product_quantity"][$i]),
                    "subtotal" => floatval($request["price"][$i]) * intval($request["product_quantity"][$i]),
                    "category" => "",
                    "addon" => []
                ];
            }
        }

        if (isset($request["kidName"]) && is_array($request["kidName"])) {
            for ($i = 0; $i < count($request["kidName"]); $i++) {
                $items[] = [
                    "name" => $request["kidName"][$i],
                    "id" => uniqid(),
                    "rate" => floatval($request["kid_price"][$i]),
                    "quantity" => intval($request["hours"][$i]),
                    "subtotal" => floatval($request["kid_price"][$i]),
                    "category" => "",
                    "addon" => []
                ];
            }
        }

        $postData = [
            "customer_key" =>  $addExtra->id,
            "merchant_id" => env('MERCHANT_ID'),
            "transaction" => [
                "id" => $addExtra->id,
                "type" => "dine_in",
                "source" => "online",
                "payment_type" => $addExtra->payment_method,
                "gross_amount" => $request->totalAmount,
                "net_amount" => $request->subTotal,
                "amount" => $request->totalAmount,
                "order_time" => $addExtra->updated_at,
                "items" => $items,
                "discount" => 0,
                "table_number" => '',
                "order_from" => "POS"
            ]
        ];
        // Make the POST request
        $response = Http::patch('https://api.reelo.io/v1/pos/integrations/e7499eb1-3ef1-4162-8dfb-8b5a55d3e6ba/update-bill', $postData);
        return redirect('/customers')->with('success', 'Successfully Updated!');
    }

    public function invoiceGenerate(Request $request)
    {
        $id = $request->id;
        $user = User::with('membership', 'userDetails', 'event', 'paymentInfo')->where('id', $id)->latest()->first();
        $paymentMethod = PaymentMethod::where('user_id', $id)->first();

        if ($user['userDetails']->membership_plan == 1) {
            $data = [
                'guardian_name' => $user->name,
                "payment_method" => $paymentMethod->payment_method,
                "date" => $user['membership']->date,
                "package_name" => $user['membership']->plan,
                "couponcode" => $user['paymentInfo']->couponcode,
                "couponvalue" => $user['paymentInfo']->couponvalue,
                "invoiceNo" => $paymentMethod->id,
                "subTotal" => $paymentMethod->subTotal,
                "gst18" => $paymentMethod->gst18,
                "gst12" => $paymentMethod->gst12,
                "totalAmount" => $paymentMethod->amounts,
            ];
            return view('receipt.membership-invoice', $data);
        } else {
            $kidValue = Kid::where('user_id', $id);
            $totalHours = $kidValue->first();
            $KidPrices = $kidValue->select('kidPrice')->first();
            $kidTotal = $kidValue->count();
            $totalKidPrice = $kidValue->sum('kidPrice');

            $userProducts = UserProduct::where(['user_id' => $id])->latest()->get();
            $in_time = strtotime($user['event']->Hours);
            $in_time = date("h:i A", $in_time);

            if ($user['kid']->end_time == "Unlimited") {
                $out_time = "Unlimited";
            } else {
                $out_time = strtotime($user['kid']->end_time);
                $out_time = date("h:i A", $out_time);
            }

            $distinctHour = Kid::where('user_id', $id)->distinct()->pluck('hours');
            $kidsInvoice = [];
            foreach ($distinctHour as $dist) {
                $kidTempData = Kid::where('user_id', $id);
                $KidPrices = $kidTempData->where('hours', $dist)->select('kidPrice')->first();
                $totalKidPrice = $kidTempData->where('hours', $dist)->sum('kidPrice');
                $kidTotal = $kidTempData->count();
                $kidValue = Kid::where('user_id', $id)->count();
                array_push($kidsInvoice, ['hours' => $dist, 'quantity' => $kidTotal, 'price' => $KidPrices->kidPrice, 'subtotal' => $totalKidPrice]);
            }
            $data = [
                'guardian_name' => $user->name,
                "payment_method" => $paymentMethod->payment_method,
                "date" => $user['event']->booking_date,
                "time" => $in_time,
                "end_time" => $out_time,
                "hours" => $user['kid']->hours,
                "couponcode" => $user['paymentInfo']->couponcode,
                "couponvalue" => $user['paymentInfo']->couponvalue,
                "invoiceNo" => $paymentMethod->id,
                "distinctHours" => $distinctHour,
                "kidTotal" => $kidValue,
                "totalKidPrice" => $totalKidPrice,
                "KidPrice" => $KidPrices['kidPrice'],
                "playtime" => $totalHours,
                "subTotal" => $paymentMethod->subTotal,
                "gst18" => $paymentMethod->gst18,
                "gst5" => $paymentMethod->gst5,
                "gst12" => $paymentMethod->gst12,
                "totalAmount" => $paymentMethod->amounts,
                'kidsInvoice' => $kidsInvoice,
                'userProducts' => $userProducts
            ];
            return view('receipt.index', $data);
        }
    }

    public function ticketsBookingReport(Request $request)
    {
        hasPermission('reports-ticket');
        $branchId = !empty($request->branch_id) && count($request->branch_id) > 0 ? $request->branch_id : (auth('admin')->user()->branch_id ? [strval(auth('admin')->user()->branch_id)] : null);
        if (isset($request->daterange) && $request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('y-m-d', strtotime($daterange[1]));

            $user_bookings = User::with('membership', 'kid', 'userDetails', 'event', 'paymentInfo', 'branch')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->where('role', '!=', 0);
            if ($branchId) {
                $user_bookings->whereIn('branch_id', $branchId);
            }
            $user_bookings = $user_bookings->get();

            $data = [];
            foreach ($user_bookings as $user) {
                if ($user['userDetails']->membership_plan == 0) {
                    array_push($data, $user);
                }
            }
            $user_bookings = $data;
            return view('happyView.tickets-booking-report', compact('user_bookings'));
        }
    }

    public function custmerView(Request $req, UserDataTable  $datatable)
    {
        return $datatable->with('data', $req)->render('happyView.customers');
    }
    // public function custmerView()
    // {
    //     hasPermission('tickets-manage');
    //     $user = User::with('membership', 'firstKid', 'userDetails', 'event', 'paymentInfo')->where('role', '!=', 0)->latest()->take(10)->get();
    //     return view('happyView.customers', compact('user'));
    // }


    public function custmerAllView(Request $req, CustomerDataTable $datatable)
    {
        hasPermission('customers');
        return $datatable->with('data', $req)->render('happyView.all-customer');
    }
    public function getPhoneList(Request $request)
    {
        $search = $request->post('query');


        $user = UserDetail::where('phone_no', 'LIKE', "%{$search}%")->latest()->limit(5)->get();

        $result = '';
        foreach ($user as $u) {

            $result .= '<a href="javascript:void(0);" onclick="fetchuserDetail(' . $u->user_id . ')"><p>' . $u->phone_no . '</p></a>';
        }
        echo $result;
    }

    public function getUserDetail(Request $request)
    {
        $user_id = $request->post('query');
        $user = User::with('kid', 'userDetails')->where('role', '!=', 0)->where('id', $user_id)->first();

        $userdetail = ['name' => $user['name'], 'phone_no' => $user['userDetails']->phone_no, 'dob' => $user['userDetails']->dob, 'nok' => $user['userDetails']->number_of_kids, 'kiddetail' => $user['kid']->name];
        // dd($user['userDetails']->dob);
        echo json_encode($userdetail);
    }

    public function getPriceKids(Request $request)
    {
        $today = Carbon\Carbon::now('Asia/Kolkata')->format('l');
        $branchId = $request->get('branch_id') ?? session('branch_id');

        $value = DayPrice::where('branch_id', $branchId)->where('Hour', $request->get('option'))->first();
        if ($value == null) {
            $value = DayPrice::whereNull('branch_id')->where('Hour', $request->get('option'))->first();
        }

        if ($value != null) {
            if ($today == "Monday") {
                $data = $value->monday;
            } elseif ($today == "Tuesday") {
                $data = $value->tuesday;
            } elseif ($today == "Wednesday") {
                $data = $value->wednesday;
            } elseif ($today == "Thursday") {
                $data = $value->thursday;
            } elseif ($today == "Friday") {
                $data = $value->friday;
            } elseif ($today == "Saturday") {
                $data = $value->saturday;
            } else {
                $data = $value->sunday;
            }
            return response()->json($data);
        } else {
            echo 0;
        }
    }

    public function addUser(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        if ($request->input('user_name') == null) {
            return back()->with('error', 'Please Provide user_name');
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->username = $request->input('user_name');
        $user->branch_id = $branchId;
        $user->email = $request->input('user_name') . '@gmail.com';
        $user->assign = $request->input('assign');
        $user->acess = $request->input('access');
        $user->role = $request->input('role');

        $user->save();
        if ($user->id) {
            return back()->with('success', 'Success! User created');
        }
    }

    public function deleteUser(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        User::whereIn('id', $selectedItems)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteCustomer(Request $request)
    {
        try {
            if (can('tickets-delete') || can('customers-delete')) {
                $selectedItems = $request->input('selectedItems');
                // PaymentMethod::whereIn('user_id', $selectedItems)->delete();
                // UserProduct::whereIn('user_id', $selectedItems)->delete();
                // Kid::whereIn('user_id', $selectedItems)->delete();
                User::whereIn('id', $selectedItems)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }

    public function sales(Request $request)
    {
        hasPermission('sales-report');
        $branchId = !empty($request->branch_id) && count($request->branch_id) > 0 ? $request->branch_id : (auth('admin')->user()->branch_id ? [strval(auth('admin')->user()->branch_id)] : null);
        if (isset($request->daterange) && $request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('y-m-d', strtotime($daterange[1]));

            $user_bookings = User::with('kids', 'userDetails', 'event', 'paymentInfo', 'branch')
                ->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])
                ->where('role', '!=', 0)
                ->whereHas('userDetails', function ($q) {
                    $q->where('membership_plan', 0);
                })
                ->when($branchId, function ($q) use ($branchId) {
                    $q->whereIn('branch_id', (array) $branchId);
                })->get();
            // dd($user_bookings[0]->event->booking_date);
            return view('happyView.sales-report', compact('user_bookings'));
        }
    }

    public function update(Request $request)
    {
        hasPermission('tickets-edit');
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        if (!$branchId) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }
        $id = $request->input('id');
        $user = User::where('id', $id)->first();
        $booking_date = isset($user['event']->booking_date) ? $user['event']->booking_date : null;
        $now = now()->format('Y-m-d');    
        if ($booking_date < $now) {
            return redirect('/customers');
        }
        $userDetail = UserDetail::where('user_id', $id)->first();

        // update payments
        $paymentMethod = PaymentMethod::where('user_id', $id)->first();
        $oldPayment = $paymentMethod->amounts;

        $paymentMethod->amounts = $request->input('newTotalAmount');
        $paymentMethod->save();

        // update kids
        $kidids = $request->input('kidids');
        if ($kidids) {
            foreach ($kidids as $i => $row) {
                $kidsDetails = Kid::where('id', $row)->first();

                // price
                $kidsDetails->kidPrice = $request->input('kid_price')[$i];

                // hours
                $newHours = $request->input('hours')[$i];
                if (str_contains($newHours, 'hours')) {
                    $search = ' hours';
                    $newHours = str_replace($search, '', $newHours);
                }
                $kidsDetails->hours = $newHours;

                // end_time
                if ($request->input('extra_time_select')[$i] != null) {
                    $old_end_time = $kidsDetails->end_time;
                    $end_time = Carbon\Carbon::parse($old_end_time)->addMinutes($request->input('extra_time_select')[$i]);
                    $time_str = $end_time->toTimeString();
                    $kidsDetails->end_time = $time_str;
                }
                $kidsDetails->save();
            }
        }

        // if extra time (30 Or 60 ) brought, added in user_product
        if ($oldPayment !== $request->input('newTotalAmount')) {
            $newProd_extraTime = new UserProduct();
            $newProd_extraTime->user_id = $id;
            $newProd_extraTime->branch_id = $branchId;
            $newProd_extraTime->product_name = 'Extra Time';
            $newProd_extraTime->price = ($request->input('newTotalAmount') - $oldPayment);
            $newProd_extraTime->save();
        }

        // For sms
        $user = User::with('kid', 'event')->where('id', $id)->first();
        $start_datetime = isset($user['event']->Hours) ? $user['event']->Hours : null;
        $end_datetime = isset($user['kid']->end_time) ? $user['kid']->end_time : null;
        $booking_date = isset($user['event']->booking_date) ? $user['event']->booking_date : null;

        $mobile = "91" . $userDetail->phone_no;
        $added_time = "0:" . $request->input('extra_time_select')[0] . ":00";
        $msg = "Your Playtime has been extended Updated details are: Date: $booking_date Time-In: $start_datetime Updated Time-Out: $end_datetime Added Time: $added_time Have a Gadget Free time of HappyOn Play!	";
        $template_id = "1707168001515557376";
        sendsms($mobile, $msg, $template_id);

        //for payment       
        $items = [];

        if (isset($request["product_name"]) && is_array($request["product_name"])) {
            for ($i = 0; $i < count($request["product_name"]); $i++) {
                $items[] = [
                    "name" => $request["product_name"][$i],
                    "id" => uniqid(),
                    "rate" => floatval($request["price"][$i]),
                    "quantity" => intval($request["product_quantity"][$i]),
                    "subtotal" => floatval($request["price"][$i]) * intval($request["product_quantity"][$i]),
                    "category" => "",
                    "addon" => []
                ];
            }
        }

        if (isset($request["kidName"]) && is_array($request["kidName"])) {
            for ($i = 0; $i < count($request["kidName"]); $i++) {
                $items[] = [
                    "name" => $request["kidName"][$i],
                    "id" => uniqid(),
                    "rate" => floatval($request["kid_price"][$i]),
                    "quantity" => intval($request["hours"][$i]),
                    "subtotal" => floatval($request["kid_price"][$i]),
                    "category" => "",
                    "addon" => []
                ];
            }
        }

        $postData = [
            "customer_key" =>  $paymentMethod->id,
            "merchant_id" => env('MERCHANT_ID'),
            "transaction" => [
                "id" => $paymentMethod->id,
                "type" => "dine_in",
                "source" => "online",
                "payment_type" => $paymentMethod->payment_method,
                "gross_amount" => $request->totalAmount,
                "net_amount" => $request->subTotal,
                "amount" => $request->totalAmount,
                "order_time" => $paymentMethod->updated_at,
                "items" => $items,
                "discount" => 0,
                "table_number" => '',
                "order_from" => "POS"
            ]
        ];
        // Make the POST request
        $response = Http::patch('https://api.reelo.io/v1/pos/integrations/e7499eb1-3ef1-4162-8dfb-8b5a55d3e6ba/update-bill', $postData);
        return redirect('/customers')->with('success', 'Successfully Updated!');
    }
}
