<?php

namespace App\Http\Controllers;

use App\Models\Admin;

use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;


class AdminPermission extends Controller
{
    public function index(Request $req)
    {
        if ($req->isMethod('POST')) {
            return $this->createUser($req);
        } elseif ($req->isMethod('GET')) {
            return $this->createAdminIndex();
        }
    }
    private function createUser($req)
    {
        $roles = Role::pluck('name', 'name')->all();
        $req->validate([
            'name' => 'required|regex:/(^[A-Za-z ]+$)+/',
            'email' => "required|email|unique:admins,email",
            'password' => 'required',
            'confirm_password' => 'same:password',
            'roles' => "required"
        ]);
        $admin = new Admin;
        $admin->name = $req->name;
        $admin->email = $req->email;
        $admin->role = $req->roles ?? null;
        $admin->password = Hash::make($req->password);
        $admin->save();
        $admin->assignRole($req->roles);
        return redirect()->back()->with(bindMsg("Last Created User: $req->name", 'success'));
    }
    private function createAdminIndex()
    {
        $users = Admin::all();
        $role = [];
        $roles = Role::pluck('name', 'name')->all();
        return view('happyView.permission.manageAdmin', compact('roles', 'users'));
    }

    public function role(Request $req)
    {
        if ($req->isMethod('POST')) {
            return $this->store($req);
        } elseif ($req->isMethod('GET')) {
            $permissions = [];
            $permission = Permission::get();
            $roles = Role::get();
            foreach ($permission as $key => $value) {
                $permissions[$value->id] = $value->name;
            }
            return view('happyView.permission.createRole', compact('permissions', 'roles'));
        }
    }
    public function edit(Request $req, $id)
    {
        $permission = Permission::get();
        $user = Role::find(intval($id));
        if ($req->isMethod('GET')) {
            $permissions = [];
            foreach ($permission as $key => $value) {
                $permissions[$value->id] = $value->name;
            }
            return view('happyView.permission.editRole', compact('user', 'permissions'));
        } elseif ($req->isMethod('PUT')) {
            $user->update(['guard_name' => 'admin', 'name' => $req->name]);
            $user->syncPermissions($req->permission);
            return redirect()->back()->with(bindMsg("Permission Updated Successfully! ", 'success'));
        }
    }

    public function store(object $req)
    {
        $req->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['guard_name' => 'admin', 'name' => $req->name]);
        $role->syncPermissions($req->permission);
        return redirect()->back()->with(bindMsg("Role Has Been Created!", 'success'));
    }
    public function editAdmin(Request $req, $id)
    {
        if ($req->isMethod('GET')) {
            $user = Admin::find(intval($id));

            $roles = Role::pluck('name', 'name')->all();
            $userRole =  $user->getRoleNames()->first();
            return view('happyView.permission.editAdmin', compact('user', 'roles', 'userRole'));
        } elseif ($req->isMethod('PUT')) {
            $req->validate([
                'name' => 'required|regex:/(^[A-Za-z ]+$)+/',
                'email' => "required|email|unique:admins,email,$id",
                'confirm_password' => 'same:password',
                'roles' => "required"
            ]);
            $role = $req->role;
            $admin = Admin::findOrFail($id);
            $admin->name = $req->name;
            $admin->email = $req->email;
            $admin->role = $req->roles ?? null;
            if (isset($req->password)) {
                $admin->password =  Hash::make($req->password);
            }
            $admin->save();
            if ($admin->role != $role) {
                $admin->removeRole($req->roles);
                $admin->assignRole($req->roles);
            }
            return redirect()->back()->with(bindMsg("Role and Profile Updated", 'success'));
        }
    }
}
