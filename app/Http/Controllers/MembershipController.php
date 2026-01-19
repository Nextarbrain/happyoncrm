<?php

namespace App\Http\Controllers;

use Carbon;
use Exception;
use App\Models\Kid;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\UserDetail;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\MembershipBuy;
use App\Models\PaymentMethod;
use App\Models\MembershipBuyRenewable;
use App\Models\MembershipPlan;
use App\Models\MembershipHistory;

class MembershipController extends Controller
{
    public function membershipBookingReport(Request $request)
    {

        hasPermission('reports-membership');
        $branchId = !empty($request->branch_id) && count($request->branch_id) > 0 ? $request->branch_id : (auth('admin')->user()->branch_id ? [strval(auth('admin')->user()->branch_id)] : null);
        if (isset($request->daterange) && $request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('y-m-d', strtotime($daterange[1]));

            if ($daterange[0] == $daterange[1]) {
                $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails', 'paymentInfo', 'branch')->whereDate('created_at', $daterange[0]);
                if ($branchId) {
                    $membershipUsers->whereIn('branch_id', $branchId);
                }
                $membershipUsers = $membershipUsers->get();
            } else {
                $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails', 'paymentInfo', 'branch')->whereBetween('created_at', [$daterange[0], $daterange[1]]);
                if ($branchId) {
                    $membershipUsers->whereIn('branch_id', $branchId);
                }
                $membershipUsers = $membershipUsers->get();
            }
            return view('happyView.membership-booking-reports', compact('membershipUsers'));
        }
    }

    public function getMembershipPhoneList(Request $request)
    {
        $search = $request->post('query');

        $user = UserDetail::where('phone_no', 'LIKE', "%{$search}%")->where('membership_plan', '1')->latest()->limit(5)->get();;

        $result = '';
        foreach ($user as $u) {

            $result .= '<a href="javascript:void(0);" onclick="calculatePlan(' . $u->phone_no . ')"><p>' . $u->phone_no . '</p></a>';
        }
        echo $result;
    }

    public function invoiceMembershipGenerate(Request $request)
    {
        $id = $request->id;
        $user = User::with('membership', 'userDetails', 'event', 'paymentInfo')->where('id', $id)->latest()->first();

        $paymentMethod = PaymentMethod::where('user_id', $id)->latest()->first();

        $branchId = $paymentMethod->branch_id;
        $planName = $user['membership']->plan;

        $reqPlan = MembershipPlan::where('plan_name', $planName)
            ->when($branchId, fn($query) => $query->where('branch_id', $branchId), fn($query) => $query->whereNull('branch_id'))
            ->get();
        if ($reqPlan->isEmpty() && $branchId) {
            $reqPlan = MembershipPlan::where('plan_name', $planName)->whereNull('branch_id')->get();
        }
        $credit = $reqPlan[0]->credits ?? 0;

        $data = [
            'guardian_name' => $user->name,
            "payment_method" => $paymentMethod->payment_method,
            "date" => $user['membership']->date,
            "package_name" => $planName,
            "amount" => $user['membership']->amount,
            "total_credit" => $credit,
            "couponcode" => $user['paymentInfo']->couponcode,
            "couponvalue" => $user['paymentInfo']->couponvalue,
            "invoiceNo" => $paymentMethod->id,
            "subTotal" => $paymentMethod->subTotal,
            "gst18" => $paymentMethod->gst18,
            "totalAmount" => $paymentMethod->amounts,
            "branch" => branchInfo($branchId),
        ];
        return view('receipt.membership-invoice', $data);
    }

    public function membershipHistoryView(Request $request)
    {
        hasPermission('membership-history');
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $membershipUser = MembershipHistory::with('user', 'kid', 'userDetails', 'membershipBuy', 'branch')->where('user_id', $request->id);
        if ($branchId) {
            $membershipUser->where('branch_id', $branchId);
        }
        $membershipUser = $membershipUser->latest()->get();
        return view('happyView.membership-history-view', compact('membershipUser'));
    }

