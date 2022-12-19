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
    // public function index(Request $request)
    // {
    //     if (Auth::check()) {
    //         if ($request->ajax()) {        
    //             $model = Floor::with('site');
    //             return DataTables::of($model)
    //                 ->addColumn('action', function ($row) {
    //                     $btn = '<a href="' . route('floor.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
    //                     $btn .= '<a href="' . route('floor.delete', $row->id) . '"  class="delete btn btn-danger">Delete</a>';
    //                     return $btn;
    //                 })
    //                 ->addColumn('site', function ($row) {
    //                     $btn = $row->site->site_name;
    //                     return $btn;
    //                 })
    //                 ->rawColumns(['action', 'site'])
    //                 ->make(true);
    //         }

    //         return view('floors.index');
    //     }
    //     return redirect()->route('logout');
    // }
    public function index(Request $request)
    {
        if (Auth::check()) {
           
            $floor = Floor::with('site')->paginate(10);
            return view('floors.index2',compact('floor'));
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
        DB::table("Floors")->where('id', $id)->delete();
        return redirect()->route('floor.index')
            ->with('success', 'Floor deleted successfully');
    }
}