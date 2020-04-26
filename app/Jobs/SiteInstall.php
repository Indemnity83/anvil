<?php

namespace App\Jobs;

use App\Events\SiteUpdated;
use App\Nginx;
use App\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SiteInstall implements ShouldQueue
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
        $this->site->writeNginxConfig();
        $this->site->writeIndexPage();

        Nginx::reload();

        $this->site->status = 'installed';
        $this->site->save();

        broadcast(new SiteUpdated($this->site));
    }
}
