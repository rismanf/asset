<?php

namespace App\Jobs;

use App\Imports\AssetImport;
use App\Models\AssetUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadAssetRegister implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $uploadFile;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uploadFile)
    {
        $this->uploadFile = $uploadFile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $fileasset = AssetUpload::where('asset_file', '=', $this->uploadFile);

        $import = (new AssetImport())->fromFile($fileasset->first()->id);
        $import->import($this->uploadFile);

        if ($import->failures()->isNotEmpty()) {
            $input['result'] =  json_encode($import->failures());
            $input['status'] = '3';
        } else {
            $input['result'] =  '-';
            $input['status'] = '2';
        }
        $fileasset->update($input);
    }
}
