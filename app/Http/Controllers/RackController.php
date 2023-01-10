<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Log_rack;
use App\Models\Rack;
use App\Models\Rack_power;
use App\Models\Rack_power_default;
use App\Models\Site;
use DB;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RackController extends Controller
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
        if ($request->ajax()) {
            $data = Rack::with('customer', 'site', 'floor', 'rackpowerdefault', 'status')->orderby('id', 'desc');
            return DataTables::of($data)
                ->addColumn('customer', function ($row) {
                    $btn = $row->customer->customer_name;
                    return $btn;
                })
                ->addColumn('site', function ($row) {
                    $site = $row->site->site_name;
                    $floor = $row->floor->floor_name;
                    return $site . '<br> -' . $floor;
                })
                ->addColumn('rackpowerdefault', function ($row) {
                    $btn = $row->rackpowerdefault->power_default;
                    return $btn . ' VA';
                })
                ->addColumn('rackavailable', function ($row) {
                    $btn = $row->rackpowerdefault->power_default;
                    $va = $row->rack_va;
                    $av = $btn - $va;
                    return number_format($av, 2) . ' VA';
                })
                ->addColumn('status', function ($row) {
                    $btn = '';
                    if ($row->flagging == 2) {
                        $btn .= 'on process <br>';
                    }
                    $btn .= $row->status->status_name;
                    return $btn;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('rack.show', $row->id) . '" class="edit btn btn-primary">Detail</a> ';
                    if ($row->flagging == 1) {
                        $btn .= '<a href="' . route('rack.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['customer', 'site', 'rackpowerdefault', 'rackavailable', 'status', 'action'])
                ->make(true);
        }

        return view('rack.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::orderby('customer_name')->pluck('customer_name', 'id')->all();
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
            'rack_power_defaults_id' => 'required',
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
            $input['status_id'] = 8;
            $rack = Rack::create($input);
            $rackId = $rack->id;
            Rack_power::create([
                'rack_id' => $rackId,
                'status_id' => 8,
            ]);
            Log_rack::record($rackId, 'Crated New Rack', 'Rack created successfully');
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
        $rack = Rack::find($id);
        $log_rack = Log_rack::where('rack_id', $id)->get();

        return view('rack.show', compact('rack', 'log_rack'));
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
            $site = Rack::find($id);
            $site->update($input);

            Log_rack::record($id, 'Updated Rack', 'Rack updated successfully');

            return redirect()->route('rack.index')
                ->with('success', 'Site updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Rack', 'Update rack', $e);
            return redirect()->route('rack.index')->with('error', 'ERROR');
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
        // $count=Rack_power::where('rack_id','=',$id)->where('status_id','=',8)->get()->count();
        // dd($count);
        // DB::table("sites")->where('id', $id)->delete();
        return redirect()->route('rack.index')
            ->with('success', 'Site deleted successfully');
    }
}
