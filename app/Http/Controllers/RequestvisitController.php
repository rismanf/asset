<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Log_error;
use App\Models\Site;
use App\Models\Visit_request;
use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class RequestvisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:request-list|request-create|request-edit|request-delete', ['only' => ['index']]);
         $this->middleware('permission:request-create', ['only' => ['create','store']]);
         $this->middleware('permission:request-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:request-delete', ['only' => ['destroy']]);
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
                $data = Visit_request::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="' . route('reuqest.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        $btn .= '<a href="' . route('reuqest.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('request.index');
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
        $customer = Customer::find(1);
        $sites = Site::pluck('site_name', 'id')->all();
        return view('request.create', compact('customer','sites'));
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
            'customer_name' => 'required',
            'sites' => 'required'
        ]);

        $input = $request->all();

        try {
            $customer = Customer::create($input);
            $customer->site()->attach($request->input('sites'));

            return redirect()->route('customer.index')
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Customer', 'Created Customer', $e);
            return redirect()->route('customer.index')->with('error', 'ERROR');
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
        $user = Customer::find($id);
        return view('customer.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = Customer::find($id);
        $sites = Site::pluck('site_name', 'id')->all();
        $customersite = DB::table("customer_site")->where("customer_site.customer_id", $id)
            ->pluck('customer_site.site_id')
            ->all();
            // dd($customersite);
        return view('customer.edit', compact('customer', 'sites', 'customersite'));
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
            'customer_name' => 'required',
            'sites' => 'required'
        ]);
        
        $input = $request->all();
      
        try {
            $customer = Customer::find($id);
            $customer->update($input);

            DB::table('customer_site')->where('customer_id', $id)->delete();

            $customer->site()->attach($request->input('sites'));

            return redirect()->route('customer.index')
                ->with('success', 'Customer updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Customer', 'Created Customer', $e);
            return redirect()->route('customer.index')->with('error', 'ERROR');
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
        if ($id != 1) {
            Customer::find($id)->delete();
            return redirect()->route('users.index')
                ->with('success', ' User deleted successfully');
        } else {
            return redirect()->route('users.index')
                ->with('error', ' Cannot Delete User');
        }
    }
}
