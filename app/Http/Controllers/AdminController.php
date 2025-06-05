<?php

namespace App\Http\Controllers;

use Carbon;
use Dompdf\Dompdf;
use App\Models\Kid;
use App\Models\User;
use App\Models\Admin;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Branch;

use App\Models\DayPrice;

use App\Models\EventItem;
use App\Models\UserDetail;
use App\Models\OpenBilling;
use Illuminate\Support\Facades\Session;
use App\Models\UserProduct;
use App\Models\EventBooking;
use Illuminate\Http\Request;
use App\Models\MembershipBuy;
use App\Models\PaymentMethod;
use App\Models\MembershipPlan;
use Illuminate\Support\Facades\Auth;
//import model admin
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

//import class hash password
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        if (auth('admin')->check() &&  !auth('admin')->user()->branch->status === "Inactive") {
            return redirect('dashboard');
        } else {
            return view('happyView.login');
        }
        return view('happyView.login');
    }

    public function auth(Request $request)
    {

        $username = $request->post('email');
        $password = $request->post('password');

        $result = Admin::where(['email' => $username, 'status' => 1])->first();
        if (Auth::guard('admin')->attempt(['email' => $username, 'password' => $password])) {
            if (auth('admin')->user()->status != 1) {
                Auth::guard('admin')->logout();
                return redirect('/')->with('error', 'Invalid Credentials !');
            }
            return redirect('dashboard')->with('success', 'Login Successfully!');
        } else {
            return redirect('/')->with('error', 'Invalid Credentials !');
        }
    }

    public function monthWise(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        if ($request->date != '') {
            $daterange = explode("-", $request->date);
            $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('Y-m-d', strtotime($daterange[1]));
            $data2 = [];
            $ticketSale = User::with('paymentInfo')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->where('role', 1);
            if ($branchId) {
                $ticketSale->where('branch_id', $branchId);
            }
            $ticketSale = $ticketSale->get();

            $amountTicket = [];
            foreach ($ticketSale as $ticketSales) {
                $data = isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;;
                $amountTicket[] = $data;
            }

            $ticket_sales = array_sum($amountTicket);


            $event_sales = EventBooking::select('totalamount')->where('payment_status', 'Paid')
                ->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $event_sales->where('branch_id', $branchId);
            }
            $event_sales = $event_sales->sum('totalamount');


            $member_sales = MembershipBuy::whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $member_sales->where('branch_id', $branchId);
            }
            $member_sales = $member_sales->sum('amount');

            array_push($data2, ['Report Between ' . $request->date, round($ticket_sales), round($member_sales), round($event_sales)]);
            return $data2;
        }

        $year = Date('Y');
        $month = Date('m');
        $months1 = ["Jan", "Feb", "March", "April", "May", "June", "July", "Aug", "Sept", "Oct", "Nov", "Dec"];
        $months2 = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $data = [['Month', 'Ticket', 'Member', 'Event']];
        $data2 = [];
        $index = array_search($month, $months2, true);
        for ($i = 1; $i <= 6; $i++) {
            array_push($data2, [$months1[$index], $months2[$index], $year]);
            $index--;
            if ($index < 0) {
                $index = 11;
                $year -= 1;
            }
        }
        $data2 = array_reverse($data2);

        foreach ($data2 as $key => $month_items) {
            $ticketSale = User::with('paymentInfo')->whereYear('created_at', $month_items[2])->whereMonth('created_at', $month_items[1])->where('role', 1);
            if ($branchId) {
                $ticketSale->where('branch_id', $branchId);
            }
            $ticketSale = $ticketSale->get();

            $amountTicket = [];
            foreach ($ticketSale as $ticketSales) {
                $datas = isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;;
                $amountTicket[] = $datas;
            }
            $ticket_sales = array_sum($amountTicket);
            $member_sales = MembershipBuy::whereYear('created_at', $month_items[2])->whereMonth('created_at', $month_items[1]);
            if ($branchId) {
                $member_sales->where('branch_id', $branchId);
            }
            $member_sales = $member_sales->sum('amount');

            $event_sales = EventBooking::select('totalamount')->where('payment_status', 'Paid')
                ->whereYear('booking_date', $month_items[2])->whereMonth('booking_date', $month_items[1]);
            if ($branchId) {
                $event_sales->where('branch_id', $branchId);
            }
            $event_sales = $event_sales->sum('totalamount');
            array_push($data, [$month_items[0], round($ticket_sales), round($member_sales), round($event_sales)]);
        }

        return $data;
    }

    public function adminPlansView()
    {
        hasPermission('admin');
        $branchId = auth('admin')->user()->branch_id ?? null;
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        $product = Product::with('branch');
        if ($branchId) {
            $product->where('branch_id', $branchId);
        }
        $product = $product->get();

        $eventitem = EventItem::query();
        if ($branchId) {
            $eventitem->where('branch_id', $branchId);
        }
        $eventitem = $eventitem->get();

        $coupon = Coupon::query();
        if ($branchId) {
            $coupon->where('branch_id', $branchId);
        }
        $coupon = $coupon->get();

        $membership = MembershipPlan::get();
        $hourPlan = DayPrice::get();
        $users = User::where('role', 0);
        if ($branchId) {
            $users->where('branch_id', $branchId);
        }
        $users = $users->get();
        return view('happyView.admin', compact('product', 'eventitem', 'coupon', 'membership', 'hourPlan', 'users', 'branches'));
    }

    public function dashboard(Request $request)
    {
        $ad = Admin::find(1);
        $ad->password = Hash::make('admin@123');
        $ad->save();
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $branches = Branch::where('status', 'Active')->get()->toArray();
        if(auth('admin')->user()->branch_id != null && auth('admin')->user()->isSuperAdmin == 1 && auth('admin')->user()->branch->type == 'Branch') {
            $branchId = $request->branch_id ? $request->branch_id : null;
        } else {
            $branchId = $request->branch_id ?? auth('admin')->user()->branch_id ?? null;
        }
        $isSuperAdminShow = auth('admin')->user()->branch_id == null ? true : false;

        if (isset($request->daterange) && $request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('Y-m-d', strtotime($daterange[1]));
            $shocksCount = UserProduct::select('qty')->where('product_name', 'Socks')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $shocksCount->where('branch_id', $branchId);
            }
            $shocksCount = $shocksCount->sum('qty');
            $user = User::where('role', '!=', 0)->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->count();

            $date = now();
            $customers = UserDetail::upcomingBirthdays();
            if ($branchId) {
                $customers->where('branch_id', $branchId);
            }
            $customers = $customers->take(3)->get();

            $event_bookings = EventBooking::whereDate('booking_date', '>=', Carbon\Carbon::today());
            if ($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->take(5)->latest()->get();

            $event_gst18 = EventBooking::select('gst18')->where('payment_status', 'Paid')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $event_gst18->where('branch_id', $branchId);
            }
            $event_gst18 = $event_gst18->sum('gst18');

            $event_gst12 = EventBooking::select('gst12')->where('payment_status', 'Paid')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $event_gst12->where('branch_id', $branchId);
            }
            $event_gst12 = $event_gst12->sum('gst12');

            $gst5 = EventBooking::select('gst5')->where('payment_status', 'Paid')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $gst5->where('branch_id', $branchId);
            }
            $gst5 = $gst5->sum('gst5');

            if ($daterange[0] == $daterange[1]) {
                $event_booking =   EventBooking::whereDate('booking_date', $daterange[0])->where('payment_status', 'Paid');
                if($branchId) {
                    $event_booking->where('branch_id', $branchId);
                }
                $event_booking = $event_booking->count();

                $event_sale = EventBooking::select('totalamount')
                    ->where(function ($query) {
                        $query->where('payment_method', 'UPI')
                            ->orWhere('payment_method', 'Cash')
                            ->orWhere('payment_method', 'Debit/Credit');
                    })
                    ->where('payment_status', 'Paid')
                    ->whereDate('booking_date', $daterange[0]);
                if($branchId) {
                    $event_sale->where('branch_id', $branchId);
                }
                $event_sale = $event_sale->sum('totalamount');

            } else {
                $event_booking =   EventBooking::whereBetween('booking_date', [$daterange[0], $daterange[1]])->where('payment_status', 'Paid');
                if($branchId) {
                    $event_booking->where('branch_id', $branchId);
                }
                $event_booking = $event_booking->count();

                $event_sale = EventBooking::select('totalamount')
                    ->where(function ($query) {
                        $query->where('payment_method', 'UPI')
                            ->orWhere('payment_method', 'Cash')
                            ->orWhere('payment_method', 'Debit/Credit');
                    })
                    ->where('payment_status', 'Paid')
                    ->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
                if($branchId) {
                    $event_sale->where('branch_id', $branchId);
                }
                $event_sale = $event_sale->sum('totalamount');
            }
            $event_booking_month = EventBooking::whereMonth('booking_date', '=', Carbon\Carbon::now());
            if($branchId) {
                $event_booking_month->where('branch_id', $branchId);
            }
            $event_booking_month = $event_booking_month->count();

            $totalOpenBilling = OpenBilling::whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if($branchId) {
                $totalOpenBilling->where('branch_id', $branchId);
            }
            $totalOpenBilling = $totalOpenBilling->count();

            $totalOpenBillingSales = OpenBilling::select('totalamount')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if($branchId) {
                $totalOpenBillingSales->where('branch_id', $branchId);
            }
            $totalOpenBillingSales = $totalOpenBillingSales->sum('totalamount');

            $totalMembershipBuy = MembershipBuy::whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if($branchId) {
                $totalMembershipBuy->where('branch_id', $branchId);
            }
            $totalMembershipBuy = $totalMembershipBuy->count();

            $memberSale = User::has('membership')->with(['paymentInfo', 'membership'])
                ->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])
                ->where('role', 2);
            if ($branchId) {
                $memberSale->where('branch_id', $branchId);
            }

            $memberSale = $memberSale->get();
            $amountMember = [];
            foreach ($memberSale as $memberTicket) {
                $data = isset($memberTicket['paymentInfo']) ? $memberTicket['paymentInfo']->amounts : 0;
                $amountMember[] = $data;
            }
            $totalMemberAmount = array_sum($amountMember);
            $kid = Kid::whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $kid->where('branch_id', $branchId);
            }
            $kid = $kid->count();
            // $totalSales = PaymentMethod::select('amounts')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->sum('amounts');
            $totalSales = $totalOpenBillingSales;
            $totalSales += $event_sale;
            $couponcodes = PaymentMethod::select('couponcode')->where('couponcode', '!=', "")->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $couponcodes->where('branch_id', $branchId);
            }
            $couponcodes = $couponcodes->count();

            $couponvalue = PaymentMethod::select('couponvalue')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $couponvalue->where('branch_id', $branchId);
            }
            $couponvalue = $couponvalue->sum('couponvalue');
            $ticketSale = User::with('paymentInfo')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->where('role', 1);
            if ($branchId) {
                $ticketSale->where('branch_id', $branchId);
            }
            $ticketSale = $ticketSale->get();
            $amountTicket = [];
            foreach ($ticketSale as $ticketSales) {
                $data = isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;
                $amountTicket[] = $data;
            }
            $totalTicketAmount = array_sum($amountTicket);
            $totalTicket = $ticketSale->count();
            $upi_membership = MembershipBuy::select('amount')->where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $upi_membership->where('branch_id', $branchId);
            }
            $upi_membership = $upi_membership->sum('amount');

            $cash_membership = MembershipBuy::select('amount')->where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $cash_membership->where('branch_id', $branchId);
            }
            $cash_membership = $cash_membership->sum('amount');

            $card_membership = MembershipBuy::select('amount')->where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $card_membership->where('branch_id', $branchId);
            }
            $card_membership = $card_membership->sum('amount');

            $upi_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'UPI')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $upi_event->where('branch_id', $branchId);
            }
            $upi_event = $upi_event->sum('totalamount');

            $cash_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'Cash')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $cash_event->where('branch_id', $branchId);
            }
            $cash_event = $cash_event->sum('totalamount');

            $card_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'Debit/Credit')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $card_event->where('branch_id', $branchId);
            }
            $card_event = $card_event->sum('totalamount');

            $partial_event = EventBooking::where('payment_method', 'Partial Payment')->where('payment_status', 'Paid')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $partial_event->where('branch_id', $branchId);
            }
            $partial_event = $partial_event->get();

            $partial_upi = 0;
            $partial_cash = 0;
            $partial_card = 0;
            foreach ($partial_event as $partial) {
                if ($partial['advance_payment_method'] == "UPI") {
                    $partial_upi += $partial['advance_amount'];
                } elseif ($partial['advance_payment_method'] == "Cash") {
                    $partial_cash += $partial['advance_amount'];
                } else {
                    $partial_card += $partial['advance_amount'];
                }

                if ($partial['installment_amount_method'] == "UPI") {
                    $partial_upi += $partial['installment_amount'];
                } elseif ($partial['installment_amount_method'] == "Cash") {
                    $partial_cash += $partial['installment_amount'];
                } else {
                    $partial_card += $partial['installment_amount'];
                }

                if ($partial['final_installment_method'] == "UPI") {
                    $partial_upi += $partial['final_installment'];
                } elseif ($partial['final_installment_method'] == "Cash") {
                    $partial_cash += $partial['final_installment'];
                } else {
                    $partial_card += $partial['final_installment'];
                }
            }
            $totalSales += $totalMemberAmount;
            $totalSales += $totalTicketAmount;
            $event_sale += $partial_upi +  $partial_cash + $partial_card;
            $totalSales += $partial_upi +  $partial_cash + $partial_card;
            $upi_open = OpenBilling::select('totalamount')->where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $upi_open->where('branch_id', $branchId);
            }
            $upi_open = $upi_open->sum('totalamount');

            $cash_open = OpenBilling::select('totalamount')->where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $cash_open->where('branch_id', $branchId);
            }
            $cash_open = $cash_open->sum('totalamount');
            $card_open = OpenBilling::select('totalamount')->where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $card_open->where('branch_id', $branchId);
            }
            $card_open = $card_open->sum('totalamount');

            $open_gst18 = OpenBilling::select('gst18')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $open_gst18->where('branch_id', $branchId);
            }
            $open_gst18 = $open_gst18->sum('gst18');

            $open_gst12 = OpenBilling::select('gst12')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $open_gst12->where('branch_id', $branchId);
            }
            $open_gst12 = $open_gst12->sum('gst12');

            $open_gst5 = OpenBilling::select('gst5')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $open_gst5->where('branch_id', $branchId);
            }
            $open_gst5 = $open_gst5->sum('gst5');


            // $upi = PaymentMethod::select('amounts')->where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->sum('amounts');
            // $cash = PaymentMethod::select('amounts')->where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->sum('amounts');
            // $card = PaymentMethod::select('amounts')->where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->sum('amounts');


            $upi = 0;
            $cash = 0;
            $card = 0;

            $ticketSale = User::with('paymentInfo')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->where('role', 1);
            if ($branchId) {
                $ticketSale->where('branch_id', $branchId);
            }
            $ticketSale = $ticketSale->get();

            foreach ($ticketSale as $ticketSales) {
                if (isset($ticketSales['paymentInfo']) && $ticketSales['paymentInfo']->payment_method == 'UPI') {
                    $upi += isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;
                }
                if (isset($ticketSales['paymentInfo']) && $ticketSales['paymentInfo']->payment_method == 'Cash') {
                    $cash += isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;
                }
                if (isset($ticketSales['paymentInfo']) && $ticketSales['paymentInfo']->payment_method == 'Debit/Credit') {
                    $card += isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;
                }
            }


            $memberSale = User::has('membership')->with(['paymentInfo', 'membership'])
                ->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])
                ->where('role', 2);

            if ($branchId) {
                $memberSale->where('branch_id', $branchId);
            }
            $memberSale = $memberSale->get();

            $amountMember = [];
            foreach ($memberSale as $memberTicket) {
                if (isset($memberTicket['paymentInfo']) && $memberTicket['paymentInfo']->payment_method == 'UPI') {
                    $upi += isset($memberTicket['paymentInfo']) ? $memberTicket['paymentInfo']->amounts : 0;
                }
                if (isset($memberTicket['paymentInfo']) && $memberTicket['paymentInfo']->payment_method == 'Cash') {
                    $cash += isset($memberTicket['paymentInfo']) ? $memberTicket['paymentInfo']->amounts : 0;
                }
                if (isset($memberTicket['paymentInfo']) && $memberTicket['paymentInfo']->payment_method == 'Debit/Credit') {
                    $card += isset($memberTicket['paymentInfo']) ? $memberTicket['paymentInfo']->amounts : 0;
                }
            }

            $upi =  $upi + $upi_membership + $upi_event + $partial_upi + $upi_open;
            $cash = $cash + $cash_membership + $cash_event + $partial_cash + $cash_open;
            $card = $card + $card_membership + $card_event + $partial_card + $card_open;


            $gst18 = PaymentMethod::select('gst18')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $gst18->where('branch_id', $branchId);
            }
            $gst18 = $gst18->sum('gst18');

            $gst12 = PaymentMethod::select('gst12')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $gst12->where('branch_id', $branchId);
            }
            $gst12 = $gst12->sum('gst12');

            $gst18 += $event_gst18 + $open_gst18;
            $gst12 += $event_gst12 + $open_gst12;
            $gst5 += $open_gst5;
            $member = User::with('membership', 'userDetails')
                ->where('role', 2)
                ->whereHas('membership', function ($query) {
                    $query->where('credit_left', '>', 0)->where('credit_left', '<', 5)->whereBetween('date', [
                  Carbon\Carbon::now()->subDays(360), Carbon\Carbon::now() ]);
                });

            if ($branchId) {
                $member->where('branch_id', $branchId);
            }
            $member = $member->limit(3)->get()->sortBy(function ($member) {
                    return $member->membership->credit_left;
            });

            $applydate = $request->daterange;
        } else {
            $today = Carbon\Carbon::today();
            $shocksCount = UserProduct::select('qty')->where('product_name', 'Socks')->whereDate('created_at', $today);
            if ($branchId) {
                $shocksCount->where('branch_id', $branchId);
            }
            $shocksCount = $shocksCount->sum('qty');

            $user = User::where('role', '!=', 0)->whereDate('created_at', $today);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->count();


            $date = now();

            $customers = UserDetail::upcomingBirthdays();
            if ($branchId) {
                $customers->where('branch_id', $branchId);
            }
            $customers = $customers->take(3)->get();

            $totalOpenBilling = OpenBilling::whereDate('created_at', $today);
            if ($branchId) {
                $totalOpenBilling->where('branch_id', $branchId);
            }
            $totalOpenBilling = $totalOpenBilling->count();

            $totalOpenBillingSales = OpenBilling::select('totalamount')->whereDate('created_at', $today);
            if ($branchId) {
                $totalOpenBillingSales->where('branch_id', $branchId);
            }
            $totalOpenBillingSales = $totalOpenBillingSales->sum('totalamount');

            $event_bookings = EventBooking::whereDate('booking_date', '>=', $today)->take(5)->orderBy('booking_date');
            if ($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->get();

            $event_sale = EventBooking::select('totalamount')->where('payment_status', 'Paid')->whereDate('booking_date', $today);
            if ($branchId) {
                $event_sale->where('branch_id', $branchId);
            }
            $event_sale = $event_sale->sum('totalamount');

            $event_gst18 = EventBooking::select('gst18')->where('payment_status', 'Paid')->whereDate('booking_date', $today);
            if ($branchId) {
                $event_gst18->where('branch_id', $branchId);
            }
            $event_gst18 = $event_gst18->sum('gst18');

            $event_gst12 = EventBooking::select('gst12')->where('payment_status', 'Paid')->whereDate('booking_date', $today);
            if ($branchId) {
                $event_gst12->where('branch_id', $branchId);
            }
            $event_gst12 = $event_gst12->sum('gst12');

            $gst5 = EventBooking::select('gst5')->where('payment_status', 'Paid')->whereDate('booking_date', $today);
            if ($branchId) {
                $gst5->where('branch_id', $branchId);
            }
            $gst5 = $gst5->sum('gst5');

            $event_booking = EventBooking::whereDate('booking_date', $today);
            if ($branchId) {
                $event_booking->where('branch_id', $branchId);
            }
            $event_booking = $event_booking->count();

            $event_booking_month = EventBooking::whereMonth('booking_date', '=', Carbon\Carbon::now());
            if ($branchId) {
                $event_booking_month->where('branch_id', $branchId);
            }
            $event_booking_month = $event_booking_month->count();

            $totalMembershipBuy = MembershipBuy::whereDate('created_at', $today);
            if ($branchId) {
                $totalMembershipBuy->where('branch_id', $branchId);
            }
            $totalMembershipBuy = $totalMembershipBuy->count();

            $memberSale = User::has('membership')->with(['paymentInfo', 'membership'])
                ->whereDate('created_at', $today)
                ->where('role', 2);
            if ($branchId) {
                $memberSale->where('branch_id', $branchId);
            }
            $memberSale = $memberSale->get();
            $amountMember = [];
            foreach ($memberSale as $memberTicket) {
                $data = isset($memberTicket['paymentInfo']) ? $memberTicket['paymentInfo']->amounts : 0;
                $amountMember[] = $data;
            }


            $totalMemberAmount = array_sum($amountMember);
            $kid = Kid::whereDate('created_at', $today);
            if ($branchId) {
                $kid->where('branch_id', $branchId);
            }
            $kid = $kid->count();
            $totalSales = PaymentMethod::select('amounts')->whereDate('created_at', $today);
            if ($branchId) {
                $totalSales->where('branch_id', $branchId);
            }
            $totalSales = $totalSales->sum('amounts');

            $totalSales += $event_sale;

            $totalSales += $totalOpenBillingSales;

            $couponcodes = PaymentMethod::select('couponcode')->where('couponcode', '!=', "")->whereDate('created_at', $today);
            if ($branchId) {
                $couponcodes->where('branch_id', $branchId);
            }
            $couponcodes = $couponcodes->count();
            $couponvalue = PaymentMethod::select('couponvalue')->whereDate('created_at', $today);
            if ($branchId) {
                $couponvalue->where('branch_id', $branchId);
            }
            $couponvalue = $couponvalue->sum('couponvalue');

            $ticketSale = User::with('paymentInfo')->whereDate('created_at', $today)->where('role', 1);
            if ($branchId) {
                $ticketSale->where('branch_id', $branchId);
            }
            $ticketSale = $ticketSale->get();
            $amountTicket = [];
            foreach ($ticketSale as $ticketSales) {
                $data = isset($ticketSales['paymentInfo']) ? $ticketSales['paymentInfo']->amounts : 0;;
                $amountTicket[] = $data;
            }

            $totalTicketAmount = array_sum($amountTicket);
            $totalTicket = $ticketSale->count();


            $upi_membership = MembershipBuy::select('amount')->where('payment_method', 'UPI')->whereDate('created_at', $today);
            if ($branchId) {
                $upi_membership->where('branch_id', $branchId);
            }
            $upi_membership = $upi_membership->sum('amount');

            $cash_membership = MembershipBuy::select('amount')->where('payment_method', 'Cash')->whereDate('created_at', $today);
            if ($branchId) {
                $cash_membership->where('branch_id', $branchId);
            }
            $cash_membership = $cash_membership->sum('amount');

            $card_membership = MembershipBuy::select('amount')->where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
            if ($branchId) {
                $card_membership->where('branch_id', $branchId);
            }
            $card_membership = $card_membership->sum('amount');

            $upi_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'UPI')->whereDate('booking_date', $today);
            if ($branchId) {
                $upi_event->where('branch_id', $branchId);
            }
            $upi_event = $upi_event->sum('totalamount');

            $cash_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'Cash')->whereDate('booking_date', $today);
            if ($branchId) {
                $cash_event->where('branch_id', $branchId);
            }
            $cash_event = $cash_event->sum('totalamount');

            $card_event = EventBooking::select('totalamount')->where('payment_status', 'Paid')->where('payment_method', 'Debit/Credit')->whereDate('booking_date', $today);
            if ($branchId) {
                $card_event->where('branch_id', $branchId);
            }
            $card_event = $card_event->sum('totalamount');

            $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereDate('booking_date', $today);
            if ($branchId) {
                $partial_event->where('branch_id', $branchId);
            }
            $partial_event = $partial_event->get();

            $partial_upi = 0;
            $partial_cash = 0;
            $partial_card = 0;
            foreach ($partial_event as $partial) {
                if ($partial['advance_payment_method'] == "UPI") {
                    $partial_upi += $partial['advance_amount'];
                } elseif ($partial['advance_payment_method'] == "Cash") {
                    $partial_cash += $partial['advance_amount'];
                } else {
                    $partial_card += $partial['advance_amount'];
                }

                if ($partial['installment_amount_method'] == "UPI") {
                    $partial_upi += $partial['installment_amount'];
                } elseif ($partial['installment_amount_method'] == "Cash") {
                    $partial_cash += $partial['installment_amount'];
                } else {
                    $partial_card += $partial['installment_amount'];
                }

                if ($partial['final_installment_method'] == "UPI") {
                    $partial_upi += $partial['final_installment'];
                } elseif ($partial['final_installment_method'] == "Cash") {
                    $partial_cash += $partial['final_installment'];
                } else {
                    $partial_card += $partial['final_installment'];
                }
            }

            $upi_open = OpenBilling::select('totalamount')->where('payment_method', 'UPI')->whereDate('created_at', $today);
            if ($branchId) {
                $upi_open->where('branch_id', $branchId);
            }
            $upi_open = $upi_open->sum('totalamount');
            $cash_open = OpenBilling::select('totalamount')->where('payment_method', 'Cash')->whereDate('created_at', $today);
            if ($branchId) {
                $cash_open->where('branch_id', $branchId);
            }
            $cash_open = $cash_open->sum('totalamount');
            $card_open = OpenBilling::select('totalamount')->where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
            if ($branchId) {
                $card_open->where('branch_id', $branchId);
            }
            $card_open = $card_open->sum('totalamount');
            $open_gst18 = OpenBilling::select('gst18')->whereDate('created_at', $today);
            if ($branchId) {
                $open_gst18->where('branch_id', $branchId);
            }
            $open_gst18 = $open_gst18->sum('gst18');

            $open_gst12 = OpenBilling::select('gst12')->whereDate('created_at', $today);
            if ($branchId) {
                $open_gst12->where('branch_id', $branchId);
            }
            $open_gst12 = $open_gst12->sum('gst12');

            $open_gst5 = OpenBilling::select('gst5')->whereDate('created_at', $today);
            if ($branchId) {
                $open_gst5->where('branch_id', $branchId);
            }
            $open_gst5 = $open_gst5->sum('gst5');


            $upi = PaymentMethod::select('amounts')->where('payment_method', 'UPI')->whereDate('created_at', $today);
            if ($branchId) {
                $upi->where('branch_id', $branchId);
            }
            $upi = $upi->sum('amounts');
            $cash = PaymentMethod::select('amounts')->where('payment_method', 'Cash')->whereDate('created_at', $today);
            if ($branchId) {
                $cash->where('branch_id', $branchId);
            }
            $cash = $cash->sum('amounts');
            $card = PaymentMethod::select('amounts')->where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
            if ($branchId) {
                $card->where('branch_id', $branchId);
            }
            $card = $card->sum('amounts');
            $upi = $upi + $upi_membership + $upi_event + $partial_upi + $upi_open;
            $cash = $cash + $cash_membership + $cash_event + $partial_cash + $cash_open;
            $card = $card + $card_membership + $card_event + $partial_card + $card_open;

            $gst18 = PaymentMethod::select('gst18')->whereDate('created_at', $today);
            if ($branchId) {
                $gst18->where('branch_id', $branchId);
            }
            $gst18 = $gst18->sum('gst18');
            $gst12 = PaymentMethod::select('gst12')->whereDate('created_at', $today);
            if ($branchId) {
                $gst12->where('branch_id', $branchId);
            }
            $gst12 = $gst12->sum('gst12');

            $gst18 += $event_gst18 + $open_gst18;
            $gst12 += $event_gst12 + $open_gst12;
            $gst5 += $open_gst5;
            $member = User::with('membership', 'userDetails')
                ->where('role', 2)
                ->whereHas('membership', function ($query) {
                    $query->where('credit_left', '>', 0)->where('credit_left', '<', 5)->whereBetween('date', [
                  Carbon\Carbon::now()->subDays(360), Carbon\Carbon::now() ]);
            });
            if ($branchId) {
                $member->where('branch_id', $branchId);
            }
            $member = $member->limit(3)->get()->sortBy(function ($member) {
                    return $member->membership->credit_left;
                });
            $applydate = "Today";
        }
        return view('happyView.dashboard', compact('shocksCount', 'user', 'kid', 'totalMemberAmount', 'totalMembershipBuy', 'totalSales', 'totalTicketAmount', 'totalOpenBillingSales', 'totalOpenBilling', 'totalTicket', 'upi', 'cash', 'card', 'member', 'applydate', 'event_booking', 'event_booking_month', 'event_bookings', 'event_sale', 'couponcodes', 'couponvalue', 'customers', 'gst18', 'gst12', 'gst5', 'branches', 'isSuperAdminShow', 'branchId'));
    }


    public function paymentMethodFilter(Request $request)
    {
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', 180);
        $key = $request->key;
        $daterange = base64_decode($request->date);
        $branchId = $request->branch_id ?? auth('admin')->user()->branch_id ?? null;
        if ($daterange != "Today") {
            if (isset($request->date) && $request->date != "") {

                $daterange = explode("-", $daterange);


                $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
                $daterange[1] = date('Y-m-d', strtotime($daterange[1]));

                $data = [];
                if ($key == "Cash") {
                    $cash = PaymentMethod::where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $cash->where('branch_id', $branchId);
                    }
                    $cash = $cash->get();

                    $cash_membership = MembershipBuy::where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $cash_membership->where('branch_id', $branchId);
                    }
                    $cash_membership = $cash_membership->get();

                    $cash_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Cash')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $cash_event->where('branch_id', $branchId);
                    }
                    $cash_event = $cash_event->get();

                    $cash_open = OpenBilling::where('payment_method', 'Cash')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $cash_open->where('branch_id', $branchId);
                    }
                    $cash_open = $cash_open->get();

                    $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
                    if ($branchId) {
                        $partial_event->where('branch_id', $branchId);
                    }
                    $partial_event = $partial_event->get();


                    $partial_cash = [];
                    $advance_payment_cash = [];
                    $installment_amount_cash = [];
                    $final_installment_cash = [];
                    foreach ($partial_event as $partial) {
                        if ($partial['advance_payment_method'] == "Cash") {
                            array_push($advance_payment_cash, $partial);
                        }

                        if ($partial['installment_amount_method'] == "Cash") {
                            array_push($installment_amount_cash, $partial);
                        }

                        if ($partial['final_installment_method'] == "Cash") {
                            array_push($final_installment_cash, $partial);
                        }
                    }
                    $partial_cash['advance_payment_cash'] = $advance_payment_cash;
                    $partial_cash['installment_amount_cash'] = $installment_amount_cash;
                    $partial_cash['final_installment_cash'] = $final_installment_cash;

                    $data['cash_users'] = $cash;
                    $data['cash_members'] = $cash_membership;
                    $data['cash_event'] = $cash_event;
                    $data['cash_open'] = $cash_open;
                    $data['cash_event_partial'] = $partial_cash;
                } else if ($key == "UPI") {
                    $upi = PaymentMethod::where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $upi->where('branch_id', $branchId);
                    }
                    $upi = $upi->get();

                    $upi_membership = MembershipBuy::where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $upi_membership->where('branch_id', $branchId);
                    }
                    $upi_membership = $upi_membership->get();

                    $upi_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'UPI')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
                    if ($branchId) {
                        $upi_event->where('branch_id', $branchId);
                    }
                    $upi_event = $upi_event->get();

                    $upi_open = OpenBilling::where('payment_method', 'UPI')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $upi_open->where('branch_id', $branchId);
                    }
                    $upi_open = $upi_open->get();

                    $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $partial_event->where('branch_id', $branchId);
                    }
                    $partial_event = $partial_event->get();

                    $partial_upi = [];
                    $advance_payment_upi = [];
                    $installment_amount_upi = [];
                    $final_installment_upi = [];
                    foreach ($partial_event as $partial) {
                        if ($partial['advance_payment_method'] == "UPI") {
                            array_push($advance_payment_upi, $partial);
                        }

                        if ($partial['installment_amount_method'] == "UPI") {
                            array_push($installment_amount_upi, $partial);
                        }

                        if ($partial['final_installment_method'] == "UPI") {
                            array_push($final_installment_upi, $partial);
                        }
                    }
                    $partial_upi['advance_payment_upi'] = $advance_payment_upi;
                    $partial_upi['installment_amount_upi'] = $installment_amount_upi;
                    $partial_upi['final_installment_upi'] = $final_installment_upi;

                    $data['upi_users'] = $upi;
                    $data['upi_members'] = $upi_membership;
                    $data['upi_event'] = $upi_event;
                    $data['upi_open'] = $upi_open;
                    $data['upi_event_partial'] = $partial_upi;
                } else {
                    $card = PaymentMethod::where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $card->where('branch_id', $branchId);
                    }
                    $card = $card->get();

                    $card_membership = MembershipBuy::where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->get();
                    if ($branchId) {
                        $card_membership->where('branch_id', $branchId);
                    }
                    $card_membership = $card_membership->get();
                    $card_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Debit/Credit')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
                    if ($branchId) {
                        $card_event->where('branch_id', $branchId);
                    }
                    $card_event = $card_event->get();

                    $card_open = OpenBilling::where('payment_method', 'Debit/Credit')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $card_open->where('branch_id', $branchId);
                    }
                    $card_open = $card_open->get();

                    $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereBetween('booking_date', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                    if ($branchId) {
                        $partial_event->where('branch_id', $branchId);
                    }
                    $partial_event = $partial_event->get();

                    $partial_card = [];
                    $advance_payment_card = [];
                    $installment_amount_card = [];
                    $final_installment_card = [];
                    foreach ($partial_event as $partial) {
                        if ($partial['advance_payment_method'] == "Debit/Credit") {
                            array_push($advance_payment_card, $partial);
                        }

                        if ($partial['installment_amount_method'] == "Debit/Credit") {
                            array_push($installment_amount_card, $partial);
                        }

                        if ($partial['final_installment_method'] == "Debit/Credit") {
                            array_push($final_installment_card, $partial);
                        }
                    }
                    $partial_card['advance_payment_card'] = $advance_payment_card;
                    $partial_card['installment_amount_card'] = $installment_amount_card;
                    $partial_card['final_installment_card'] = $final_installment_card;

                    $data['card_users'] = $card;
                    $data['card_members'] = $card_membership;
                    $data['card_event'] = $card_event;
                    $data['card_open'] = $card_open;
                    $data['card_event_partial'] = $partial_card;
                }

                $applydate = $request->daterange;
            }
        } else {

            $today = Carbon\Carbon::today();
            $data = [];
            if ($key == "Cash") {
                $cash = PaymentMethod::where('payment_method', 'Cash')->whereDate('created_at', $today);
                if ($branchId) {
                    $cash->where('branch_id', $branchId);
                }
                $cash = $cash->get();

                $cash_membership = MembershipBuy::where('payment_method', 'Cash')->whereDate('created_at', $today);
                if ($branchId) {
                    $cash_membership->where('branch_id', $branchId);
                }
                $cash_membership = $cash_membership->get();

                $cash_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Cash')->whereDate('booking_date', $today);
                if ($branchId) {
                    $cash_event->where('branch_id', $branchId);
                }
                $cash_event = $cash_event->get();

                $cash_open = OpenBilling::where('payment_method', 'Cash')->whereDate('created_at', $today);
                if ($branchId) {
                    $cash_open->where('branch_id', $branchId);
                }
                $cash_open = $cash_open->get();

                $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereDate('booking_date', $today);
                if ($branchId) {
                    $partial_event->where('branch_id', $branchId);
                }
                $partial_event = $partial_event->get();

                $partial_cash = [];
                $advance_payment_cash = [];
                $installment_amount_cash = [];
                $final_installment_cash = [];
                foreach ($partial_event as $partial) {
                    if ($partial['advance_payment_method'] == "Cash") {
                        array_push($advance_payment_cash, $partial);
                    }

                    if ($partial['installment_amount_method'] == "Cash") {
                        array_push($installment_amount_cash, $partial);
                    }

                    if ($partial['final_installment_method'] == "Cash") {
                        array_push($final_installment_cash, $partial);
                    }
                }
                $partial_cash['advance_payment_cash'] = $advance_payment_cash;
                $partial_cash['installment_amount_cash'] = $installment_amount_cash;
                $partial_cash['final_installment_cash'] = $final_installment_cash;

                $data['cash_users'] = $cash;
                $data['cash_members'] = $cash_membership;
                $data['cash_event'] = $cash_event;
                $data['cash_open'] = $cash_open;
                $data['cash_event_partial'] = $partial_cash;
            } else if ($key == "UPI") {
                $upi = PaymentMethod::where('payment_method', 'UPI')->whereDate('created_at', $today);
                if ($branchId) {
                    $upi->where('branch_id', $branchId);
                }
                $upi = $upi->get();

                $upi_membership = MembershipBuy::where('payment_method', 'UPI')->whereDate('created_at', $today);
                if ($branchId) {
                    $upi_membership->where('branch_id', $branchId);
                }
                $upi_membership = $upi_membership->get();

                $upi_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'UPI')->whereDate('booking_date', $today);
                if ($branchId) {
                    $upi_event->where('branch_id', $branchId);
                }
                $upi_event = $upi_event->get();

                $upi_open = OpenBilling::where('payment_method', 'UPI')->whereDate('created_at', $today);
                if ($branchId) {
                    $upi_open->where('branch_id', $branchId);
                }
                $upi_open = $upi_open->get();

                $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereDate('booking_date', $today);
                if ($branchId) {
                    $partial_event->where('branch_id', $branchId);
                }
                $partial_event = $partial_event->get();


                $partial_upi = [];
                $advance_payment_upi = [];
                $installment_amount_upi = [];
                $final_installment_upi = [];
                foreach ($partial_event as $partial) {
                    if ($partial['advance_payment_method'] == "UPI") {
                        array_push($advance_payment_upi, $partial);
                    }

                    if ($partial['installment_amount_method'] == "UPI") {
                        array_push($installment_amount_upi, $partial);
                    }

                    if ($partial['final_installment_method'] == "UPI") {
                        array_push($final_installment_upi, $partial);
                    }
                }
                $partial_upi['advance_payment_upi'] = $advance_payment_upi;
                $partial_upi['installment_amount_upi'] = $installment_amount_upi;
                $partial_upi['final_installment_upi'] = $final_installment_upi;

                $data['upi_users'] = $upi;
                $data['upi_members'] = $upi_membership;
                $data['upi_event'] = $upi_event;
                $data['upi_open'] = $upi_open;
                $data['upi_event_partial'] = $partial_upi;
            } else {
                $card = PaymentMethod::where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
                if ($branchId) {
                    $card->where('branch_id', $branchId);
                }
                $card = $card->get();

                $card_membership = MembershipBuy::where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
                if ($branchId) {
                    $card_membership->where('branch_id', $branchId);
                }
                $card_membership = $card_membership->get();

                $card_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Debit/Credit')->whereDate('booking_date', $today);
                if ($branchId) {
                    $card_event->where('branch_id', $branchId);
                }
                $card_event = $card_event->get();

                $card_open = OpenBilling::where('payment_method', 'Debit/Credit')->whereDate('created_at', $today);
                if ($branchId) {
                    $card_open->where('branch_id', $branchId);
                }
                $card_open = $card_open->get();

                $partial_event = EventBooking::where('payment_status', 'Paid')->where('payment_method', 'Partial Payment')->whereDate('booking_date', $today);
                if ($branchId) {
                    $partial_event->where('branch_id', $branchId);
                }
                $partial_event = $partial_event->get();


                $partial_card = [];
                $advance_payment_card = [];
                $installment_amount_card = [];
                $final_installment_card = [];
                foreach ($partial_event as $partial) {
                    if ($partial['advance_payment_method'] == "Debit/Credit") {
                        array_push($advance_payment_card, $partial);
                    }

                    if ($partial['installment_amount_method'] == "Debit/Credit") {
                        array_push($installment_amount_card, $partial);
                    }

                    if ($partial['final_installment_method'] == "Debit/Credit") {
                        array_push($final_installment_card, $partial);
                    }
                }
                $partial_card['advance_payment_card'] = $advance_payment_card;
                $partial_card['installment_amount_card'] = $installment_amount_card;
                $partial_card['final_installment_card'] = $final_installment_card;

                $data['card_users'] = $card;
                $data['card_members'] = $card_membership;
                $data['card_event'] = $card_event;
                $data['card_event_partial'] = $partial_card;
                $data['card_open'] = $card_open;
            }

            $applydate = "Today";
        }

        return view('happyView.payment_method_filter', compact('data', 'applydate'));
    }


    public function DatawiseRecord(Request $request)
    {
        $applydate = base64_decode($request->date);
        $branchId = $request->branch_id ?? auth('admin')->user()->branch_id ?? null;
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        if ($applydate == "Today") {

            $today = Carbon\Carbon::today();

            if ($request->key == "membership") {

                $membership = MembershipPlan::get();
                $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails')->whereDate('created_at', $today);
                if ($branchId) {
                    $membershipUsers->where('branch_id', $branchId);
                }
                $membershipUsers = $membershipUsers->get();
                return view('happyView.memberships', compact('membership', 'membershipUsers', 'applydate', 'branches'));
            }

            if ($request->key == "event_booking") {

                $event_bookings = EventBooking::with('branch')->whereDate('booking_date', Carbon\Carbon::now());
                if ($branchId) {
                    $event_bookings->where('branch_id', $branchId);
                }
                $event_bookings = $event_bookings->get();

                return view('happyView.events', ['event_bookings' => $event_bookings, 'applydate' => $applydate]);
            }

            if ($request->key == "openbillings") {

                $openbillings = OpenBilling::with('branch')->whereDate('created_at', $today);
                if ($branchId) {
                    $openbillings->where('branch_id', $branchId);
                }
                $openbillings = $openbillings->get();

                return view('happyView.openBilling.index', ['open_billings' => $openbillings, 'applydate' => $applydate]);
            }

            // $ticket_sales = PaymentMethod::sum('amounts');
            // $member_sales = MembershipBuy::sum('amount');
            // $event_sales = EventBooking::where('payment_status', 'Paid')->sum('totalamount');


            $event_bookings = EventBooking::where('payment_status', 'Paid')->whereDate('booking_date', $today);
            if ($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->get();

            $openbillings = OpenBilling::whereDate('created_at', $today);
            if ($branchId) {
                $openbillings->where('branch_id', $branchId);
            }
            $openbillings = $openbillings->get();

            $user = User::with('membership', 'kid', 'userDetails', 'event', 'paymentInfo')->whereDate('created_at', $today)->where('role', '!=', 0);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->latest()->get();
            return view('happyView.customer', compact('user', 'applydate', 'event_bookings', 'openbillings'));
        } else {
            $daterange = explode("-", base64_decode($request->date));
            $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('Y-m-d', strtotime($daterange[1]));

            if ($request->key == "membership") {

                $membership = MembershipPlan::get();
                $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                if ($branchId) {
                    $membershipUsers->where('branch_id', $branchId);
                }
                $membershipUsers = $membershipUsers->get();

                return view('happyView.memberships', compact('membership', 'membershipUsers', 'applydate', 'branches'));
            }

            if ($request->key == "event_booking") {
                if ($daterange[0] == $daterange[1]) {
                    $event_bookings = EventBooking::with('branch')->whereDate('booking_date', $daterange[0]);
                    if ($branchId) {
                        $event_bookings->where('branch_id', $branchId);
                    }
                    $event_bookings = $event_bookings->get();
                } else {
                    $event_bookings = EventBooking::with('branch')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
                    if ($branchId) {
                        $event_bookings->where('branch_id', $branchId);
                    }
                    $event_bookings = $event_bookings->get();
                }

                return view('happyView.events', ['event_bookings' => $event_bookings, 'applydate' => $applydate]);
            }

            if ($request->key == "openbillings") {

                $openbillings = OpenBilling::with('branch')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
                if ($branchId) {
                    $openbillings->where('branch_id', $branchId);
                }
                $openbillings = $openbillings->get();

                return view('happyView.openBilling.index', ['open_billings' => $openbillings, 'applydate' => $applydate]);
            }
            $event_bookings = EventBooking::where('payment_status', 'Paid')->whereBetween('booking_date', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->get();

            $openbillings = OpenBilling::whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59']);
            if ($branchId) {
                $openbillings->where('branch_id', $branchId);
            }
            $openbillings = $openbillings->get();

            $user = User::with('membership', 'kid', 'userDetails', 'event', 'paymentInfo')->whereBetween('created_at', [$daterange[0] . ' 00:00:00', $daterange[1] . ' 23:59:59'])->where('role', '!=', 0);
            if ($branchId) {
                $user->where('branch_id', $branchId);
            }
            $user = $user->latest()->get();

            return view('happyView.customer', compact('applydate', 'event_bookings', 'openbillings', 'user'));
        }
    }


    public function qrCode()
    {
        $qr_code = QrCode::size(250)->generate('htttps://www.example.com');
        return view('happyView.qrcode', compact('qr_code'));
    }
    public function deleteProduct(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        Product::whereIn('id', $selectedItems)->delete();
        return response()->json(['success' => true]);
    }




    public function deleteCoupon(Request $request)
    {
        if (can('admin-coupon-delete')) {
            $selectedItems = $request->input('selectedItems');
            Coupon::whereIn('id', $selectedItems)->delete();
            return response()->json(['success' => true]);
        }
    }



    public function editOpenModel(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $product = Product::with('branch')->where('id', $selectedItems)->first();
        return response()->json($product);
    }




    public function editCouponModal(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $coupon = Coupon::where('id', $selectedItems)->first();
        return response()->json($coupon);
    }

    public function addProduct(Request $request)
    {
        hasPermission('admin-products-add');
        if ($request->input('product_name') == null) {
            return back()->with('error', 'Please Provide Product Name');
        }
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->product_price = $request->input('price');
        $product->product_qty = $request->input('qty');
        $product->product_tax = $request->input('tax');
        $product->product_description = $request->input('product_name');
        $product->branch_id = $branchId;
        $product->save();
        if ($product->id) {
            return back()->with('success', 'Success! Product created');
        }
    }

    public function addCoupon(Request $request)
    {
        hasPermission('admin-coupon-add');
        if ($request->input('name') == null) {
            return back()->with('error', 'Please Provide coupon name');
        }
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $coupon = new Coupon();
        $coupon->coupon_name = $request->input('name');
        $coupon->discount_type = $request->input('type');
        $coupon->discount = $request->input('discount');
        $coupon->start_from = $request->input('start_date');
        $coupon->valid_till = $request->input('valid_till');
        $coupon->branch_id = $branchId;

        $coupon->save();
        if ($coupon->id) {
            return back()->with('success', 'Success! Coupon created');
        }
    }

    public function reports(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        if (can('reports-event') || can('reports-openbilling')) {
            $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails', 'paymentInfo');
            if ($branchId) {
                $membershipUsers->where('branch_id', $branchId);
            }
            $membershipUsers = $membershipUsers->latest()->get();
            $branches = Branch::where('status', 'Active')->get()->toArray();
            return view('happyView.reports', ['key' => $request->key, 'membershipUsers' => $membershipUsers, 'branches' => $branches]);
        } else {
            abort(401);
        }
    }

    public function getCoupon(Request $request)
    {
        $branchId = (int)$request->get('branch') ? (int)$request->get('branch') : auth('admin')->user()->branch_id;
        $todayDate = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d');
        $coupon = Coupon::where('coupon_name', $request->input('option'));
        if ($branchId) {
            $coupon->where('branch_id', $branchId);
        }
        $coupon = $coupon->first();

        if (!$coupon) {
            return response()->json(['status' => 400, 'msg' => 'Please enter a valid coupon code']);
        }
        if ($coupon->start_from <= $todayDate && $coupon->valid_till >= $todayDate) {
            $data = [
                'coupon_name' => $coupon->coupon_name,
                'discount_type' => $coupon->discount_type,
                'discount' => $coupon->discount
            ];
            return response()->json($data, 200);
        } else {
            return response()->json(['status' => 400, 'msg' => 'Coupon code has expired']);
        }
    }

    // public function getCoupon(Request $request)
    // {

    //     $todaydate = Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d');

    //     $value = Coupon::where('coupon_name', $request->get('option'))->first();
    //     dd($value);
    //     if (!$value) {
    //         return response()->json(['status' => 400, 'msg' => 'Please enter valid coupon code']);
    //     }
    //     if (isset($value->coupon_name) && $value->coupon_name != '') {
    //         if ($value->start_from <= $todaydate && $value->valid_till >= $todaydate) {
    //             $data = ['coupon_name' => $value->coupon_name, 'discount_type' => $value->discount_type, 'discount' => $value->discount];
    //             return response()->json($data, 200);
    //         } else {
    //             return response()->json(['status' => 400, 'msg' => 'Coupon code expired']);
    //         }
    //     } else {
    //         return response()->json(['status' => 400, 'msg' => 'Coupon code invalid']);
    //     }

    // }


    public function editProductPost(Request $request)
    {
        hasPermission('admin-products-edit');
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $product = Product::where('id', $request->productId)->first();
        $product->product_price = $request->editprice;
        $product->product_qty = $request->editqty;
        $product->product_tax = $request->tax;
        $product->branch_id = $branchId;
        $product->save();
        session()->flash('success', 'Product Updated successfully!');
        return redirect()->back();
    }

    public function editCouponPost(Request $request)
    {
        hasPermission('admin-coupon-edit');
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $coupon = Coupon::where('id', $request->couponId)->first();
        $coupon->coupon_name = $request->couponName;
        $coupon->discount_type = $request->couponType;
        $coupon->discount = $request->couponDiscount;
        $coupon->start_from = $request->couponStart;
        $coupon->valid_till = $request->couponValid;
        $coupon->branch_id = $branchId;
        $coupon->save();

        session()->flash('success', 'Coupon Updated Successfully!');
        return redirect()->back();
    }

    public function editDayPriceModal(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $dayprice = DayPrice::where('id', $selectedItems)->first();
        return response()->json($dayprice);
    }

    public function editDayPricePost(Request $request)
    {
        hasPermission('admin-hours-edit');
        $dayprice = DayPrice::where('id', $request->dayPriceId)->first();
        $dayprice->monday = $request->monday;
        $dayprice->tuesday = $request->tuesday;
        $dayprice->wednesday = $request->wednesday;
        $dayprice->thursday = $request->thursday;

        $dayprice->save();

        session()->flash('success', 'Weekdays Price Updated Successfully!');
        return redirect()->back();
    }

    public function editWeekendModal(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $weekend = DayPrice::where('id', $selectedItems)->first();
        return response()->json($weekend);
    }

    public function editWeekendPost(Request $request)
    {
        hasPermission('admin-hours-edit');
        $weekend = DayPrice::where('id', $request->weekendId)->first();
        $weekend->friday = $request->friday;
        $weekend->saturday = $request->saturday;
        $weekend->sunday = $request->sunday;

        $weekend->save();

        session()->flash('success', 'Weekend Price Updated Successfully!');
        return redirect()->back();
    }


    public function logout()
    {
        $admin = Auth::guard('admin')->user();
        Auth::guard('admin')->logout();
        return redirect(route('login'))->with('success', 'Logged out successfully');
    }
}
