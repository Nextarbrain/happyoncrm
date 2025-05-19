<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Branch;

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
        $branchId = $req->branch_id ? $req->branch_id : auth('admin')->user()->branch_id;
        $roles = Role::query();
        if($branchId) {
            $roles->where('branch_id', $branchId); 
        }
        $roles = $roles->pluck('name', 'name')->all();
        $req->validate([
            'name' => 'required|regex:/(^[A-Za-z ]+$)+/',
            'email' => "required|email|unique:admins,email",
            'password' => 'required',
            'confirm_password' => 'same:password',
            'roles' => "required"
        ]);
        $admin = new Admin;
        $admin->name = $req->name;
        $admin->branch_id = $branchId;
        $admin->email = $req->email;
        $admin->role = $req->roles ?? null;
        $admin->password = Hash::make($req->password);
        $admin->save();
        $admin->assignRole($req->roles);
        return redirect()->back()->with(bindMsg("Last Created User: $req->name", 'success'));
    }
    private function createAdminIndex()
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $users = Admin::query();
        if ($branchId) {
            $users->where('branch_id', $branchId);
        }
        $users = $users->get();
        $role = [];
        $role = Role::query();
       if ($branchId) {
            $role->where('branch_id', $branchId); 
        }
        $role = $role->get()->toArray();
        $roles = [];

        if (count($role) > 0) {
            foreach ($role as $value) {
                if ($value) {
                    $branch = Branch::find($value['branch_id']);
                    if ($branch) {
                        $roles[$value['name']] = $value['name'] . '-' . $branch->name;
                    } else {
                        $roles[$value['name']] = $value['name'] . '- No role assigned';
                    }
                }
            }
        }
        $branches = Branch::pluck('name', 'id')->all();
        return view('happyView.permission.manageAdmin', compact('roles', 'users','branches'));
    }

    public function role(Request $req)
    {
        $branchId = auth('admin')->user()->branch_id ?? null;
        $branches = Branch::pluck('name', 'id')->all();
        if ($req->isMethod('POST')) {
            return $this->store($req);
        } elseif ($req->isMethod('GET')) {
            $permissions = [];
            $permission = Permission::get();
            $roles = Role::query();
            if($branchId) {
                $roles->where('branch_id', $branchId);
            }
            $roles = $roles->get();
            foreach ($permission as $key => $value) {
                $permissions[$value->id] = $value->name;
            }
            return view('happyView.permission.createRole', compact('permissions', 'roles', 'branches'));
        }
    }
    public function edit(Request $req, $id)
    {
        $permission = Permission::get();
        $user = Role::find(intval($id));
        $branches = Branch::pluck('name', 'id')->all();
        if ($req->isMethod('GET')) {
            $permissions = [];
            foreach ($permission as $key => $value) {
                $permissions[$value->id] = $value->name;
            }
            return view('happyView.permission.editRole', compact('user', 'permissions', 'branches'));
        } elseif ($req->isMethod('PUT')) {
            $user->update(['guard_name' => 'admin', 'name' => $req->name, 'branch_id' => $req->branch_id ]);
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
        $role = Role::create(['guard_name' => 'admin', 'name' => $req->name, 'branch_id' => $req->branch_id ]);
        $role->syncPermissions($req->permission);
        return redirect()->back()->with(bindMsg("Role Has Been Created!", 'success'));
    }
    public function editAdmin(Request $req, $id)
    {
        $branchId = $req->branch_id ? $req->branch_id : auth('admin')->user()->branch_id;
        $branches = Branch::pluck('name', 'id')->all();
        if ($req->isMethod('GET')) {
            $user = Admin::find(intval($id));

            $role = Role::query();
            if($branchId) {
                $role->where('branch_id', $branchId); 
            }
            $role = $role->get();
            if (count($role) > 0) {
                foreach ($role as $value) {
                    if ($value) {
                        $branch = Branch::find($value['branch_id']);
                        if ($branch) {
                            $roles[$value['name']] = $value['name'] . '-' . $branch->name;
                        } else {
                            $roles[$value['name']] = $value['name'] . '- No role assigned';
                        }
                    }
                }
            }

            $userRole =  $user->getRoleNames()->first();
            return view('happyView.permission.editAdmin', compact('user', 'roles', 'userRole','branches'));
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
            $admin->branch_id = $branchId;
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
