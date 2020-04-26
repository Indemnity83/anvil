<?php

namespace App\Jobs;

use App\Events\SiteUpdated;
use App\Site;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Encryption\Encrypter;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RepoInstall implements ShouldQueue
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
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        // Clean out temporary web_root
        $this->site->cleanDirectory();

        // Git clone the repo
        shell_exec("git clone -b {$this->site->repository_branch} {$this->site->repository} {$this->site->path}");

        // Setup environment file with some sensible defaults
        if ($this->site->makeEnvironmentFile()) {
            $this->site->setEnvironmentVariable('APP_KEY', $this->generateKey());
            $this->site->setEnvironmentVariable('APP_ENV', 'production');
            $this->site->setEnvironmentVariable('APP_DEBUG', 'false');
            $this->site->setEnvironmentVariable('DB_CONNECTION', 'sqlite');
            $this->site->setEnvironmentVariable('DB_DATABASE', '');
        }

        // Touch database.sqlite in the standard location
        touch("{$this->site->path}/database/database.sqlite");

        // Setup default deploy script
        $this->site->deploy_script = view('deploy-script.default', ['site' => $this->site])->render();

        // Update site's repository_status to installed
        $this->site->repository_status = 'installed';
        $this->site->save();

        // Broadcast an event
        broadcast(new SiteUpdated($this->site));
    }

    /**
     * Set the application key in the environment file.
     *
     * @param string $cipher
     * @return string
     */
    protected function generateKey($cipher = 'AES-128-CBC')
    {
        return 'base64:'.base64_encode(
            Encrypter::generateKey($cipher)
        );
    }
}
