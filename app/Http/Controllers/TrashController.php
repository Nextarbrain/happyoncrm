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
        $branchId = auth('admin')->user()->branch_id ?? null;
        $users = User::query();
        if($branchId) {
            $users->where('branch_id', $branchId);
        }
        $users = $users->onlyTrashed()->get();
        $mps = MembershipPlan::onlyTrashed()->get();
        $mhs = MembershipHistory::with('singleUser');
        // if($branchId) {
        //     $mhs->where('branch_id', $branchId);
        // }
        $mhs = $mhs->onlyTrashed()->get();

        $mbs = MembershipBuy::with('singleUser');
        // if($branchId) {
        //     $mbs->where('branch_id', $branchId);
        // }
        $mbs = $mbs->onlyTrashed()->get();

        $eventItems = EventItem::query();
        if($branchId) {
            $eventItems->where('branch_id', $branchId);
        }
        $eventItems = $eventItems->onlyTrashed()->get();

        $events = EventBooking::query();
        if($branchId) {
            $events->where('branch_id', $branchId);
        }
        $events = $events->onlyTrashed()->get();

        $billings = OpenBilling::query();
        if($branchId) {
            $billings->where('branch_id', $branchId);
        }
        $billings = $billings->onlyTrashed()->get();
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