    public function membershipView()
    {
        hasPermission('membership');
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        // $membership = MembershipPlan::get();
        $membership = MembershipPlan::whereNull('branch_id')->get();
        if (session('branch_id') > 0) {
            $branchPlans = MembershipPlan::where('branch_id', session('branch_id'))->get();
            if ($branchPlans->count() === 3) {
                $membership = $branchPlans->values();
            } else {
                // Fill missing plan from default
                $requiredPlans = $membership->pluck('plan_name')->toArray();
                foreach ($requiredPlans as $plan) {
                    $reqPlan = MembershipPlan::where('branch_id', session('branch_id'))->where('plan_name', $plan)->get();
                    if ($reqPlan->isEmpty()) {
                        $reqPlan = MembershipPlan::whereNull('branch_id')->where('plan_name', $plan)->get();
                    }
                    $branchPlans = $branchPlans->merge($reqPlan);
                }
                $membership = $branchPlans->values();
            }
        }
        $membershipUsers = MembershipBuy::with('user', 'kid', 'userDetails', 'paymentInfo', 'branch');
        if ($branchId) {
            $membershipUsers->where('branch_id', $branchId);
        }
        $membershipUsers = $membershipUsers->latest()->get();
        return view('happyView.memberships', compact('membership', 'membershipUsers', 'branches', 'branchId'));
    }

    public function membershipHistory()
    {
        hasPermission('membership-history');
        // $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $branchId = null;
        $membershipUsers = MembershipHistory::with('user', 'kid', 'userDetails', 'membershipBuy', 'branch');
        if ($branchId) {
            $membershipUsers->where('branch_id', $branchId);
        }
        $membershipUsers = $membershipUsers->latest()->get();

        return view('happyView.membership-history', compact('membershipUsers'));
    }


    public function getMembershipPlan(Request $request)
    {
        hasPermission('membership');
        $branchId = $request->get('branch_id') ? (int) $request->get('branch_id') : auth('admin')->user()->branch_id;
        $id = $request->input('id');
        $data = MembershipPlan::where('id', $id)->first();
        if (!$data) {
            return response()->json(["status" => false, "message" => "Please Choose Membership Plan "]);
        }
        if ($request->coupon) {
            $coupon = Coupon::where('coupon_name', $request->coupon);
            if ($branchId) {
                $coupon->where('branch_id', $branchId);
            }
            $coupon = $coupon->first();
            if ($coupon == null) {
                return response()->json(["status" => false, "message" => "Please enter a valid coupon code"]);
            }
            $data->coupan_name = $coupon->coupon_name ?? '';
            $data->coupan_price = $coupon->discount ?? '';
        }
        // Original Plan Price
        $planPrice = $data->price;

        // coupon calculation
        $couponDiscount = 0;
        if (isset($coupon) && !is_null($coupon) && $coupon->discount_type === 'flat') {
            $couponDiscount = number_format($coupon->discount, 2, '.', '');
        } elseif (isset($coupon) && !is_null($coupon) && $coupon->discount_type === 'percent') {
            $couponDiscount = ($planPrice * $coupon->discount) / 100;
            $couponDiscount = number_format($couponDiscount, 2, '.', '');
        }
        $data->discounted_amt = $couponDiscount;
        $planPrice = $planPrice - $couponDiscount;
        $data->subTotal = $planPrice;

        // GST Calculation
        $data->gst18Amt = $gst18Amt = ($planPrice * 18) / 100;
        $data->gst9Amt = ($planPrice * 9) / 100;

        $data->totalAmt = $planPrice + $gst18Amt;
        return response()->json($data);
    }


