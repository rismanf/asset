<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Site;
use DB;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:floor-list|floor-create|floor-edit|floor-delete', ['only' => ['index']]);
        $this->middleware('permission:floor-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:floor-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:floor-delete', ['only' => ['destroy']]);
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
            $data = Floor::with('site');
            if ($user->hasRole(['superadmin', 'admin'])) {
                $data->withTrashed();
            }
            return DataTables($data)
                ->addColumn('site', function ($row) {
                    $btn = '<small class="badge badge-success">' . $row->site->site_name . '</small ><br>';
                    return $btn;
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->hasRole(['superadmin', 'admin'])) {
                        $btn = '';
                        if (empty($row->deleted_at)) {
                            $btn .= '<a href="' . route('floor.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('floor-edit')) {
                            $btn .= '<a href="' . route('room.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        }
                        if ($user->can('floor-delete')) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'site'])
                ->make(true);
        }

        return view('floors.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Site::pluck('site_name', 'id')->all();
        return view('floors.create', compact('site'));
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
            'floor_name' => ['required', 'max:255', Rule::unique('floors')
                ->where('site_id', $request->site_id)],
        ]);

        $input = $request->all();

        try {
            Floor::create($input);

            return redirect()->route('floor.index')
                ->with('success', 'Floor created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Floor', 'Crated New Floor', $e);
            return redirect()->route('floor.index')->with('error', 'ERROR');
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
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $floor = Floor::find($id);
        $site = Site::pluck('site_name', 'id')->all();
        $site_id = $floor->site_id;
        return view('floors.edit', compact('floor', 'site', 'site_id'));
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
            'floor_name' => ['required', 'max:255', Rule::unique('floors')
                ->ignore($id)
                ->where('site_id', $request->site_id)],
        ]);

        $input = $request->all();

        try {
            $floor = Floor::find($id);
            $floor->update($input);

            return redirect()->route('floor.index')
                ->with('success', 'Floor updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Floor', 'Update Floor', $e);
            return redirect()->route('floor.index')->with('error', 'ERROR');
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
            $Floor = Floor::find($id);
            $Floor->deleted_by_id = Auth::id();
            $Floor->save();
            $Floor->delete();
            return response()->json(['status' => 'success', 'msg' => 'Floor deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Floor Cannot deleted']);
        }
    }

    public function restore(Request $request)
    {
        $Floor = Floor::onlyTrashed()->findOrFail($request->id);
        $Floor->restore();
        return response()->json(['status' => 'success', 'msg' => 'Floor Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Floor = Floor::onlyTrashed()->findOrFail($request->id);
        $Floor->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Floor Permanently Delete successfully']);
    }
}
