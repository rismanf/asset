<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\AssetCategoryGroup;
use App\Models\Log_error;
use Illuminate\Support\Facades\Auth;

class CategoriesgroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
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
            $data = AssetCategoryGroup::query();
            if ($user->hasRole('superadmin')) {
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
                            $btn .= '<a href="' . route('categoriesgroup.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('categoriesgroup.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        }
                        if ($user->can('category-delete')) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('categoriesgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categoriesgroup.create');
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
            'category_group_name' => 'required|max:50|unique:asset_category_groups',
            'category_group_code' => 'nullable|max:10|unique:asset_category_groups',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            AssetCategoryGroup::create($input);

            return redirect()->route('categoriesgroup.index')
                ->with('success', 'Category Group created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'categoriesgroup', 'Created Category Group', $e);
            return redirect()->route('categoriesgroup.index')->with('error', 'ERROR');
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

        $user = AssetCategoryGroup::find($id);
        return view('categoriesgroup.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $CategoryGroup = AssetCategoryGroup::find($id);
        return view('categoriesgroup.edit', compact('CategoryGroup'));
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
            'category_group_name' => 'required|max:50|unique:asset_category_groups,category_group_name,' . $id,
            'category_group_code' => 'nullable|max:10|unique:asset_category_groups,category_group_code,' . $id,
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            $AssetCategoryGroup = AssetCategoryGroup::find($id);
            $AssetCategoryGroup->updated_by_id = Auth::id();
            $AssetCategoryGroup->save();
            $AssetCategoryGroup->update($input);

            return redirect()->route('categoriesgroup.index')
                ->with('success', 'Category Group updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Customer', 'Created Category Group', $e);
            return redirect()->route('categoriesgroup.index')->with('error', 'ERROR');
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
        if (AssetCategory::where('category_group_id', '=', $id)->first()) {
            return response()->json(['status' => 'error', 'msg' => 'Category Group Cannot deleted, Please Delete Category list']);
        }
        try {
            $AssetCategoryGroup = AssetCategoryGroup::find($id);
            $AssetCategoryGroup->deleted_by_id = Auth::id();
            $AssetCategoryGroup->save();
            $AssetCategoryGroup->delete();
            return response()->json(['status' => 'success', 'msg' => 'Category Group deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Category Group Cannot deleted, Please Delete Category list']);
        }
    }

    public function restore(Request $request)
    {
        $CategoryGroup = AssetCategoryGroup::onlyTrashed()->findOrFail($request->id);
        $CategoryGroup->restore();
        return response()->json(['status' => 'success', 'msg' => 'Category Group restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $CategoryGroup = AssetCategoryGroup::onlyTrashed()->findOrFail($request->id);
        $CategoryGroup->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Category Group Permanently Delete successfully']);
    }
}
