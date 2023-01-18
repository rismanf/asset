<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log_error;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:vendor-list|vendor-create|vendor-edit|vendor-delete', ['only' => ['index']]);
        $this->middleware('permission:vendor-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:vendor-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:vendor-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();
            $data = Vendor::query();
            if($user->hasRole('superadmin')){
                $data->withTrashed();
            }
            
            return DataTables($data)
                ->editColumn('updated_at', function ($user) {
                    return [
                       'display' => e($user->updated_at->format('Y F d')),
                       'timestamp' => $user->updated_at->timestamp
                    ];
                 })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->hasRole('superadmin')) {
                        $btn = '';
                        if (empty($row->deleted_at)) {
                            $btn .= '<a href="' . route('vendor.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('vendor.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        }
                        if ($user->can('category-delete')) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'group'])
                ->make(true);
        }

        return view('vendor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendor.create');
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
            'vendor_name' => 'required|max:80|unique:vendors',
            'vendor_code' => 'nullable|max:10|unique:vendors',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            Vendor::create($input);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Vendor', 'Created Vendor', $e);
            return redirect()->route('vendor.index')->with('error', 'ERROR');
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

        $user = Vendor::find($id);
        return view('vendor.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Vendor::find($id);

        return view('vendor.edit', compact('vendor'));
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
            'vendor_name' => 'required|max:80|unique:vendors,vendor_name,' . $id,
            'vendor_code' => 'nullable|max:10|unique:vendors,vendor_code,' . $id,
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            $AssetCategory = Vendor::find($id);
            $AssetCategory->updated_by_id = Auth::id();
            $AssetCategory->save();
            $AssetCategory->update($input);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Vendor', 'Created Vendor', $e);
            return redirect()->route('vendor.index')->with('error', 'ERROR');
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
        try {
            $Vendor=Vendor::find($id);
            $Vendor->deleted_by_id = Auth::id();
            $Vendor->save();
            $Vendor->delete();
            return response()->json(['status' => 'success', 'msg' => 'Vendor deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Vendor Cannot deleted']);
        }

    }

    public function restore(Request $request)
    {
        $Vendor = Vendor::onlyTrashed()->findOrFail($request->id);
        $Vendor->restore();
        return response()->json(['status' => 'success', 'msg' => 'Vendor Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Vendor = Vendor::onlyTrashed()->findOrFail($request->id);
        $Vendor->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Vendor Permanently Delete successfully']);
    }
}
