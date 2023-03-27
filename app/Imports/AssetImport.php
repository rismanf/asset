<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Brand;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AssetImport implements ToModel, WithHeadingRow, SkipsOnError, WithValidation, SkipsOnFailure, WithBatchInserts
{
    use Importable, SkipsErrors, SkipsFailures;

    protected $fileId;
    protected $site;
    protected $floor;
    protected $room;
    protected $brand;
    protected $vendor;

    public function __construct()
    {
        $this->site = Site::select('id', 'site_name')->get();
        $this->floor = Floor::select('id', 'floor_name', 'site_id')->get();
        $this->room = Room::select('id', 'room_name', 'floor_id')->get();
        $this->brand = Brand::select('id', 'brand_name')->get();
        $this->vendor = Vendor::select('id', 'vendor_name')->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function fromFile(string $fileId)
    {
        $this->fileId = $fileId;
        return $this;
    }

    public function model(array $row)
    {
        $site = $this->site->where('site_name', $row['site'])->first();
        if ($site) {
            $floor = $this->floor->where('floor_name', $row['floor'])->where('site_id', $site->id)->first();
            if ($floor) {
                $room = $this->room->where('room_name', $row['room'])->where('floor_id', $floor->id)->first();
            }
        }

        $brand = $this->brand->where('brand_name', $row['brand'])->first();
        $vendor = $this->vendor->where('vendor_name', $row['vendor'])->first();
       
        return new Asset([
            'site_id' => $site->id ?? null,
            'floor_id' => $floor->id ?? null,
            'room_id' => $room->id ?? null,
            'asset_facility' => $row['assetfacility'] ?? null,
            'asset_name' => $row['assetname'],
            'brand_id' => $brand->id ?? null,
            'asset_type' => $row['assettype'] ?? null,
            'asset_class' => $row['assetclass'] ?? null, 
            'serial_number' => $row['serialnumber'] ?? null,
            'description' => $row['description'] ?? null,
            'capacity' => $row['capacity'] ?? null,
            'buy_date' => $this->checkExtension($row['buydate']) ?? null,
            'po_number' => $row['ponumber'] ?? null,
            'po_date' => $this->checkExtension($row['podate']) ?? null,
            'po_number_maintenance' => $row['ponumbermaintenance'] ?? null,
            'po_date_maintenance' => $this->checkExtension($row['podatemaintenance']) ?? null,
            'do_number' => $row['donumber'] ?? null,
            'do_date' => $this->checkExtension($row['dodate']) ?? null,
            'SAP_number' => $row['sapnumber'] ?? null,
            'dep_start_date' => $this->checkExtension($row['depstartdate']) ?? null,
            'dep_end_date' => $this->checkExtension($row['dependdate']) ?? null,
            'vendor_id' => $vendor->id ?? null,
            'dep_end_date' => $this->checkExtension($row['dependdate']) ?? null,
            'old_tag' => $row['oldtag'] ?? null,
            'polis' => $row['polis'] ?? null,
            'uspace' => $row['uspace'] ?? null,
            'condition' => $row['condition'] ?? null,
            'tahun_pembuatan' => $row['tahunpembuatan'] ?? null,
            'tahun_instalasi' => $row['tahuninstalasi'] ?? null,
            'tahun_operasi' => $row['tahunoperasi'] ?? null,
            'asset_file_id' => $this->fileId,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.assetname' => ['unique:assets,asset_name'],
            '*.site' => ['required'],
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    private function  checkExtension($dateTime)
    {
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateTime);
    }
}
