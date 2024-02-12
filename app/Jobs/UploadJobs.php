<?php

namespace App\Jobs;

use App\Imports\UploadsImport;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Imtigger\LaravelJobStatus\Trackable;
use Maatwebsite\Excel\Facades\Excel;

class UploadJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable, Trackable;

    private  $user_id;
    private  $file;

    public function __construct($user_id, $file)
    {
        $this->prepareStatus();
        $this->user_id = $user_id;
        $this->file = $file;
        // $this->prepareStatus(['key' => $params[0]]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $this->setProgressMax(1);

        $gg = Excel::import(new UploadsImport($this->user_id), "public/excel/".$this->file);

        unlink('public/excel/'.$this->file);

        $this->setProgressNow(1);

        $this->setOutput(['total' => $gg, 'other' => 'parameter']);
    }
}
