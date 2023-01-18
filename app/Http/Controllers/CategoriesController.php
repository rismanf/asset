<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AssetCategory;
use App\Models\AssetCategoryGroup;
use App\Models\Log_error;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
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
            $data = AssetCategory::with('group');
            if($user->hasRole('superadmin')){
                $data->withTrashed();
            }
            return DataTables($data)
                ->addColumn('group', function ($row) {
                    $btn = $row->group->category_group_name;
                    return $btn;
                })
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
                            $btn .= '<a href="' . route('categories.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('categories.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
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

        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category_group = AssetCategoryGroup::pluck('category_group_name', 'id')->all();
        return view('categories.create', compact('category_group'));
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
            'category_name' => 'required|max:50|unique:asset_categories',
            'category_code' => 'nullable|max:10|unique:asset_categories',
            'category_group_id' => 'required',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            AssetCategory::create($input);

            return redirect()->route('categories.index')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Category', 'Created Category', $e);
            return redirect()->route('categories.index')->with('error', 'ERROR');
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

        $user = AssetCategory::find($id);
        return view('categories.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = AssetCategory::find($id);
        $group = AssetCategoryGroup::pluck('category_group_name', 'id')->all();
        $category_group = $category->category_group_id;

        return view('categories.edit', compact('category', 'group', 'category_group'));
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
            'category_name' => 'required|max:50|unique:asset_categories,category_name,' . $id,
            'category_code' => 'nullable|max:10|unique:asset_categories,category_code,' . $id,
            'category_group_id' => 'required',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            $AssetCategory = AssetCategory::find($id);
            $AssetCategory->updated_by_id = Auth::id();
            $AssetCategory->save();
            $AssetCategory->update($input);

            return redirect()->route('categories.index')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Category', 'Created Category', $e);
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
        try {
            $AssetCategory=AssetCategory::find($id);
            $AssetCategory->deleted_by_id = Auth::id();
            $AssetCategory->save();
            $AssetCategory->delete();
            return response()->json(['status' => 'success', 'msg' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Category Cannot deleted']);
        }

    }

    public function restore(Request $request)
    {
        $CategoryGroup = AssetCategory::onlyTrashed()->findOrFail($request->id);
        $CategoryGroup->restore();
        return response()->json(['status' => 'success', 'msg' => 'Category Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $CategoryGroup = AssetCategory::onlyTrashed()->findOrFail($request->id);
        $CategoryGroup->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Category Permanently Delete successfully']);
    }
}
