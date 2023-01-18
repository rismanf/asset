<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Room;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:room-list|room-create|room-edit|room-delete', ['only' => ['index']]);
        $this->middleware('permission:room-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:room-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:room-delete', ['only' => ['destroy']]);
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
            $data = Room::with('floor');
            if ($user->hasRole(['superadmin', 'admin'])) {
                $data->withTrashed();
            }
            return DataTables($data)
                ->addColumn('site', function ($row) {

                    $btn = '<small class="badge badge-success">' . $row->floor->site->site_name . '</small ><br>';
                    $btn .= '<small class="badge badge-success">' . $row->floor->floor_name . '</small ><br>';

                    return $btn;
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';
                    if ($user->hasRole(['superadmin', 'admin'])) {
                        $btn = '';
                        if (empty($row->deleted_at)) {
                            $btn .= '<a href="' . route('room.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('room-edit')) {
                            $btn .= '<a href="' . route('room.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                        }
                        if ($user->can('room-delete')) {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        }
                    }
                    return $btn;
                })
                ->rawColumns(['action', 'site'])
                ->make(true);
        }

        return view('room.index');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Site::pluck('site_name', 'id')->all();
        return view('room.create', compact('site'));
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
            'site_id' => 'required',
            'floor_id' => 'required',
            'room_name' => ['required', 'max:255', Rule::unique('rooms')
                ->where('floor_id', $request->floor_id)],
        ]);

        $input = $request->all();

        try {
            Room::create($input);

            return redirect()->route('room.index')
                ->with('success', 'Room created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Room', 'Crated New Room', $e);
            return redirect()->route('room.index')->with('error', 'ERROR');
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
        $room = room::find($id);
        $site = Site::pluck('site_name', 'id')->all();
        $floor = Floor::where('id','=',$room->floor_id)->pluck('floor_name', 'id')->all();
        return view('room.edit', compact('room', 'site', 'floor'));
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
            'site_id' => 'required',
            'floor_id' => 'required',
            'room_name' => ['required', 'max:255', Rule::unique('rooms')
                ->ignore($id)
                ->where('floor_id', $request->floor_id)],
        ]);

        $input = $request->all();

        try {
            $room = Room::find($id);
            $room->update($input);

            return redirect()->route('room.index')
                ->with('success', 'Room updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Room', 'Update Room', $e);
            return redirect()->route('room.index')->with('error', 'ERROR');
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
            $Room = Room::find($id);
            $Room->deleted_by_id = Auth::id();
            $Room->save();
            $Room->delete();
            return response()->json(['status' => 'success', 'msg' => 'Room deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Room Cannot deleted']);
        }
    }

    public function restore(Request $request)
    {
        $Room = Room::onlyTrashed()->findOrFail($request->id);
        $Room->restore();
        return response()->json(['status' => 'success', 'msg' => 'Room Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $Room = Room::onlyTrashed()->findOrFail($request->id);
        $Room->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Room Permanently Delete successfully']);
    }
}
