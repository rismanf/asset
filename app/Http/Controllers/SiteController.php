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

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:site-list|site-create|site-edit|site-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:site-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:site-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:site-delete', ['only' => ['destroy']]);
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
                $user = auth()->user();
                $data = Site::query();
                if ($user->hasRole(['superadmin', 'admin'])) {
                    $data->withTrashed();
                }
                return DataTables($data)
                    ->addColumn('action', function ($row) use ($user) {
                        $btn = '';
                        if ($user->hasRole(['superadmin', 'admin'])) {
                            $btn = '';
                            if (empty($row->deleted_at)) {
                                $btn .= '<a href="' . route('site.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                            } else {
                                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                            }
                        } else {
                            if ($user->can('site-edit')) {
                                $btn .= '<a href="' . route('site.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            }
                            if ($user->can('site-delete')) {
                                $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                            }
                        }
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('sites.index');
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
        return view('sites.create');
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
            'site_name' => 'required|min:5|max:100|unique:sites',
            'address' => 'max:200',
            'phone' => 'max:20',
        ]);

        $input = $request->all();

        try {
            Site::create($input);

            return redirect()->route('site.index')
                ->with('success', 'Site created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Site', 'Crated New Site', $e);
            return redirect()->route('site.index')->with('error', 'ERROR');
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
        $site = Site::find($id);
        return view('sites.edit', compact('site'));
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
            'site_name' => 'required|min:5|max:100|unique:sites,site_name,' . $id,
            'address' => 'max:200',
            'phone' => 'max:20',
        ]);

        $input = $request->all();

        try {
            $site = Site::find($id);
            $site->update($input);

            return redirect()->route('site.index')
                ->with('success', 'Site updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Site', 'Update Site', $e);
            return redirect()->route('site.index')->with('error', 'ERROR');
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
        if (Floor::where('site_id', '=', $id)->first()) {
            return response()->json(['status' => 'error', 'msg' => 'Site Cannot deleted, Please Delete Floor frist']);
        }
        try {
            $Site = Site::find($id);
            $Site->deleted_by_id = Auth::id();
            $Site->save();
            $Site->delete();
            return response()->json(['status' => 'success', 'msg' => 'Site deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Site Cannot deleted, Please Delete Floor frist']);
        }
    }

    public function restore(Request $request)
    {
        $Site = Site::onlyTrashed()->findOrFail($request->id);
        $Site->restore();
        return response()->json(['status' => 'success', 'msg' => 'Site restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Site = Site::onlyTrashed()->findOrFail($request->id);
        $Site->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Site Permanently Delete successfully']);
    }
}
