<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Floor;
use App\Models\Rack;
use App\Models\Room;
use App\Models\Site;
use Illuminate\Http\Request;

class DropdownController extends Controller
{
    public function sitecustomer(Request $request)
    {
        $customer = Customer::find($request->id);
        $respon=$customer->site->pluck('site_name', 'id');
        return response()->json($respon);
    }

    public function floorcustomer(Request $request)
    {
        $respon = Floor::where("site_id", $request->id)->pluck('floor_name', 'id');
        return response()->json($respon);
    }

    public function floor(Request $request)
    {
        $respon = Floor::where("site_id", $request->id)->pluck('floor_name', 'id');
        return response()->json($respon);
    }

    public function room(Request $request)
    {
        $respon = Room::where("floor_id", $request->id)->pluck('room_name', 'id');
        return response()->json($respon);
    }

    public function rack_customer(Request $request)
    {
        $respon = Rack::where("customer_id", $request->id)->orderby('status_id')->get();
        return response()->json($respon);
    }

    public function rack_customer_check(Request $request)
    {
        $query = Rack::select('slug','rack_name','rack_default','rack_va')
                ->where("customer_id", $request->customer_id)->orderby('status_id');
        $query->where(function ($w) use ($request) {
            foreach ($request->id as $v) {
                $w->orWhere('slug', '=', $v);
            }
        });
        $respon = $query->get();

        return view('movein.ajax_rack', [
            "title" => "Movein",
            "data" => $respon
        ]);

    }
}
