<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Rack;
use App\Models\Rack_power_default;
use App\Models\Site;
use DB;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WebnewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:rack-list|rack-create|rack-edit|rack-delete', ['only' => ['index']]);
        $this->middleware('permission:rack-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:rack-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:rack-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            if ($request->ajax()) {
                $data = Rack::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('customer', function ($row) {
                        $btn = $row->customer->customer_name;
                        return $btn;
                    })
                    ->addColumn('site', function ($row) {
                        $btn = $row->site->site_name;
                        return $btn;
                    })
                    ->addColumn('floor', function ($row) {
                        $btn = $row->floor->floor_name;
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $btn = $row->status->status_name;
                        return $btn;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('rack.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        $btn .= '<a href="' . route('rack.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['customer', 'site', 'floor', 'status', 'action'])
                    ->make(true);
            }

            return view('rack.index');
        }
        return redirect()->route('logout');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::pluck('customer_name', 'id')->all();
        $power_default = Rack_power_default::pluck('power_default', 'id')->all();
        return view('rack.create', compact('customer', 'power_default'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'site_id' => 'required',
            'floor_id' => 'required',
            'customer_id' => 'required',
            'rack_name' => ['required', 'max:100', Rule::unique('racks')
                ->where('customer_id', $request->customer_id)],
            'rack_default' => 'required',
            'rack_description' => 'max:200',
        ];

        $customMessages = [
            'site_id.required' => 'The Site field is required.',
            'floor_id.required' => 'The Floor field is required.',
            'floocustomer_idr_id.required' => 'The Customer field is required.'
        ];

        $this->validate($request, $rules, $customMessages);

        $input = $request->all();
        try {
            Rack::create($input);

            return redirect()->route('rack.index')
                ->with('success', 'Rack created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Rack', 'Crated New Rack', $e);
            return redirect()->route('rack.index')->with('error', 'ERROR');
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
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rack = Rack::find($id);
        $customer_id = $rack->customer_id;
        $site_id = $rack->site_id;
        $floor_id = $rack->floor_id;
        $rack_default_id = $rack->rack_default;
        $customer = Customer::pluck('customer_name', 'id')->all();
        $tmp = Customer::find($customer_id);
        $site = $tmp->site->pluck('site_name', 'id');
        $floor = Floor::where('site_id', '=', $site_id)->pluck('floor_name', 'id')->all();
        $power_default = Rack_power_default::pluck('power_default', 'id')->all();
        return view('rack.edit', compact('rack', 'customer_id', 'site_id', 'floor_id', 'rack_default_id', 'customer', 'site', 'floor', 'power_default'));
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
     
        $rules = [
            'site_id' => 'required',
            'floor_id' => 'required',
            'customer_id' => 'required',
            'rack_name' => ['required', 'max:100', Rule::unique('racks')
                ->ignore($id)
                ->where('customer_id', $request->customer_id)],
            'rack_default' => 'required',
            'rack_description' => 'max:200',
        ];

        $customMessages = [
            'site_id.required' => 'The Site field is required.',
            'floor_id.required' => 'The Floor field is required.',
            'floocustomer_idr_id.required' => 'The Customer field is required.'
        ];

        $this->validate($request, $rules, $customMessages);

        $input = $request->all();

        try {
            $site = Site::find($id);
            $site->update($input);

            return redirect()->route('site.index')
                ->with('success', 'Site updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Site', 'Update Site', $e);
            return redirect()->route('site.index')->with('error', 'ERROR');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table("sites")->where('id', $id)->delete();
        return redirect()->route('site.index')
            ->with('success', 'Site deleted successfully');
    }
}
