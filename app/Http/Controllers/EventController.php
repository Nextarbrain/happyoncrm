<?php

namespace App\Http\Controllers;

use Carbon;
use Exception;
use Dompdf\Dompdf;
use App\Models\Kid;
use App\Models\User;
use App\Models\Admin;
use App\Models\Event;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\DayPrice;
use App\Models\EventItem;
use App\Models\UserDetail;
use App\Models\UserProduct;
use App\Models\EventBooking;
use Illuminate\Http\Request;
use App\Models\MembershipBuy;
use App\Models\PaymentMethod;
use App\Models\DeleteActivity;
use App\Models\MembershipPlan;
use App\Models\Branch;

//import model admin
use App\Models\MembershipHistory;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

//import class hash password
use Illuminate\Support\Facades\Hash;

class EventController extends Controller
{
    public function eventBookingReport(Request $request)
    {
        $branchId = !empty($request->branch_id) && count($request->branch_id) > 0 ? $request->branch_id : (auth('admin')->user()->branch_id ? [strval(auth('admin')->user()->branch_id)]: null);
        if (isset($request->daterange) && $request->daterange != "") {
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('Y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('Y-m-d', strtotime($daterange[1]));
            if ($daterange[0] == $daterange[1]) {
                $event_bookings = EventBooking::with('branch')->whereDate('booking_date', $daterange[0])->where('payment_status', 'Paid');
                if ($branchId) {
                    $event_bookings->whereIn('branch_id', $branchId);
                }
                $event_bookings = $event_bookings->get();

            } else {
                $event_bookings = EventBooking::with('branch')->whereBetween('booking_date', [$daterange[0], $daterange[1]])->where('payment_status', 'Paid');
                if ($branchId) {
                    $event_bookings->whereIn('branch_id', $branchId);
                }
                $event_bookings = $event_bookings->get();
            }
            return view('happyView.events-booking-report', compact('event_bookings'));
        }
    }

    public function eventsView(Request $req)
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        if($req->fromDate && $req->toDate){
            $event_bookings = EventBooking::whereBetween('booking_date', [$req->fromDate, $req->toDate]);
            if ($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->get();
        }else{
            $event_bookings = EventBooking::query();
            if($branchId) {
                $event_bookings->where('branch_id', $branchId);
            }
            $event_bookings = $event_bookings->latest()->get();

        }

        return view('happyView.events', ['event_bookings' => $event_bookings]);
    }
    public function eventsbookingmonthView()
    {
        $event_booking_month = EventBooking::whereMonth('booking_date', '=', Carbon\Carbon::now())->get();
        return view('happyView.events', ['event_bookings' => $event_booking_month]);
    }
    public function upcomingeventsbookingView()
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $today = Carbon\Carbon::today();
        $event_bookings = EventBooking::whereDate('booking_date', '>=', $today);
        if($branchId) {
            $event_bookings->where('branch_id', $branchId);
        }
        $event_bookings = $event_bookings->latest()->get();
        return view('happyView.events', ['event_bookings' => $event_bookings]);
    }

    public function addEventView(Request $request)
    {
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        return view('happyView.events-add', compact('branches'));
    }

    public function editEventView(Request $request)
    {
        $branches = Branch::select('id', 'name')->where('status', 'Active')->get()->toArray();
        $eventbooking = EventBooking::where('id', $request->id)->first();
        $eventitems = EventItem::where('event_booking_id', $request->id)->get();
        return view('happyView.events-edit', compact('eventbooking', 'eventitems', 'branches'));
    }

    public function invoiceEvent(Request $request)
    {

        $eventbooking = EventBooking::where('id', $request->id)->first();
        $eventitems = EventItem::where('event_booking_id', $request->id)->get();

        return view('happyView.event-invoice', compact('eventbooking', 'eventitems'));
    }


    public function editEventPost(Request $request)
    {
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $id = $request->id;
        $eventbooking = EventBooking::where('id', $id)->first();
        $eventbooking->booking_date = $request->booking_date;
        $eventbooking->booking_time_from = $request->booking_time_from;
        $eventbooking->branch_id = $branchId;
        $eventbooking->booking_time_to = $request->booking_time_to;
        $eventbooking->phone = $request->phone;
        $eventbooking->bill_name = $request->billing_name;
        $eventbooking->contact_person = $request->contact;
        $eventbooking->kid_name = $request->kid_name;
        $eventbooking->kid_dob = $request->kid_dob;
        $eventbooking->payment_method = $request->payment_method;
        $eventbooking->payment_status = $request->payment_status;

        $eventbooking->couponcode = $request->couponcode;
        $eventbooking->couponvalue = $request->couponvalue;

        $eventbooking->totalamount = $request->totalAmount;
        $eventbooking->subtotal = $request->subtotal;
        $eventbooking->gst18 = $request->gst18;
        $eventbooking->gst12 = $request->gst12;
        $eventbooking->gst5 = $request->gst5;

        if ($request->advance_amount > 0) {
            $eventbooking->advance_amount = $request->advance_amount;
            $eventbooking->advance_payment_method = $request->advance_payment_method;
            $eventbooking->due_amount = $request->due_amount;
            //  $eventbooking->due_payment_method = $request->due_payment_method;

            $eventbooking->payment_in_installment = $request->payment_in_installment;
            $eventbooking->installment_amount = $request->installment_amount;
            $eventbooking->installment_amount_method = $request->installment_amount_method;
            $eventbooking->final_installment = $request->final_installment;
            $eventbooking->final_installment_method = $request->final_installment_method;
        }

        $eventbooking->save();

        if ($id) {

            $item_name = $request->item_name;
            $item_price = $request->item_price;
            $item_qty = $request->item_qty;
            $item_tax = $request->item_tax;
            $event_item_ids = $request->event_item_ids;


            for ($i = 0; $i <= count($event_item_ids) - 1; $i++) {

                if ($event_item_ids[$i] > 0 && $event_item_ids[$i] != null) {
                    $event_item = EventItem::where('id', $event_item_ids[$i])->first();
                } else {
                    $event_item = new EventItem();
                }

                if (isset($item_name[$i])) {
                    $event_item->event_booking_id = $id;
                    $event_item->branch_id = $branchId;
                    $event_item->item_name = $item_name[$i];
                    $event_item->item_price = $item_price[$i];
                    $event_item->item_qty = $item_qty[$i];
                    $event_item->item_tax = $item_tax[$i];
                    $event_item->save();
                }
            }
        }
        return redirect('/events')->with('success', 'Successfully Updated!');
    }

