<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Brand;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Room;
use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:asset-list|asset-create|asset-edit|asset-delete', ['only' => ['index']]);
        $this->middleware('permission:asset-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset-delete', ['only' => ['destroy']]);
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
            $data = Asset::with('site','floor');
            if ($user->hasRole('superadmin', 'admin')) {
                $data->withTrashed();
            }
            return DataTables($data)
                ->editColumn('updated_at', function ($user) {
                    return [
                        'display' => e($user->updated_at->format('Y F d')),
                        'timestamp' => $user->updated_at->timestamp
                    ];
                })
                ->addColumn('site', function ($row) {

                    $btn = '<small class="badge badge-success">' . $row->site->site_name . '</small ><br>';
                    $btn .= ($row->floor_id) ?'<small class="badge badge-success">' . $row->floor->floor_name . '</small ><br>':'';
                    $btn .= ($row->room_id) ?'<small class="badge badge-success">' . $row->room->room_name . '</small ><br>':'';

                    return $btn;
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->hasRole('superadmin')) {
                        $btn = '';
                        if (empty($row->deleted_at)) {
                            $btn .= '<a href="' . route('asset.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('asset.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        }
                        if ($user->can('category-delete')) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'site'])
                ->make(true);
        }

        return view('asset.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::pluck('brand_name', 'id')->all();
        $category = AssetCategory::pluck('category_name', 'id')->all();
        $vendor = Vendor::pluck('vendor_name', 'id')->all();
        $site = Site::pluck('site_name', 'id')->all();
        return view('asset.create', compact('brand', 'category', 'vendor', 'site'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAssetRequest $request)
    {
        try {
            Asset::create($request->validated());

            return redirect()->route('asset.index')
                ->with('success', 'Asset created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Asset', 'Created Asset', $e);
            return redirect()->route('asset.index')->with('error', 'ERROR');
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
        $Brand = Brand::pluck('brand_name', 'id')->all();
        $AssetCategory = AssetCategory::pluck('category_name', 'id')->all();
        $Vendor = Vendor::pluck('vendor_name', 'id')->all();
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
        $asset = Asset::find($id);
        $brand = Brand::pluck('brand_name', 'id')->all();
        $category = AssetCategory::pluck('category_name', 'id')->all();
        $vendor = Vendor::pluck('vendor_name', 'id')->all();
        $site = Site::pluck('site_name', 'id')->all();
        $floor = Floor::where('site_id','=',$asset->site_id)->pluck('floor_name', 'id')->all();
        $room = Room::where('floor_id','=',$asset->floor_id)->pluck('room_name', 'id')->all();
        return view('asset.edit', compact('asset','brand', 'category', 'vendor', 'site','floor','room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAssetRequest $request, $id)
    {

        try {
            $Asset = Asset::find($id);
            $Asset->updated_by_id = Auth::id();
            $Asset->save();
            $Asset->update($request->validated());

            return redirect()->route('brand.index')
                ->with('success', 'Asset updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Asset', 'Created Asset', $e);
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
            $Asset = Asset::find($id);
            $Asset->deleted_by_id = Auth::id();
            $Asset->save();
            $Asset->delete();
            return response()->json(['status' => 'success', 'msg' => 'Asset deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Asset Cannot deleted']);
        }
    }

    public function restore(Request $request)
    {
        $Asset = Asset::onlyTrashed()->findOrFail($request->id);
        $Asset->restore();
        return response()->json(['status' => 'success', 'msg' => 'Asset Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Asset = Asset::onlyTrashed()->findOrFail($request->id);
        $Asset->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Asset Permanently Delete successfully']);
    }
}
