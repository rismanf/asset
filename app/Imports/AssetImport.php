<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Site;
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

    public function __construct()
    {
        $this->site = Site::select('id', 'site_name')->get();
        $this->floor = Floor::select('id', 'floor_name', 'site_id')->get();
        $this->room = Room::select('id', 'room_name', 'floor_id')->get();
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

        return new Asset([
            'asset_name' => $row['assetname'],
            'asset_file_id' => $this->fileId,
            'site_id' => $site->id ?? null,
            'floor_id' => $floor->id ?? null,
            'room_id' => $room->id ?? null,
            'po_date' => $this->checkExtension($row['podate']),
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
