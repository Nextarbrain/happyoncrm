<?php
// import model admin
use App\Models\Admin;
// use Exception;
use App\Models\Product;
use App\Models\Coupon;
use Illuminate\Support\Carbon;
use App\Models\UserProduct;

function sendsms($phone, $msg, $template_id)
{
    return true;
    // $api_key = '461E1011B2C0F2';
    // $contacts = $phone;
    // $from = 'HAPYON';
    // $sms_text = urlencode("$msg");
    // $pe_id = '1701167959115997405';
    // try{
    //  $api_url = "https://kutility.org/app/smsapi/index.php?key=" . $api_key . "&campaign=12886&routeid=7&type=text&contacts=" . $contacts . "&senderid=" . $from . "&msg=" . $sms_text . "&template_id=" . $template_id . "&pe_id=" . $pe_id;
    // $response = file_get_contents($api_url);
    // return $response;
    // }catch(Exception $e){

    // }

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
    $product = Product::select('product_tax')->where(['product_name' => $name])->first();
    return is_null($product) ? 0 : $product->product_tax;
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
    if (strtolower($name) == 'socks') {
        $qty = UserProduct::where(['user_id' => intval($userID), 'product_name' => $name])->select('qty')->first();
        if (is_null($qty)) {
            return false;
        }
        if ($qty->qty > $count) {
            return true;
        } else {
            return false;
        }
    } else {
        if (UserProduct::where(['user_id' => intval($userID), 'product_name' => $name])->count() > $count) {
            return true;
        } else {
            return false;
        }
    }
}
function getProductPrice($name)
{
    $product = Product::where('product_name', $name)->first();
    return $product->product_price ?? null;
}

function couponCountUpdate($code)
{
    if($code){
    $coupon = Coupon::where('coupon_name', $code)->first();
    $coupon->coupon_count = $coupon->coupon_count + 1;
    $coupon->save();
    return true;
    }
    return false;
}

function parseDateInHuman($date)
{

    $createdAt = Carbon::parse($date);
    return $createdAt->diffForHumans();
}
    // $msg = "Thank you for visiting HappyOn Party & Play. We hope you had a great time. Please spare 2 mins for your valuable review: https://rb.gy/pvkwg";
    // $template_id = "1707168821233182292";
    // dd(sendsms('7079322204', $msg, $template_id));
