<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Log_error;
use App\Models\Site;
use App\Models\User;
use App\Models\User_relation;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use DataTables;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            $data = User::with('site')->orderby('id', 'desc');
            return DataTables($data)
                ->addColumn('role', function ($row) {
                    $btn = '';
                    foreach ($row->getRoleNames()  as $role) {
                        $btn .= '<small class="badge badge-success">' . $role . '</small ><br>';
                    }
                    return $btn;
                })
                ->addColumn('site', function ($row) {
                    $btn = '';
                    foreach ($row->site as $site) {
                        $btn .= '<small class="badge badge-success">' . $site->site_name . '</small ><br>';
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->can('user-edit')) {
                        $btn .= '<a href="' . route('users.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                    }
                    if ($user->can('user-delete')) {
                        $btn .= '<a href="' . route('users.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'role', 'site'])
                ->make(true);
        }

        return view('users.admin_index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        $sites = Site::pluck('site_name', 'id')->all();
        $customers = Customer::pluck('customer_name', 'id')->all();
        return view('users.create', compact('roles', 'sites', 'customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 10 characters in length
                // 'regex:/[a-z]/',      // must contain at least one lowercase letter
                // 'regex:/[A-Z]/',      // must contain at least one uppercase letter
                // 'regex:/[0-9]/',      // must contain at least one digit
                // 'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'required|same:password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['created_by_id'] = Auth::id();

        try {
            $user = User::create($input);
            $user->assignRole($request->input('roles'));
            $user->site()->attach($request->input('sites'));

            return redirect()->route('users.index')
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'User', 'Crated User', $e);
            return redirect()->route('users.index')->with('error', 'ERROR');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $sites = Site::pluck('site_name', 'id')->all();
        $customers = Customer::pluck('customer_name', 'customer_name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $usersite = DB::table("site_user")->where("site_user.user_id", $id)
            ->pluck('site_user.site_id')
            ->all();

        return view('users.edit', compact('user', 'roles', 'customers', 'sites', 'userRole', 'usersite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        DB::table('site_user')->where('user_id', $id)->delete();

        $user->site()->attach($request->input('sites'));
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != 1) {
            User::find($id)->delete();
            return redirect()->route('users.index')
                ->with('success', ' User deleted successfully');
        } else {
            return redirect()->route('users.index')
                ->with('error', ' Cannot Delete User');
        }
    }
}
