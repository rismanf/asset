<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\Rack;
use App\Models\Room;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function floor(Request $request)
    {
        $respon = Floor::where("building_id", $request->id)->pluck('floor_name', 'id');
        return response()->json($respon);
    }

    public function room(Request $request)
    {
        $respon = Room::where("floor_id", $request->id)->pluck('room_name', 'id');
        return response()->json($respon);
    }

    public function rack_customer(Request $request)
    {
        $respon= Rack::with('rackpowerdefault','status')
        ->where("customer_id", $request->id)
        ->where("site_id", $request->siteid)
        ->orderby('flagging')->get();
     
        return response()->json($respon);
    }

    public function rack_customer_check(Request $request)
    {
        $query = Rack::with('rackpowerdefault')
                ->where("customer_id", $request->customer_id)->orderby('status_id');
        $query->where(function ($w) use ($request) {
            foreach ($request->id as $v) {
                $w->orWhere('id', '=', $v);
            }
        });
        $data = $query->get();

        return view('movein.ajax_rack', compact('data'));

    }
}
