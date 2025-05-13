<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

use Carbon;
use Illuminate\Support\Facades\Http;

//import model admin
use App\Models\Admin;
//import class hash password
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function staffsView()
    {
        // $currentTime = Carbon\Carbon::now()->format('H:i:s');
        // $currentDate = Carbon\Carbon::now()->format('Y-m-d');

        // $users = User::with('kid', 'userDetails')->where('role', '!=', 0)->whereDate('created_at',$currentDate)->latest()->get();

        // foreach($users as $user)
        // {
        //     $mobile = $user['userDetails']->phone_no;
        //     $endtime = $user['kid']->end_time;

        //     $to = \Carbon\Carbon::createFromFormat('H:i:s', $currentTime);

        //     $from = \Carbon\Carbon::createFromFormat('H:i:s',$endtime);
        //     $diff_in_hours = $to->diffInMinutes($from);
        //   dd($diff_in_hours);
        //     if($to>$from)
        //     {
        //         if($diff_in_hours >= 60 && $diff_in_hours < 179)
        //         {
        //             $msg = "Thank you for Visiting HappyOn Play We are sure you had an amazing gadget free Playtime. Kindly review us on the: See You Soon Again! For any queries call us on : Visit us on www.Happyonplay.com";
        //             $template_id = "1707168001521740597";
        //             sendsms($mobile, $msg, $template_id);
        //         }
        //     }

        // }

        $user = Admin::all();
        return view('happyView.staffs.index', compact('user'));
    }
    public function addStaff()
    {
        return view('happyView.staffs.add-staff');
    }
    public function addStaffPost(Request $request)
    {

        $staff = new Admin();
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        if (isset($request->password)) {
            $staff->password = Hash::make($request->password);
        }
        $staff->save();
        return redirect('/staffs')->with('success', 'Successfully Added!');
    }

    public function editStaff(Request $request)
    {
        $id = $request->id;
        $user = Admin::where('id', $id)->first();
        return view('happyView.staffs.edit-staff', compact('user'));
    }

    public function editStaffPost(Request $request)
    {
        $id = $request->id;
        $staff = Admin::find($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;
        if (isset($request->password)) {
            $staff->password = Hash::make($request->password);
        }
        $staff->role = $request->role;
        $staff->save();

        return redirect('/staffs')->with('success', 'Successfully Updated!');
    }

    public function deleteStaff(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        Admin::whereIn('id', $selectedItems)->delete();
        return response()->json(['success' => true]);
    }

    public function changeStatus(Request $request)
    {

        Admin::where('id', $request->id)->update(["$request->column" => $request->status]);
        return response()->json(['success' => true]);
    }
}
