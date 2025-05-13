<?php

namespace App\Http\Controllers;

use App\Models\EventBooking;
use App\Models\EventItem;
use App\Models\MembershipBuy;
use App\Models\MembershipHistory;
use App\Models\MembershipPlan;
use App\Models\OpenBilling;
use App\Models\User;

class TrashController extends Controller
{
    public function index()
    {
        $users = User::onlyTrashed()->get();
        $mps = MembershipPlan::onlyTrashed()->get();
        $mhs = MembershipHistory::with('singleUser')->onlyTrashed()->get();
        $mbs = MembershipBuy::with('singleUser')->onlyTrashed()->get();
        $eventItems = EventItem::onlyTrashed()->get();
        $events = EventBooking::onlyTrashed()->get();
        $billings = OpenBilling::onlyTrashed()->get();
        return view('happyView.trash', compact('users', 'mps', 'mhs', 'mbs', 'events', 'billings', 'eventItems'));
    }
    public function restore($id, $model)
    {
        $model = app()->make('App\\Models\\' . $model);
        $item = $model->withTrashed()->find($id);
        if ($item) {
            $item->restore();
            return redirect()->back()->with(bindMsg("Item Restored Successfully", 'success'));
        } else {
            return redirect()->back()->with(bindMsg("Something Went Wrong", 'danger'));
        }
    }

    public function delete($id, $model)
    {
        $model = app()->make('App\\Models\\' . $model);
        $item = $model->withTrashed()->find($id);
        if ($item) {
            $item->forceDelete();
            return redirect()->back()->with(bindMsg("Item Permamently Deleted Successfully", 'success'));
        } else {
            return redirect()->back()->with(bindMsg("Something Went Wrong", 'danger'));
        }
    }
}
