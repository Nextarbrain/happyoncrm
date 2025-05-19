<?php

namespace App\Http\Controllers;

use App\Models\Coupon;

use App\Models\OpenBilling;
use App\Models\Admin;
use App\Models\DeleteActivity;
use App\Models\EventItem;

use App\Models\OpenBillingItem;


use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon;
use Illuminate\Support\Facades\Http;
use Dompdf\Dompdf;


//import class hash password
use Illuminate\Support\Facades\Hash;

class OpenBillingController extends Controller
{
    public function openBillingReport(Request $request)
    {
        if (isset($request->daterange) && $request->daterange != "") {
            $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
            $daterange = explode("-", $request->daterange);
            $daterange[0] = date('y-m-d', strtotime($daterange[0]));
            $daterange[1] = date('y-m-d', strtotime($daterange[1]));
            $open_billings = OpenBilling::whereBetween('created_at', [$daterange[0], $daterange[1]]);
            if ($branchId) {
                $open_billings->where('branch_id', $branchId);
            }
            $open_billings = $open_billings->get();
            return view('happyView.openBilling.open-billing-report', compact('open_billings'));
        }
    }

    public function openBilling()
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $open_billings = OpenBilling::query();
        if ($branchId) {
            $open_billings->where('branch_id', $branchId);
        }
        $open_billings = $open_billings->latest()->get();
        return view('happyView.openBilling.index', ['open_billings' => $open_billings]);
    }



    public function addOpenBillingView(Request $request)
    {

        return view('happyView.openBilling.add-open-billing');
    }

    public function editOpenBillingView(Request $request)
    {

        $open_billing = OpenBilling::where('id', $request->id)->first();
        $open_billing_items = OpenBillingItem::where('open_billing_id', $request->id)->get();


        return view('happyView..openBilling.edit-open-billing', compact('open_billing', 'open_billing_items'));
    }

    public function invoiceOpenBilling(Request $request)
    {

        $openbilling = OpenBilling::where('id', $request->id)->first();
        $openbillingitems = OpenBillingItem::where('open_billing_id', $request->id)->get();

        return view('happyView.openBilling.open-billing-invoice', compact('openbilling', 'openbillingitems'));
    }


    public function editOpenBillingPost(Request $request)
    {
        // dd(count($request->open_billing_item_ids));
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $id = $request->id;
        $openbilling = OpenBilling::where('id', $id)->first();

        $openbilling->phone = $request->phone;
        $openbilling->bill_name = $request->billing_name;

        $openbilling->payment_method = $request->payment_method;
        $openbilling->branch_id = $branchId;

        $openbilling->couponcode = $request->couponcode;
        couponCountUpdate($request->couponcode);
        $openbilling->couponvalue = $request->couponvalue;

        $openbilling->totalamount = $request->totalAmount;
        $openbilling->subtotal = $request->subtotal;
        $openbilling->gst18 = $request->gst18;
        $openbilling->gst12 = $request->gst12;
        $openbilling->gst5 = $request->gst5;


        $openbilling->save();

        if ($id) {

            $item_name = $request->item_name;
            $item_price = $request->item_price;
            $item_qty = $request->item_qty;
            $item_tax = $request->item_tax;
            $open_billing_item_ids = $request->open_billing_item_ids;


            for ($i = 0; $i <= count($open_billing_item_ids) - 1; $i++) {

                if ($open_billing_item_ids[$i] > 0 && $open_billing_item_ids[$i] != null) {
                    $event_item = OpenBillingItem::where('id', $open_billing_item_ids[$i])->first();
                } else {
                    $event_item = new OpenBillingItem();
                }

                if (isset($item_name[$i])) {
                    $event_item->open_billing_id = $id;
                    $event_item->item_name = $item_name[$i];
                    $event_item->item_price = $item_price[$i];
                    $event_item->item_qty = $item_qty[$i];
                    $event_item->item_tax = $item_tax[$i];
                    $event_item->branch_id = $branchId;
                    $event_item->save();
                }
            }
        }
        return redirect('/open-billing')->with('success', 'Successfully Updated!');
    }

    public function addOpenBillingPost(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ?  auth('admin')->user()->branch_id : null;
        $openbilling = new OpenBilling();


        $openbilling->phone = $request->phone;
        $openbilling->bill_name = $request->billing_name;

        $openbilling->payment_method = $request->payment_method;
        $openbilling->branch_id = $branchId;

        $openbilling->couponcode = $request->couponcode;
        $openbilling->couponvalue = $request->couponvalue;

        $openbilling->totalamount = $request->totalAmount;
        $openbilling->subtotal = $request->subtotal;
        $openbilling->gst18 = $request->gst18;
        $openbilling->gst12 = $request->gst12;
        $openbilling->gst5 = $request->gst5;


        $openbilling->save();

        if ($openbilling->id) {

            $item_name = $request->item_name;
            $item_price = $request->item_price;
            $item_qty = $request->item_qty;
            $item_tax = $request->item_tax;


            for ($i = 0; $i <= count($item_name) - 1; $i++) {
                $open_billing_item = new OpenBillingItem();
                if (isset($item_name[$i])) {
                    $open_billing_item->open_billing_id = $openbilling->id;
                    $open_billing_item->item_name = $item_name[$i];
                    $open_billing_item->item_price = $item_price[$i];
                    $open_billing_item->item_qty = $item_qty[$i];
                    $open_billing_item->item_tax = $item_tax[$i];
                    $open_billing_item->branch_id = $branchId;
                    $open_billing_item->save();
                }
            }
        }
        return redirect('/open-billing')->with('success', 'Successfully Created!');
    }

    public function addEventItem(Request $request)
    {
        if ($request->input('item_name') == null) {
            return back()->with('error', 'Please Provide item Name');
        }
        $branchId = auth('admin')->user()->branch_id ? auth('admin')->user()->branch_id : null;
        $item = new EventItem();
        $item->item_name = $request->input('item_name');
        $item->item_price = $request->input('price');
        $item->item_qty = $request->input('qty');
        $item->item_tax = $request->input('tax');
        $item->item_description = $request->input('item_name');
        $item->branch_id = $branchId;
        $item->save();
        if ($item->id) {
            return back()->with('success', 'Success! item created');
        }
    }

    public function editEventItemPost(Request $request)
    {
        $branchId = auth('admin')->user()->branch_id ? auth('admin')->user()->branch_id : null;
        $item = EventItem::where('id', $request->eventItemId)->first();
        $item->item_price = $request->editprice;
        $item->item_qty = $request->editqty;
        $item->item_tax = $request->tax;
        $item->branch_id = $branchId;
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

    public function deleteOpenBillingItem(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        OpenBillingItem::where('id', $selectedItems)->delete();
        return response()->json(['success' => true]);
    }

    public function deleteOpenBilling(Request $request)
    {
        $admin = Admin::find(auth('admin')->user()->id);
        if ($admin->has_delete == 1) {
            $selectedItems = $request->input('selectedItems');
            if (strtolower($admin->role) != 'Super Admin' && strtolower($admin->role) != 'Admin') {
                $data = OpenBilling::whereIn('id', $selectedItems)->get();
                DeleteActivity::create(['data' =>  $data, 'name' => $admin->name, 'deleted_by' => $admin->toJson()]);
            }
            OpenBilling::whereIn('id', $selectedItems)->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false], 400);
        }
    }
}
