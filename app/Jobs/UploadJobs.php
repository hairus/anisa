<?php

namespace App\Jobs;

use App\Events\NamaEvent;
use App\Imports\SiswaImport;
use App\Imports\UploadsImport;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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

        // $this->setProgressMax(1);

        // Excel::import(new SiswaImport($this->user_id), "public/excel/" . $this->file);

        // // unlink('public/excel/'.$this->file);

        // $this->setProgressNow(1);
        // $this->setInput(['user_id => ' . $this->user_id, 'fileName =>' . $this->file]);

        // $this->setOutput(['total' => "1 file", 'user_id' => $this->user_id]);
    }

    public function failed(Exception $exception)
    {
        // event(new NamaEvent($exception->getMessage()));
        // Log::error($exception->getMessage());
    }
}
