<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Log_error;
use App\Models\Site;
use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
            $data = Customer::with('site')->orderby('id', 'desc');
            return DataTables($data)
                ->addColumn('site', function ($row) {
                    $btn = '';
                    foreach ($row->site as $site) {
                        $btn .= '<small class="badge badge-success">' . $site->site_name . '</small ><br>';
                    }
                    return $btn;
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->can('customer-edit')) {
                        $btn .= '<a href="' . route('customer.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                    }
                    if ($user->can('customer-delete')) {
                        $btn .= '<a href="' . route('customer.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
                    }

                    $btn = '<a href="' . route('customer.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                    $btn .= '<a href="' . route('customer.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action', 'site'])
                ->make(true);
        }

        return view('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sites = Site::pluck('site_name', 'id')->all();
        return view('customer.create', compact('sites'));
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

        // return response()->json($respon);

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
