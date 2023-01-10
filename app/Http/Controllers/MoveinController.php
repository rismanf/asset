<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Log_error;
use App\Models\Log_movein;
use App\Models\Movein;
use App\Models\Movein_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class MoveinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            $data = Movein::select('*');
            return DataTables::of($data)
                ->addColumn('status', function ($row) {
                    return '<span class="badge badge-' . $row->status->badge . '">' . $row->status->status_name . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('rack.show', $row->id) . '" class="edit btn btn-primary">Detail</a> ';
                    if ($row->flagging == 1) {
                        $btn .= '<a href="' . route('rack.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('movein.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = Customer::orderby('customer_name')->pluck('customer_name', 'id')->all();

        return view('movein.create', compact('customer'));
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
            'installation_date' => 'required',
            'customer' => 'required',
            'pic_name' => 'required',
            'pic_phone' => 'required|numeric',
            'item' => 'required',
        ]);
        $no = Movein::max('id');

        $input = $request->all();

        try {
            $input['code_movein'] = 'IN-' . date('Y.m.d') . sprintf("%03s", $no + 1);
            $input['customer_id'] = $request->customer;
            $movein = Movein::create($input);
            $moveinId = $movein->id;
            foreach ($request->item as $racklist) {

                foreach ($racklist['data'] as $itemlist) {

                    Movein_detail::create([
                        'rack_id' => $racklist['id'],
                        'rack_va_now' => $racklist['va_now'],
                        'movein_id' => $moveinId,
                        'item_name' => $itemlist['item_name'],
                        'item_va' => $itemlist['ampere'],
                    ]);
                }
            }
            Log_movein::record('movein', 'Crated new request movein');
            return redirect()->route('movein.index')
                ->with('success', 'Move-in request successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'movein', 'Crated movein', $e);
            return redirect()->route('movein.index')->with('error', 'ERROR');
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
        //
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
        //
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
