<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class AssetImport implements ToModel, WithHeadingRow,SkipsOnError,WithValidation,SkipsOnFailure,WithBatchInserts
{
    use Importable,SkipsErrors,SkipsFailures;

    protected $fileId;

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
        return new Asset([
            'asset_name' => $row['assetname'],
            'asset_file_id' => $this->fileId,
        ]);
    }
    public function rules(): array
    {
        return [
            '*.assetname' => [ 'unique:assets,asset_name']
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
}