    public function addEventPost(Request $request)
    {
        // $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $branchId = $request->branch_id != null ? (int)$request->branch_id :  auth('admin')->user()->branch_id;
        $eventbooking = new EventBooking();
        $eventbooking->booking_date = $request->booking_date;
        $eventbooking->booking_time_from = $request->booking_time_from;
        $eventbooking->booking_time_to = $request->booking_time_to;
        $eventbooking->branch_id = $branchId;
        $eventbooking->phone = $request->phone;
        $eventbooking->bill_name = $request->billing_name;
        $eventbooking->contact_person = $request->contact;
        $eventbooking->kid_name = $request->kid_name;
        $eventbooking->kid_dob = $request->kid_dob;
        $eventbooking->payment_method = $request->payment_method;
        $eventbooking->payment_status = $request->payment_status;

        $eventbooking->couponcode = $request->couponcode;

        $eventbooking->couponvalue = $request->couponvalue;

        $eventbooking->totalamount = $request->totalAmount;
        $eventbooking->subtotal = $request->subtotal;
        $eventbooking->gst18 = $request->gst18;
        $eventbooking->gst12 = $request->gst12;
        $eventbooking->gst5 = $request->gst5;

        if ($request->advance_amount > 0) {
            $eventbooking->advance_amount = $request->advance_amount;
            $eventbooking->advance_payment_method = $request->advance_payment_method;
            $eventbooking->due_amount = $request->due_amount;
            $eventbooking->payment_in_installment = $request->payment_in_installment;
            $eventbooking->installment_amount = $request->installment_amount;
            $eventbooking->installment_amount_method = $request->installment_amount_method;
            $eventbooking->final_installment = $request->final_installment;
            $eventbooking->final_installment_method = $request->final_installment_method;
        }
        $eventbooking->save();
        couponCountUpdate($request->couponcode);

        if ($eventbooking->id) {

            $item_name = $request->item_name;
            $item_price = $request->item_price;
            $item_qty = $request->item_qty;
            $item_tax = $request->item_tax;


            for ($i = 0; $i <= count($item_name) - 1; $i++) {
                $event_item = new EventItem();
                if (isset($item_name[$i])) {
                    $event_item->event_booking_id = $eventbooking->id;
                    $event_item->item_name = $item_name[$i];
                    $event_item->branch_id = $branchId;
                    $event_item->item_price = $item_price[$i];
                    $event_item->item_qty = $item_qty[$i];
                    $event_item->item_tax = $item_tax[$i];
                    $event_item->save();
                }
            }
        }
        return redirect('/events')->with('success', 'Successfully Created!');
    }

    public function addEventItem(Request $request)
    {
        if ($request->input('item_name') == null) {
            return back()->with('error', 'Please Provide item Name');
        }
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $item = new EventItem();
        $item->item_name = $request->input('item_name');
        $item->branch_id = $branchId;
        $item->item_price = $request->input('price');
        $item->item_qty = $request->input('qty');
        $item->item_tax = $request->input('tax');
        $item->item_description = $request->input('item_name');
        $item->save();
        if ($item->id) {
            return back()->with('success', 'Success! item created');
        }
    }

    public function editEventItemPost(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $item = EventItem::where('id', $request->eventItemId)->first();
        $item->branch_id = $branchId;
        $item->item_price = $request->editprice;
        $item->item_qty = $request->editqty;
        $item->item_tax = $request->tax;
        $item->save();

        session()->flash('success', 'Event item Updated successfully!');
        return redirect()->back();
    }

    public function editOpenEventItemModel(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $eventitem = EventItem::where('id', $selectedItems)->first();
        return response()->json($eventitem);
    }

    public function deleteEventItem(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        EventItem::where('id', $selectedItems)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteEvent(Request $request)
    {

        try {
            if (can('event-delete')) {
                $selectedItems = $request->input('selectedItems');
                $integerArray = array_map('intval', $selectedItems);
                EventBooking::whereIn('id', $integerArray)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }
}
