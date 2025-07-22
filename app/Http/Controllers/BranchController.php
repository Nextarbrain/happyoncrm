<?php

namespace App\Http\Controllers;
use App\Models\MembershipBuy;
use App\Models\Branch;
use App\Models\Admin;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon;
use Illuminate\Support\Facades\Http;
use Dompdf\Dompdf;


//import class hash password
use Illuminate\Support\Facades\Hash;

class BranchController extends Controller
{
    public function branch()
    {
        $branch = Branch::with('admin')->latest()->get();
        return view('happyView.branch.index', ['branch' => $branch]);
    }

    public function branchView(Request $request)
    {
        return view('happyView.branch.add-branch');
    }

    public function editBranchView(Request $request)
    {
        $branch = Branch::with('admin')->where('id', $request->id)->first();
        return view('happyView.branch.edit-branch', compact('branch'));
    }

    public function editBranchPost(Request $request)
    {
        $emailExist = Admin::where('id', $request->email)->first();
        if($emailExist !==  null) {
             return redirect('/create-branch')->with('success', 'Email already exists!');
        }
        $id = $request->id;
        $adminId = $request->admin_id;
        $branch = Branch::where('id', $id)->first();
        $branch->name = $request->name;
        $branch->location = $request->location;
        $branch->pincode = $request->pincode;
        $branch->phone = $request->phone;
        $branch->gstno = $request->gstno;
        $branch->address = $request->address ?? '';
        $branch->status = $request->status;
        $branch->type = $request->type;
        $branch->save();

        if ($id) {
            $admin = Admin::where('id', $adminId)->first();
            if($admin !== null) {
                $admin->name = $request->person_name;
                $admin->role = $request->type.' Superadmin';
                $admin->isSuperAdmin = 1;
                if (isset($request->password)) {
                    $admin->password = Hash::make($request->password);
                }
                $admin->save();
            }
        }
        return redirect('/branch')->with('success', 'Successfully Updated!');
    }

    public function addBranch(Request $request)
    {
        $branch = new Branch();
        $branch->name = $request->name;
        $branch->location = $request->location;
        $branch->pincode = $request->pincode;
        $branch->phone = $request->phone;
        $branch->gstno = $request->gstno;
        $branch->address = $request->address ?? '';
        $branch->user_id  = auth()->guard('admin')->user()->id;
        $branch->status = $request->status;
        $branch->type = $request->type;
        $branch->save();
        if ($branch->id) {
            $admin = new Admin();
            $admin->name = $request->person_name;
            $admin->email = $request->email;
            $admin->phone = $request->phone;
            $admin->branch_id  = $branch->id;
            $admin->role = $request->type.' Superadmin';
            $admin->isSuperAdmin = 1;
            if (isset($request->password)) {
                $admin->password = Hash::make($request->password);
            }
            $admin->save();
        }
        return redirect('/branch')->with('success', 'Successfully Created!');
    }

    public function deleteBranch(Request $request)
    {
        try {
            if (can('branch-delete')) {
                $selectedItems = $request->input('selectedItems');
                Branch::whereIn('id', $selectedItems)->delete();
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false], 400);
            }
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }

    public function getMembershipUserByBranch(Request $request)
    {
        try {
            $branches = $request->branches;
            $membershipUsers = MembershipBuy::with('user');
            if ($branches) {
                $membershipUsers->whereIn('branch_id', $branches);
            }
            $membershipUsers = $membershipUsers->latest()->get();
            return response()->json(['status' => true, 'data' => $membershipUsers ]);
        } catch (Exception $e) {
            return response()->json(['success' => $e->getMessage()], 500);
        }
    }


}