    public function old_metMembershipPlan_niu(Request $request)
    {
        hasPermission('membership');
        $branchId = $request->get('branch_id') ? (int) $request->get('branch_id') : auth('admin')->user()->branch_id;
        $id = $request->input('id');
        $data = MembershipPlan::where('id', $id)->first();
        if (!$data) {
            return response()->json(["status" => false, "message" => "Please Choose Membership Plan "]);
        }
        $price = $data->price;
        $data->subtotal = $data->price;
        if ($request->coupon) {
            $coupon = Coupon::where('coupon_name', $request->coupon);
            if ($branchId) {
                $coupon->where('branch_id', $branchId);
            }
            $coupon = $coupon->first();
            if ($coupon == null) {
                return response()->json(["status" => false, "message" => "Please enter a valid coupon code"]);
            }
        }

        $gstAmount = $data->price;
        $planAfterGST = $data->price;
        if (isset($coupon) && !is_null($coupon) && $coupon->discount_type === 'flat') {
            $finalCoupanPrice = $planAfterGST - $coupon->discount;
            $data->price = number_format($finalCoupanPrice, 2, '.', '');
            $data->discounted_amt = number_format($coupon->discount, 2, '.', '');
        } elseif (isset($coupon) && !is_null($coupon) && $coupon->discount_type === 'percent') {
            $finalCoupanPrice = ($coupon->discount / 100) * $gstAmount;
            $data->price = $data->discounted_amt = number_format($finalCoupanPrice, 2, '.', '');
        }
        $data->coupan_name = $coupon->coupon_name ?? '';
        $data->coupan_price = $coupon->discount ?? '';
        return response()->json($data);
    }

    public function addMembershipPlan(Request $request)
    {
        hasPermission('membership-add');
        $dateInput = $request->input('date');
        $userdetail = UserDetail::with('user')->where('phone_no', $request->input('phone'))->where('membership_plan', 1)->first();
        $getPlan = MembershipPlan::where('id', $request->input('membershipPlan'))->first();
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        if (!$branchId) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }
        $payment_method = 'Card';
        if ($request->input('payment_mode') == 1) {
            $payment_method = "UPI";
        } elseif ($request->input('payment_mode') == 2) {
            $payment_method = "Cash";
        } else {
            $payment_method = "Debit/Credit";
        }

