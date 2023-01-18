<?php

namespace App\Http\Controllers;

use App\Imports\AssetImport;
use App\Jobs\UploadAssetRegister;
use App\Models\Asset;
use App\Models\AssetUpload;
use Illuminate\Http\Request;

class AssetUploadContorller extends Controller
{
    function __construct()
    {
        $this->middleware('permission:asset-list|asset-create|asset-edit|asset-delete', ['only' => ['index']]);
        $this->middleware('permission:asset-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:asset-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:asset-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();
            $data = AssetUpload::query();

            return DataTables($data)
                ->editColumn('status', function ($row) {
                    switch ($row->status) {
                        case 1:
                            $status = "On-Progres";
                            break;
                        case 2:
                            $status = "done";
                            break;
                        default:
                            $status = "Failure";
                            break;
                    }

                    return $status;
                })
                ->editColumn('updated_at', function ($row) {
                    return [
                        'display' => e($row->updated_at->format('Y F d H:i:s')),
                        'timestamp' => $row->updated_at->timestamp
                    ];
                })
                ->addColumn('action', function ($row) use ($user) {
                    $btn = '';

                    if ($row->status == 3) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-warning showerrorbtn">Show Error</a> ';
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-danger deletebtn">Delete</a>';
                    }
                    if ($row->status == 2) {
                        $btn .= '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" data-original-tilte="delete" class="delete btn btn-primary showbtn">Show Data</a> ';
                    }

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('assetupload.index');
    }

    public function show(Request $request)
    {
        $data = Asset::where('asset_file_id', '=', $request->id)->get();

        return view('assetupload.show', compact('data'));
    }

    public function showerror(Request $request)
    {
        $AssetUpload = AssetUpload::select('result')->find($request->id);
        $data = json_decode($AssetUpload->result, true);
        return view('assetupload.showerror', compact('data'));
    }

    public function delete(Request $request)
    {
        try {
            $AssetUpload = AssetUpload::find($request->id);
            $AssetUpload->delete();
            return response()->json(['status' => 'success', 'msg' => 'AssetUpload deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'msg' => 'AssetUpload Cannot deleted']);
        }
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|mimes:xlsx,xsl'
        ]);

        $file = $request->file('file')->store('imports');
        $fileName = $request->file('file')->getClientOriginalName();
        AssetUpload::create([
            'asset_file' => $file,
            'asset_original_file' => $fileName
        ]);


        UploadAssetRegister::dispatch($file);

        return back()->withStatus('Import in queue, we will send notification after import finished.');
    }

    public function export()
    {
        return true;
    }
}
