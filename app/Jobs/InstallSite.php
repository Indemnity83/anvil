<?php

namespace App\Jobs;

use App\Site;
use App\Status;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstallSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Site
     */
    private $site;

    /**
     * Create a new job instance.
     *
     * @param Site $site
     */
    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $index = view('nginx.index', ['site' => $this->site])->render();
            Storage::disk('sites')->put($this->site->name.$this->site->directory.'\index.php', $index);
        } catch (\Throwable $e) {
            // TODO Handle errors when writing nginx config fails
        }

        Log::info('Generating Config: '.$this->site->name);
        try {
            $config = view('nginx.config', ['site' => $this->site])->render();
            Storage::disk('nginx')->put($this->site->name.'.conf', $config);
        } catch (\Throwable $e) {
            // TODO Handle errors when writing nginx config fails
        }

        Log::info('Testing Nginx Configuration');
        exec('nginx -t', $output, $exitCode);
        if ($exitCode !== 0) {
            // TODO Revert changes if nginx test fails
        }

        Log::info('Reloading Nginx');
        exec('nginx -s reload', $output, $exitCode);
        if ($exitCode !== 0) {
            // TODO Rrevert changes if nginx reload fails
        }

        $this->site->updateStatus(Status::Installed);
    }
}