        if (isset($userdetail)) {
            $member = MembershipBuy::where('user_id', $userdetail->user_id)->latest()->first();
            if (isset($member)) {
                if ($member->credit_left > 0) {
                    if ($userdetail->user) {
                        $userdetail->user->name =  $request->input('name');
                        $userdetail->user->save();
                    }
                    $kidsDetails = new Kid();
                    $kidsDetails->user_id = $userdetail->user_id;
                    $kidsDetails->name = $request->input('name');
                    $kidsDetails->branch_id = $branchId;
                    $kidsDetails->save();


                    $member->plan = $getPlan->plan_name;
                    $user_total_creadit = $member->credit_left + $getPlan->credits;

                    $member->credit_left = $user_total_creadit;
                    $member->total_credit = $user_total_creadit;
                    $member->amount = $request->input('totalamount');
                    $member->payment_method = $payment_method;
                    $member->date = $request->input('date');
                    $date = Carbon\Carbon::parse($request->input('date'));
                    $dateAfter1Year = $date->addYear();
                    $member->renewal_date = $dateAfter1Year->format('Y-m-d');

                    // $member->time = $request->input('time');
                    $member->time = now()->format('H:i');
                    $member->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                    $member->save();

                    $membershipRenewabel = new MembershipBuyRenewable();
                    $membershipRenewabel->amount = $request->input('totalamount');
                    $membershipRenewabel->branch_id = $branchId;
                    $membershipRenewabel->user_id = $userdetail->user_id;
                    $membershipRenewabel->payment_method = $payment_method;
                    $membershipRenewabel->created_at = now();
                    $membershipRenewabel->save();

                    $amount = new PaymentMethod();
                    $amount->user_id = $userdetail->user_id;
                    $amount->subTotal = $request->input('subtotal');
                    $amount->branch_id = $branchId;
                    $amount->gst18 = $request->input('gst18');
                    $amount->amounts = $request->input('totalamount');
                    $amount->payment_method = $payment_method;

                    $coupon = null;
                    if ($request->coupon_code) {
                        $coupon = Coupon::where('coupon_name', $request->coupon_code);
                        if ($branchId) {
                            $coupon->where('branch_id', $branchId);
                        }
                        $coupon = $coupon->first();
                    }
                    if (!is_null($coupon) && $coupon->discount_type === 'flat' && $coupon->isValid()) {
                        $amount->couponvalue = $coupon->discount;
                        $amount->couponcode = $request->coupon_code;
                    } elseif (!is_null($coupon) && $coupon->discount_type === 'percent') {
                        $amount->couponvalue = $request->input('couponDis');
                        $amount->couponcode = $request->coupon_code;
                    }
                    couponCountUpdate($request->coupon_code);
                    $amount->save();

                    $mobile = "91" . $request->input('phone');
                    $package = $getPlan->plan_name;
                    $entries = $user_total_creadit;
                    $date = $request->input('date');
                    $renewal_date = $dateAfter1Year->format('Y-m-d');

                    $msg = "Congratulation! You are now the Privilege Member of HappyOn Family. Membership details : Package: $package, Date: $date, Entries: $entries, Valid Upto: $renewal_date";
                    $template_id = "1707167990929869469";
                    try {
                        sendsms($mobile, $msg, $template_id);
                    } catch (Exception $e) {
                    }
                    session()->flash('success', 'Membership Plan has been saved successfully!');

                    // Redirect back to the previous page
                    return redirect()->back();
                } else {
                    $userSave = new User();
                    $userSave->name = $request->input('name');
                    $userSave->branch_id = $branchId;
                    $userSave->email = $request->input('name') . rand(10, 100) . '@gmail.com';
                    $userSave->role = 2;
                    $userSave->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                    $userSave->save();
                    if ($userSave->id) {
                        $userDetails = new UserDetail();
                        $userDetails->user_id = $userSave->id;
                        $userDetails->phone_no = $request->input('phone');
                        $userDetails->branch_id = $branchId;
                        $userDetails->number_of_kids = 1;
                        $userDetails->membership_plan = 1;
                        $userDetails->save();


                        $kidsDetails = new Kid();
                        $kidsDetails->user_id = $userSave->id;
                        $kidsDetails->branch_id = $branchId;
                        $kidsDetails->name = $request->input('name');
                        $kidsDetails->save();

                        $member = new MembershipBuy();
                        $member->user_id = $userSave->id;
                        $member->branch_id = $branchId;
                        $member->plan = $getPlan->plan_name;
                        $member->credit_left = $getPlan->credits;
                        $member->total_credit = $getPlan->credits;
                        $member->amount = $request->input('totalamount');
                        $member->payment_method = $payment_method;
                        $member->date = $request->input('date');
                        $date = Carbon\Carbon::parse($request->input('date'));
                        $dateAfter1Year = $date->addYear();
                        $member->renewal_date = $dateAfter1Year->format('Y-m-d');

                        // $member->time = $request->input('time');
                        $member->time = now()->format('H:i');
                        $member->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                        $member->save();
                        $membershipRenewabel = new MembershipBuyRenewable();
                        $membershipRenewabel->amount = $request->input('totalamount');
                        $membershipRenewabel->branch_id = $branchId;
                        $membershipRenewabel->user_id = $userSave->id;
                        $membershipRenewabel->payment_method = $payment_method;
                        $membershipRenewabel->created_at = now();
                        $membershipRenewabel->save();

                        $amount = new PaymentMethod();
                        $amount->user_id = $userSave->id;
                        $amount->branch_id = $branchId;
                        $amount->subTotal = $request->input('subtotal');
                        $amount->gst18 = $request->input('gst18');
                        $amount->amounts = $request->input('totalamount');
                        $amount->payment_method = $payment_method;
                        $coupon = null;
                        if ($request->coupon_code) {
                            $coupon = Coupon::where('coupon_name', $request->coupon_code);
                            if ($branchId) {
                                $coupon->where('branch_id', $branchId);
                            }
                            $coupon = $coupon->first();
                        }
                        if (!is_null($coupon) && $coupon->discount_type === 'flat' && $coupon->isValid()) {
                            $amount->couponvalue = $coupon->discount;
                            $amount->couponcode = $request->coupon_code;
                        } elseif (!is_null($coupon) && $coupon->discount_type === 'percent') {
                            $amount->couponvalue = $request->input('couponDis');
                            $amount->couponcode = $request->coupon_code;
                        }
                        couponCountUpdate($request->coupon_code);
                        $amount->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                        $amount->save();

                        $mobile = "91" . $request->input('phone');
                        $package = $getPlan->plan_name;
                        $entries = $getPlan->credits;
                        $date = $request->input('date');
                        $renewal_date = $dateAfter1Year->format('Y-m-d');

                        $msg = "Congratulation! You are now the Privilege Member of HappyOn Family. Membership details : Package: $package, Date: $date, Entries: $entries, Valid Upto: $renewal_date";
                        $template_id = "1707167990929869469";
                        sendsms($mobile, $msg, $template_id);
                        session()->flash('success', 'Membership Plan has been saved successfully!');

                        // Redirect back to the previous page
                        return redirect()->back();
                    }
                }
            } else {
                $userSave = new User();
                $userSave->name = $request->input('name');
                $userSave->branch_id = $branchId;
                $userSave->email = $request->input('name') . rand(10, 100) . '@gmail.com';
                $userSave->role = 2;
                $userSave->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                $userSave->save();
                if ($userSave->id) {
                    $userDetails = new UserDetail();
                    $userDetails->user_id = $userSave->id;
                    $userDetails->branch_id = $branchId;
                    $userDetails->phone_no = $request->input('phone');
                    $userDetails->number_of_kids = 1;
                    $userDetails->membership_plan = 1;
                    $userDetails->save();

                    $kidsDetails = new Kid();
                    $kidsDetails->user_id = $userSave->id;
                    $kidsDetails->branch_id = $branchId;
                    $kidsDetails->name = $request->input('name');
                    $kidsDetails->save();

                    $member = new MembershipBuy();
                    $member->user_id = $userSave->id;
                    $member->plan = $getPlan->plan_name;
                    $member->branch_id = $branchId;
                    $member->credit_left = $getPlan->credits;
                    $member->total_credit = $getPlan->credits;
                    $member->amount = $request->input('totalamount');
                    $member->payment_method = $payment_method;
                    $member->date = $request->input('date');
                    $date = Carbon\Carbon::parse($request->input('date'));
                    $dateAfter1Year = $date->addYear();
                    $member->renewal_date = $dateAfter1Year->format('Y-m-d');
                    $member->time = now()->format('H:i');
                    $member->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                    $member->save();

                    $membershipRenewabel = new MembershipBuyRenewable();
                    $membershipRenewabel->amount = $request->input('totalamount');
                    $membershipRenewabel->branch_id = $branchId;
                    $membershipRenewabel->user_id = $userSave->id;
                    $membershipRenewabel->payment_method = $payment_method;
                    $membershipRenewabel->created_at = now();
                    $membershipRenewabel->save();

                    $amount = new PaymentMethod();
                    $amount->user_id = $userSave->id;
                    $amount->branch_id = $branchId;
                    $amount->subTotal = $request->input('subtotal');
                    $amount->gst18 = $request->input('gst18');
                    $amount->amounts = $request->input('totalamount');
                    $amount->payment_method = $payment_method;
                    $coupon = null;
                    if ($request->coupon_code) {
                        $coupon = Coupon::where('coupon_name', $request->coupon_code);
                        if ($branchId) {
                            $coupon->where('branch_id', $branchId);
                        }
                        $coupon = $coupon->first();
                    }
                    if (!is_null($coupon) && $coupon->discount_type === 'flat' && $coupon->isValid()) {
                        $amount->couponvalue = $coupon->discount;
                        $amount->couponcode = $request->coupon_code;
                    } elseif (!is_null($coupon) && $coupon->discount_type === 'percent') {
                        $amount->couponvalue = $request->input('couponDis');
                        $amount->couponcode = $request->coupon_code;
                    }
                    couponCountUpdate($request->coupon_code);
                    $amount->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                    $amount->save();

                    $mobile = "91" . $request->input('phone');
                    $package = $getPlan->plan_name;
                    $entries = $getPlan->credits;
                    $date = $request->input('date');
                    $renewal_date = $dateAfter1Year->format('Y-m-d');

                    $msg = "Congratulation! You are now the Privilege Member of HappyOn Family. Membership details : Package: $package, Date: $date, Entries: $entries, Valid Upto: $renewal_date";
                    $template_id = "1707167990929869469";
                    sendsms($mobile, $msg, $template_id);
                    session()->flash('success', 'Membership Plan has been saved successfully!');

                    // Redirect back to the previous page
                    return redirect()->back();
                }
            }
        } else {
            $userSave = new User();
            $userSave->name = $request->input('name');
            $userSave->branch_id = $branchId;
            $userSave->email = $request->input('name') . rand(10, 100) . '@gmail.com';
            $userSave->role = 2;
            $userSave->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
            $userSave->save();
            if ($userSave->id) {
                $userDetails = new UserDetail();
                $userDetails->user_id = $userSave->id;
                $userDetails->branch_id = $branchId;
                $userDetails->phone_no = $request->input('phone');
                $userDetails->number_of_kids = 1;
                $userDetails->membership_plan = 1;
                $userDetails->save();

                $kidsDetails = new Kid();
                $kidsDetails->user_id = $userSave->id;
                $kidsDetails->branch_id = $branchId;
                $kidsDetails->name = $request->input('name');
                $kidsDetails->save();

                $member = new MembershipBuy();
                $member->user_id = $userSave->id;
                $member->branch_id = $branchId;
                $member->plan = $getPlan->plan_name;
                $member->credit_left = $getPlan->credits;
                $member->total_credit = $getPlan->credits;
                $member->amount = $request->input('totalamount');
                $member->payment_method = $payment_method;
                $member->date = $request->input('date');
                $date = Carbon\Carbon::parse($request->input('date'));
                $dateAfter1Year = $date->addYear();
                $member->renewal_date = $dateAfter1Year->format('Y-m-d');
                $member->time = now()->format('H:i');
                $member->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                $member->save();

                $membershipRenewabel = new MembershipBuyRenewable();
                $membershipRenewabel->amount = $request->input('totalamount');
                $membershipRenewabel->branch_id = $branchId;
                $membershipRenewabel->user_id = $userSave->id;
                $membershipRenewabel->payment_method = $payment_method;
                $membershipRenewabel->created_at = now();
                $membershipRenewabel->save();

                $amount = new PaymentMethod();
                $amount->user_id = $userSave->id;
                $amount->branch_id = $branchId;
                $amount->subTotal = $request->input('subtotal');
                $amount->gst18 = $request->input('gst18');
                $amount->amounts = $request->input('totalamount');
                $amount->payment_method = $payment_method;
                $coupon = null;
                if ($request->coupon_code) {
                    $coupon = Coupon::where('coupon_name', $request->coupon_code);
                    if ($branchId) {
                        $coupon->where('branch_id', $branchId);
                    }
                    $coupon = $coupon->first();
                }
                if (!is_null($coupon) && $coupon->discount_type === 'flat' && $coupon->isValid()) {
                    $amount->couponvalue = $coupon->discount;
                    $amount->couponcode = $request->coupon_code;
                } elseif (!is_null($coupon) && $coupon->discount_type === 'percent') {
                    $amount->couponvalue = $request->input('couponDis');
                    $amount->couponcode = $request->coupon_code;
                }
                couponCountUpdate($request->coupon_code);
                $amount->created_at = (!empty($dateInput) && Carbon\Carbon::parse($dateInput)->lte(now())) ? Carbon\Carbon::parse($dateInput)->startOfDay() : now();
                $amount->save();

                $mobile = "91" . $request->input('phone');
                $package = $getPlan->plan_name;
                $entries = $getPlan->credits;
                $date = $request->input('date');
                $renewal_date = $dateAfter1Year->format('Y-m-d');

                $msg = "Congratulation! You are now the Privilege Member of HappyOn Family. Membership details : Package: $package, Date: $date, Entries: $entries, Valid Upto: $renewal_date";
                $template_id = "1707167990929869469";
                sendsms($mobile, $msg, $template_id);
                session()->flash('success', 'Membership Plan has been saved successfully!');

                // Redirect back to the previous page
                return redirect()->back();
            }
        }
    }

    public function getPlan(Request $request)
    {
        $mobile = $request->input('mobile');
        $branchId = auth('admin')->user()->branch_id ?? null;
        $findId = UserDetail::select('user_id')->where(['phone_no' => $mobile, 'membership_plan' => 1])->latest()->first();

        $user = MembershipBuy::with('user', 'kid', 'userDetails')->where('user_id', $findId->user_id);
        // if ($branchId) {
        //     $user->where('branch_id', $branchId);
        // }
        $user = $user->first();

        return response()->json($user);
    }

    public function SendOtpMembership(Request $request)
    {

        $mobile = "91" . $request->input('mobile');

        $otp = rand(100000, 999999);
        // $otp = 123456;
        $msg = "$otp is your OTP to verify your Membership at HappyOn Play.";
        $template_id = '1707167991426660279';
        $response = sendsms($mobile, $msg, $template_id);

        $user = User::where('id', $request->input('user_id'))->first();
        $user->otp = $otp;

        $user->save();
        echo $otp;
    }


    public function updateMembership(Request $request)
    {
        hasPermission('membership-edit');
        // $branchId = auth('admin')->user()->branch_id ?? null;
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        if (!$branchId) {
            return redirect()->back()->with('error', 'Please select a branch first.');
        }
        if ($request->input('response_otp') > 0) {
            $user = User::where('id', $request->input('user_id'))->where('otp', $request->input('response_otp'))->first();

            if ($user) {
                $update = MembershipBuy::where('user_id', $request->input('user_id'))->first();
                $update->credit_left =  $update->credit_left - $request->input('hrs');
                $update->save();
                $userDetail = UserDetail::where('user_id', $request->input('user_id'))->first();
                $userDetail->number_of_kids = $request->input('number_of_kids');
                $userDetail->save();

                $event = new Event();
                $event->user_id = $request->input('user_id');
                $event->branch_id = $branchId;
                $event->booking_date = now()->format('y-m-d');
                $event->Hours =  now()->format('H:i');
                $event->save();

                $kidsDetails = Kid::where('user_id', $request->input('user_id'))->first();;
                $kidsDetails->hours = $request->input('hrs');
                $end_time = Carbon\Carbon::parse(now()->format('H:i'))->addHour($request->input('hrs'));
                $time_str = $end_time->toTimeString();
                $kidsDetails->end_time = $time_str;
                $kidsDetails->branch_id = $branchId;
                $kidsDetails->save();

                $membershiphistory = new MembershipHistory;
                $membershiphistory->user_id = $request->input('user_id');
                $membershiphistory->branch_id = $branchId;
                $membershiphistory->membership_buyer_id = $update->id;
                $membershiphistory->use_credit = $request->input('hrs');
                $membershiphistory->current_left = $update->credit_left;
                $membershiphistory->save();

                $user->otp = NULL;

                $user->save();


                $mobile = "91" . $userDetail->phone_no;
                $name = $user->name;
                $remaining = $update->credit_left;
                $totalused = $update->total_credit - $update->credit_left;
                $time_in = now()->format('H:i');
                $hours = $kidsDetails->hours;
                $time_out = date("H:i", strtotime("+$hours hours"));

                $date = date("Y-m-d");

                $msg = "Hi $name, At HappyOn Play Your entry has been registered. Date : $date, Time-In :$time_in, Time-Out : $time_out, Entry Used : $totalused, Remaining Entry : $remaining";
                $template_id = "1707167991038724774";
                sendsms($mobile, $msg, $template_id);

                return redirect('/customers')->with('success', 'Successfully Updated!');
            } else {
                return redirect('/tickets')->with('error', 'OTP Wrong!');
            }
        } else {
            return redirect('/tickets')->with('error', 'Please enter OTP!');
        }
    }

    public function addMembership(Request $request)
    {
        hasPermission('admin-membership-add');
        if ($request->input('plan') == null) {
            return back()->with('error', 'Please Provide Plan');
        }
        $branchId = auth('admin')->user()->branch_id ?? null;
        $membership = new MembershipPlan();
        $membership->plan_name = $request->input('plan');
        $membership->price = $request->input('price');
        $membership->credits = $request->input('credit');
        // if ($membership->id) {
        $membership->save();

        return back()->with('success', 'Success! MembershipPlan created');
        // }
    }

    public function editMemberPost(Request $request)
    {
        hasPermission('admin-membership-edit');
        $branch_id = session('branch_id');
        if ($branch_id > 0) {
            $memPlan = MembershipPlan::where([
                ['id', '=', $request->memberId],
                ['branch_id', '=', $branch_id],
            ])->first();
            if (!$memPlan) {
                $adminPlan = MembershipPlan::where('id', $request->memberId)->first();
                $memPlan = new MembershipPlan();
                $memPlan->branch_id = $branch_id;
                $memPlan->plan_name = $adminPlan->plan_name;
                $memPlan->credits = $adminPlan->credits;
            }
        } else {
            $memPlan = MembershipPlan::where('id', $request->memberId)->first();
            $memPlan->credits = $request->editPlanEntry;
        }
        $memPlan->price = $request->editPlanPrice;
        $memPlan->save();
        session()->flash('success', 'Membership Plan Updated Successfully!');
        return redirect()->back();
    }

    public function editMemberModel(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $member = MembershipPlan::where('id', $selectedItems)->first();
        return response()->json($member);
    }

    public function deleteMembership(Request $request)
    {
        try {
            if (can('membership-delete')) {
                $selectedItems = $request->input('selectedItems');
                $integerArray = array_map('intval', $selectedItems);

                MembershipPlan::whereIn('id', $integerArray)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }
    public function deleteMembershipBuyers(Request $request)
    {
        try {
            if (can('membership-delete')) {
                $selectedItems = $request->input('selectedItems');
                $userIds = collect($selectedItems)->pluck('userId')->toArray();
                $membershipId = collect($selectedItems)->pluck('membershipId')->toArray();
                $paymentIds = collect($selectedItems)->pluck('paymentId')->filter()->toArray();
                $userDetail = collect($selectedItems)->pluck('userdetailId')->filter()->toArray();
                $kidId = collect($selectedItems)->pluck('kidId')->filter()->toArray();
                MembershipHistory::whereIn('membership_buyer_id', $membershipId)->delete();
                MemberShipBuy::whereIn('id', $membershipId)->delete();

                if (!empty($userIds)) {
                    User::whereIn('id', $userIds)->delete();
                }

                if (!empty($paymentIds)) {
                    PaymentMethod::whereIn('id', $paymentIds)->delete();
                }

                 if (!empty($userDetail)) {
                    UserDetail::whereIn('id', $userDetail)->delete();
                }

                if (!empty($kidId)) {
                    Kid::whereIn('id', $kidId)->delete();
                }

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }
    public function deleteMembershipHistory(Request $request)
    {
        try {
            if (can('membership-delete')) {
                $selectedItems = $request->input('selectedItems');
                $integerArray = array_map('intval', $selectedItems);
                MembershipHistory::whereIn('id', $integerArray)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }

    public function membershipBookingUser(Request $request)
    {
        hasPermission('report-membership-user');
        $branchId = !empty($request->branch_id) && count($request->branch_id) > 0 ? $request->branch_id : (auth('admin')->user()->branch_id ? [strval(auth('admin')->user()->branch_id)] : null);
        $membershipUsers = MembershipHistory::with('user', 'kid', 'userDetails', 'membershipBuy', 'branch');
        if ($request->membership_user) {
            $membershipUsers->where('user_id', $request->membership_user);
        }
        if ($branchId) {
            $membershipUsers->whereIn('branch_id', $branchId);
        }
        $membershipUsers = $membershipUsers->latest()->get();
        return view('happyView.membership-booking-user', compact('membershipUsers'));
    }
    public function getBranchPlan(Request $request)
    {
        $branchId = $request->get('branch_id');
        $membership = MembershipPlan::whereNull('branch_id')->get();

        if ($branchId) {
            $branchPlans = MembershipPlan::where('branch_id', $branchId)->get();
            if ($branchPlans->count() === 3) {
                $membership = $branchPlans->values();
            } else {
                // Fill missing plan from default
                $requiredPlans = ['Happy Star', 'Bright Star', 'Shooting Star'];
                foreach ($requiredPlans as $plan) {
                    $reqPlan = MembershipPlan::where('branch_id', $branchId)->where('plan_name', $plan)->get();
                    if ($reqPlan->isEmpty()) {
                        $reqPlan = MembershipPlan::whereNull('branch_id')->where('plan_name', $plan)->get();
                    }
                    $branchPlans = $branchPlans->merge($reqPlan);
                }
                $membership = $branchPlans->values();
            }
        }
        return response()->json($membership);
    }
}
