<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:role.read'],  ['only' => ['index', 'show']]);
        $this->middleware(['permission:role.create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:role.update'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:role.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search_text = $request->input('search');

        $roles = Role::where('name', 'like', "%$search_text%")
            ->orderBy('id', 'asc')
            ->paginate(env('PER_PAGE'))
            ->appends(['search' => $request->get('search')]);

        return view('role.roles-list', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::select('id', 'name')
            ->orderBy('id', 'asc')
            ->get();

        $custom_permissions = $this->custom_permissions($permissions);

        return view('role.role-create', [
            'permissions' => $custom_permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'max:255', Rule::unique('roles', 'name')],
            'permissions' => ['required'],
        ]);

        $permissions = [];
        foreach($request->input('permissions') as $selected) {
            $check_exist = Permission::find($selected);
            if($check_exist == null) {
                return redirect()->route('roles.index')->with('alert-error', __('Permission not found!'));
            }
            $permissions[] = $check_exist->name;
        }

        try {

            $role = Role::create(['name' => $request->input('role_name')]);
            $role->syncPermissions($permissions);

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->route('roles.index')->with('alert-success', __('Role created successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $permissions = $role->Permissions()->select('id', 'name')
            ->orderBy('id', 'asc')
            ->get();

        $custom_permissions = $this->custom_permissions($permissions);

        return view('role.role-show', [
            'role' => $role,
            'permissions' => $custom_permissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if($role->id == 1) {
            abort(404);
        }

        $assigned_permissions = $role->Permissions()->select('id', 'name')
            ->orderBy('id', 'asc')
            ->get();

        $all_permissions = Permission::select('id', 'name')
            ->orderBy('id', 'asc')
            ->get();

        $custom_assigned_permissions = $this->custom_permissions($assigned_permissions);
        $custom_all_permissions = $this->custom_permissions($all_permissions);

        return view('role.role-edit', [
            'role' => $role,
            'assigned_permissions' => $custom_assigned_permissions,
            'all_permissions'      => $custom_all_permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if($role->id == 1) {
            abort(404);
        }

        $request->validate([
            'role_name' => ['required', 'max:255', Rule::unique('roles', 'name')->ignore($role->id)],
            'permissions' => ['required'],
        ]);

        $permissions = [];
        foreach($request->input('permissions') as $selected) {
            $check_exist = Permission::find($selected);
            if($check_exist == null) {
                return redirect()->route('roles.index')->with('alert-error', __('Permission not found!'));
            }
            $permissions[] = $check_exist->name;
        }

        try {

            $role->update(['name' => $request->input('role_name')]);
            $role->syncPermissions($permissions);

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->route('roles.index')->with('alert-success', __('Role updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if($role->id == 1) {
            abort(404);
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('alert-error', __('Role cannot be deleted because it is assigned to users!'));
        }

        try {

            $role->revokePermissionTo(Permission::all());
            $role->delete();

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->route('roles.index')->with('alert-success', __('Role deleted successfully'));
    }

    private function custom_permissions($permissions)
    {
        $custom_permissions = [];

        foreach($permissions as $permission) {
            $key = substr($permission->name, 0, strpos($permission->name, '.'));
            $custom_name = substr($permission->name, strpos($permission->name, '.') + 1);
            if(str_starts_with($permission->name, $key)) {
                $custom_permissions[$key][] = [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'view_name' => $custom_name,
                ];
            }
        }

        return $custom_permissions;
    }

}
