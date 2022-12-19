<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Floor;
use App\Models\Log_error;
use App\Models\Rack;
use App\Models\Rack_power_default;
use App\Models\Site;
use App\Models\Web_solution_detail;
use App\Models\Web_solution_list;
use DB;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class WebsolutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('permission:rack-list|rack-create|rack-edit|rack-delete', ['only' => ['index']]);
    //     $this->middleware('permission:rack-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:rack-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:rack-delete', ['only' => ['destroy']]);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            $solution = Web_solution_detail::paginate(10);
            return view('web.solution.index', compact('solution'));
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
        $solution = Web_solution_list::pluck('solution_name', 'id')->all();
        return view('web.solution.create', compact('solution'));
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
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:10240',
            'text' => 'required',
        ]);


        $path = $request->file('image')->store('public/images');


        try {
            $save = new Web_solution_detail;

            $save->title = $request->title;
            $save->img = $path;
            $save->link = 'as';
            $save->text = $request->text;
            $save->publish_date = date('Y-m-d');

            $save->save();

            return redirect()->route('websolution.index')
                ->with('success', 'WEB Solution successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'WEB', 'Crated Solution', $e);
            return redirect()->route('websolution.index')->with('error', 'ERROR');
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
        $solution = Web_solution_detail::find($id);
        return view('web.solution.edit', compact('solution'));
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
            'title' => 'required',
            'text' => 'required',
        ]);

        $input = $request->all();

        try {
            $solution = Web_solution_detail::find($id);
            $solution->update($input);

            return redirect()->route('websolution.index')
                ->with('success', 'Solution updated successfully');
        } catch (\Exception $e) {
            Log_error::record(Auth::user(), 'WEB', 'Update Solution', $e);
            return redirect()->route('websolution.index')->with('error', 'ERROR');
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
        DB::table("sites")->where('id', $id)->delete();
        return redirect()->route('site.index')
            ->with('success', 'Site deleted successfully');
    }
}
