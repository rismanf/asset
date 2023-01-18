<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\AssetCategoryGroup;
use App\Models\Brand;
use App\Models\Log_error;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:brand-list|brand-create|brand-edit|brand-delete', ['only' => ['index']]);
        $this->middleware('permission:brand-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:brand-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:brand-delete', ['only' => ['destroy']]);
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
            $data = Brand::query();
            if($user->hasRole('superadmin','admin')){
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
                    if($user->hasRole('superadmin','admin')){
                        $btn = '';
                        if (empty($row->deleted_at)) {
                            $btn .= '<a href="' . route('brand.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('brand.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
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

        return view('brand.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('brand.create');
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
            'brand_name' => 'required|max:80|unique:brands',
            'brand_code' => 'nullable|max:10|unique:brands',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            Brand::create($input);

            return redirect()->route('brand.index')
                ->with('success', 'Brand created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Brand', 'Created Brand', $e);
            return redirect()->route('brand.index')->with('error', 'ERROR');
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

        $user = Brand::find($id);
        return view('brand.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::find($id);

        return view('brand.edit', compact('brand'));
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
            'brand_name' => 'required|max:80|unique:brands,brand_name,' . $id,
            'brand_code' => 'nullable|max:10|unique:brands,brand_code,' . $id,
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            $AssetCategory = Brand::find($id);
            $AssetCategory->updated_by_id = Auth::id();
            $AssetCategory->save();
            $AssetCategory->update($input);

            return redirect()->route('brand.index')
                ->with('success', 'Brand updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Brand', 'Created Brand', $e);
            return redirect()->route('brand.index')->with('error', 'ERROR');
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
            $Brand=Brand::find($id);
            $Brand->deleted_by_id = Auth::id();
            $Brand->save();
            $Brand->delete();
            return response()->json(['status' => 'success', 'msg' => 'Brand deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Brand Cannot deleted']);
        }

    }

    public function restore(Request $request)
    {
        $Brand = Brand::onlyTrashed()->findOrFail($request->id);
        $Brand->restore();
        return response()->json(['status' => 'success', 'msg' => 'Brand Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Brand = Brand::onlyTrashed()->findOrFail($request->id);
        $Brand->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Brand Permanently Delete successfully']);
    }
}
