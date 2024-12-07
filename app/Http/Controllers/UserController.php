<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(['permission:user.read'], ['only' => ['index', 'datatables', 'show']]);
        $this->middleware(['permission:user.create'], ['only' => ['create', 'store', 'check_email_exists']]);
        $this->middleware(['permission:user.update'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:user.delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.users-list');
    }

    public function datatables()
    {
        $users = User::with('roles')->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->editColumn('name', function ($user) {
                return $user->name;
            })
            ->editColumn('email', function ($user) {
                return $user->email;
            })
            ->editColumn('role', function ($user) {
                return isset($user->roles[0]) ? $user->roles[0]->name : __('No role');
            })
            ->editColumn('created_at', function ($user) {
                return date('j F, Y, g:i A', strtotime($user->created_at));
            })
            ->addColumn('options', function ($user) {
                return view('user.partials.users-list-options', compact('user'));
            })
            ->rawColumns(['name', 'email', 'role', 'created_at', 'options'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::select('id', 'name')->get();

        return view('user.user-create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'min:4', 'max:255'],
            'email'     => ['required', 'max:255', 'email', 'unique:users'],
            'password'  => ['max:255'],
            'role'      => ['required']
        ]);

        $validated_data = $request->all();

        try {

            $user = User::create([
                'name' => $validated_data['name'],
                'email' => $validated_data['email'],
                'password' => Hash::make($validated_data['password']),
                'email_verified_at' => now(),
            ]);

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        if($user) {

            $role = Role::find($validated_data['role']);

            if($role) {
                $user->assignRole($role->id);
            }

        }

        return redirect()->route('users.index')->with('alert-success', __('User created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.user-show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::select('id', 'name')->get();

        return view('user.user-edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => ['required', 'min:4', 'max:255'],
            'email'     => ['required', 'max:255', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'  => ['max:255'],
            'role'      => ['required']
        ]);

        $validated_data = $request->all();

        try {

            $user->update([
                'name' => $validated_data['name'],
                'email' => $validated_data['email'],
            ]);

            if(!empty($validated_data['password'])) {
                $user->password = Hash::make($validated_data['password']);
                $user->save();
            }

            if(isset($user->roles[0]->id)) {
                $user->removeRole($user->roles[0]->id);
            }

            $role = Role::find($validated_data['role']);

            if($role) {
                $user->assignRole($role->id);
            }

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->route('users.index')->with('alert-success', __('User updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id == 1) {
            abort(404);
        }

        try {

            $user->delete();

        } catch(\Exception $exception) {
            return redirect()->back()->with('alert-error', __('Something went wrong, please try again later.'));
        }

        return redirect()->route('users.index')->with('alert-success', __('User deleted successfully'));
    }

    public function check_email_exists(Request $request)
    {
        $email = $request->input('email');

        $exists = User::where('email', $email)->first();

        $user_id = $request->input('user_id');
        if(!empty($user_id)) {
            $user = User::find($user_id);
            if(isset($exists->email) && $exists->email == $user->email) {
                return response()->json(true);
            }
        }

        return response()->json($exists ? false : true);
    }

}
