<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log_error;
use App\Models\Log_rack;
use App\Models\Rack;
use App\Models\Rack_power;
use DataTables;
use Illuminate\Support\Facades\Auth;

class CheckpowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        // $this->middleware('permission:rack-list|rack-create|rack-edit|rack-delete', ['only' => ['index']]);
        // $this->middleware('permission:rack-create', ['only' => ['create', 'store']]);
        // $this->middleware('permission:rack-edit', ['only' => ['edit', 'update']]);
        // $this->middleware('permission:rack-delete', ['only' => ['destroy']]);
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
                $data = Rack_power::where('active', '=', true)
                    ->with('rack', 'status')
                    ->orderby('id', 'desc');
                return DataTables::of($data)
                    ->addColumn('rackname', function ($row) {
                        $btn = $row->rack->rack_name;
                        return $btn;
                    })
                    ->addColumn('customer', function ($row) {
                        $btn = $row->rack->customer->customer_name;
                        return $btn;
                    })
                    ->addColumn('site', function ($row) {
                        $site = $row->rack->site->site_name;
                        $floor = $row->rack->floor->floor_name;
                        return $site . '<br> -' . $floor;
                    })
                    ->addColumn('status', function ($row) {
                        $btn = $row->status->status_name;
                        return $btn;
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('checkpower.edit', $row->id) . '" class="edit btn btn-primary">Check Power</a> ';
                        return $btn;
                    })
                    ->rawColumns(['rackname', 'customer', 'site', 'status', 'action'])
                    ->make(true);
            }

            return view('checkpower.index');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rackpower = Rack_power::find($id);
        return view('checkpower.edit', compact('rackpower'));
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
            'rack_before' => 'required',
            'rack_va' => 'required|gte:0',
        ]);

        $input = $request->all();

        if ($request->proses == 'check') {
            $input['status_id'] = 1;
            $rack['flagging'] = 2;
        }
        if ($request->proses == 'reject') {
            $input['status_id'] = 6;
            $rack['flagging'] = 2;
        }
        if ($request->proses == 'approved') {
            $input['status_id'] = 3;
            $input['active'] = false;

            $rack['flagging'] = 1;
            $rack['rack_va'] = $request->rack_va;
            $rack['status_id'] = 9;
            $rack['pic_id'] = Auth::id();
            $rack['approve_date'] = date("Y-m-d H:i:s");
        }

        try {

            $power = Rack_power::find($id);
            $power->update($input);

            $rackupdate = Rack::find($power->rack_id);
            $rackupdate->update($rack);

            Log_rack::record($id, $request->proses . ' power', 'Power rack '.$request->proses.' '.$request->rack_va.' VA successfully');
            return redirect()->route('checkpower.index')
                ->with('success', 'Power updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Checkpower', 'Checkpower', $e);
            return redirect()->route('checkpower.index')->with('error', 'ERROR');
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
        //
    }
}
