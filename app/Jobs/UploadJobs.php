<?php

namespace App\Jobs;

use App\Imports\UploadsImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class UploadJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    private  $user_id;
    private  $file;

    public function __construct($user_id, $file)
    {
        $this->user_id = $user_id;
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::import(new UploadsImport($this->user_id), "public/excel/".$this->file);
        // unlink($path);
    }
}
