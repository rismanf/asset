<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BisnisUnit;
use App\Models\Log_error;
use Illuminate\Support\Facades\Auth;

class BisnisUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:bisnisunit-list|bisnisunit-create|bisnisunit-edit|bisnisunit-delete', ['only' => ['index']]);
        $this->middleware('permission:bisnisunit-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:bisnisunit-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:bisnisunit-delete', ['only' => ['destroy']]);
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
            $data = BisnisUnit::query();
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
                            $btn .= '<a href="' . route('bisnisunit.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                        } else {
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-success restorebtn">Restore</a> ';
                            $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="btn btn-danger forcedeletebtn">Force Delete</a>';
                        }
                    } else {
                        if ($user->can('category-edit')) {
                            $btn .= '<a href="' . route('bisnisunit.edit', $row->id) . '" class="edit btn btn-primary">Edit</a> ';
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

        return view('bisnisunit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bisnisunit.create');
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
            'bisnis_unit_name' => 'required|max:80|unique:bisnis_units',
            'bisnis_unit_code' => 'nullable|max:10|unique:bisnis_units',
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            BisnisUnit::create($input);

            return redirect()->route('bisnisunit.index')
                ->with('success', 'Bisnis Unit created successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Bisnis Unit', 'Created Bisnis Unit', $e);
            return redirect()->route('bisnisunit.index')->with('error', 'ERROR');
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
        $BisnisUnit = BisnisUnit::find($id);
        return view('bisnisunit.show', compact('BisnisUnit'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bisnisunit = BisnisUnit::find($id);

        return view('bisnisunit.edit', compact('bisnisunit'));
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
            'bisnis_unit_name' => 'required|max:80|unique:bisnis_units,bisnis_unit_name,' . $id,
            'bisnis_unit_code' => 'nullable|max:10|unique:bisnis_units,bisnis_unit_code,' . $id,
            'description' => 'nullable|max:250'
        ]);

        $input = $request->all();

        try {
            $bisnisunit = BisnisUnit::find($id);
            $bisnisunit->updated_by_id = Auth::id();
            $bisnisunit->save();
            $bisnisunit->update($input);

            return redirect()->route('bisnisunit.index')
                ->with('success', 'Bisnis Unit updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'Bisnis Unit', 'Created Bisnis Unit', $e);
            return redirect()->route('bisnisunit.index')->with('error', 'ERROR');
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
            $BisnisUnit=BisnisUnit::find($id);
            $BisnisUnit->deleted_by_id = Auth::id();
            $BisnisUnit->save();
            $BisnisUnit->delete();
            return response()->json(['status' => 'success', 'msg' => 'Bisnis Unit deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'Bisnis Unit Cannot deleted']);
        }

    }

    public function restore(Request $request)
    {
        $BisnisUnit = BisnisUnit::onlyTrashed()->findOrFail($request->id);
        $BisnisUnit->restore();
        return response()->json(['status' => 'success', 'msg' => 'Bisnis Unit Restore successfully']);
    }

    public function forcedelete(Request $request)
    {
        $BisnisUnit = BisnisUnit::onlyTrashed()->findOrFail($request->id);
        $BisnisUnit->forceDelete();
        return response()->json(['status' => 'success', 'msg' => 'Bisnis Unit Permanently Delete successfully']);
    }
}
