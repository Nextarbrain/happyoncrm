<?php
// import model admin
use App\Models\Admin;
// use Exception;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Carbon;
use App\Models\UserProduct;
use App\Models\Branch;
use Illuminate\Support\Facades\Log;

function sendsms($phone, $msg, $template_id)
{
    $api_key = '461E1011B2C0F2';
    $contacts = $phone;
    $from = 'HAPYON';
    $sms_text = urlencode("$msg");
    $pe_id = '1701167959115997405';
    try{
     $api_url = "https://kutility.org/app/smsapi/index.php?key=" . $api_key . "&campaign=12886&routeid=7&type=text&contacts=" . $contacts . "&senderid=" . $from . "&msg=" . $sms_text . "&template_id=" . $template_id . "&pe_id=" . $pe_id;
    $response = file_get_contents($api_url);
    return $response;
    }catch(Exception $e){

    }

}

function staff_detail($id)
{

    return Admin::where('id', $id)->first();
}

function getBirthDays($birthdate)
{
    $dob = Carbon::parse($birthdate);
    $upcomingBirthday = $dob->copy()->year(date('Y'));
    if ($upcomingBirthday->isPast()) {
        $upcomingBirthday->addYear();
    }
    return Carbon::now()->diffInDays($upcomingBirthday, false);
}
function hasProduct($userID, $name)
{

    if (UserProduct::where(['user_id' => intval($userID), 'product_name' => $name])->count() > 0) {
        return true;
    } else {
        return false;
    }
}
function getTax($name)
{
    $branchId = auth('admin')->user()->branch_id ?? null;
    $product = Product::select('product_tax')->where(['product_name' => $name]);
    if ($branchId) {
        $product->where('branch_id', $branchId);
    }
    $product = $product->first();
    return is_null($product) ? 0 : $product->product_tax;
}

function getAddedItemTotal($productTax, $qty, $price)
{
    $taxRatesList = [
        18 => 1.18,
        12 => 1.12,
        5  => 1.05,
        0  => 1.00,
    ];
    //  dd($price);
    $totalAmt = ($qty * $price);
    // dd($totalAmt);
    return round($totalAmt / $taxRatesList[$productTax], 2);
}

if (!function_exists('bindMsg')) {
    function bindMsg(string $msg, string $class)
    {
        $msg = ($msg);
        $data = compact('msg', 'class');
        return $data;
    }
}
if (!function_exists('createUrlSlug')) {
    function createUrlSlug($urlString)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $urlString);
        return strtolower($slug);
    }
}
if (!function_exists('can')) {
    function can($module)
    {
        if (auth()->guard('admin')->user()->can($module) || auth()->guard('admin')->user()->isSuperAdmin) {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('hasPermission')) {
    function hasPermission($module)
    {
        if (auth()->guard('admin')->user()->can($module) || auth()->guard('admin')->user()->isSuperAdmin) {
            return true;
        } else {
            abort(401);
        }
    }
}
function hasTwoProduct($userID, $name, $count)
{
    $name = strtolower($name);
    $userID = intval($userID);
    if ($name === 'socks') {
        $record = UserProduct::where(['user_id' => $userID, 'product_name' => $name])
            ->where('qty', '>', 0)
            ->latest()
            ->first();
        $qty = $record ? $record->qty : 0;
        return $qty > $count;
    }
    return UserProduct::where(['user_id' => $userID, 'product_name' => $name])->where('qty', '>', 0)->exists();
}
function getProductPrice($name, $branchId = null)
{
    $product = Product::where('product_name', $name);
    if ($branchId) {
        $product->where('branch_id', $branchId);
    }
    $product = $product->first();
    return $product->product_price ?? null;
}

function couponCountUpdate($code)
{
    if ($code) {
        $branchId = auth('admin')->user()->branch_id ??  auth('admin')->user()->branch_id;
        $coupon = Coupon::where('coupon_name', $code);
        if ($branchId) {
            $coupon->where('branch_id', $branchId);
        }
        $coupon = $coupon->first();
        if ($coupon) {
            $coupon->coupon_count = ($coupon->coupon_count ?? 0) + 1;
            $coupon->save();
            return true;
        }
        return false;
    }
    return false;
}

function parseDateInHuman($date)
{

    $createdAt = Carbon::parse($date);
    return $createdAt->diffForHumans();
}

function branchName($branchId)
{
    $branchName = Branch::where('id', $branchId)->first();
    return $branchName != null ? $branchName->name : '';
}
// $msg = "Thank you for visiting HappyOn Party & Play. We hope you had a great time. Please spare 2 mins for your valuable review: https://rb.gy/pvkwg";
// $template_id = "1707168821233182292";
// dd(sendsms('7079322204', $msg, $template_id));

function branchInfo($branchId)
{
    $branch = Branch::with('admin')->where('id', $branchId)->first();
    return $branch;
}




if (! function_exists('remaining_time')) {
    /**
     * Calculate the remaining time for a booking in “HH : mm : ss” format.
     *
     * @param  string|null  $bookingDate   // e.g. 2025‑07‑14
     * @param  string|null  $startTime     // e.g. 13:30  (from event->Hours)
     * @param  string|null  $kidHours      // e.g. 1, 1.5 or "Unlimited"
     * @param  string       $timezone      // default: Asia/Kolkata
     * @return string                      // formatted “HH : mm : ss”
     */
    function remaining_time($bookingDate, $startTime, $kidHours, $timezone = 'Asia/Kolkata'): string
    {
        if (! $bookingDate || ! $startTime) {
            return '00 : 00 : 00';
        }
        $start = Carbon::parse("{$bookingDate} {$startTime}:00", $timezone);
        $hours   = ($kidHours === 'Unlimited') ? 1 : (float) ($kidHours ?? 0);
        $end     = (clone $start)->addSeconds($hours * 3600);

        $secondsLeft = max(0, $end->diffInSeconds(now($timezone), false) * -1);

        $h = floor($secondsLeft / 3600);
        $m = floor(($secondsLeft % 3600) / 60);
        $s = $secondsLeft % 60;

        return sprintf('%02d : %02d : %02d', $h, $m, $s);
    }
}
