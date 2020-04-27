<?php

namespace App;

use App\Jobs\RepoInstall;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string name
 * @property string port
 * @property mixed directory
 * @property string status
 * @property Server server
 * @property string disk
 * @property string path
 * @property string web_root
 * @property string repository
 * @property string repository_provider
 * @property string repository_branch
 * @property string repository_status
 * @property string deploy_script
 * @property string deploy_status
 * @property string deploy_script_path
 * @property string deploy_log_path
 */
class Site extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'port',
        'directory',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'server',
    ];

    /**
     * Get the associated server.
     *
     * @return Server
     */
    public function getServerAttribute()
    {
        return new Server;
    }

    /**
     * Get the path to the site storage folder.
     *
     * @return string
     */
    public function getPathAttribute()
    {
        return Storage::disk($this->disk)
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();
    }

    /**
     * Get the path to the site web root folder.
     *
     * @return string
     */
    public function getWebRootAttribute()
    {
        return $this->path.DIRECTORY_SEPARATOR.$this->directory;
    }

    /**
     * Get the name of the disk for the site directory.
     *
     * @return string
     */
    public function getDiskAttribute()
    {
        return "site-{$this->id}";
    }

    /**
     * Get the path the to deploy script.
     *
     * @return string
     */
    public function getDeployScriptPathAttribute()
    {
        return storage_path("logs/deploy-{$this->name}.sh");
    }

    /**
     * Get the path the to deploy log.
     *
     * @return string
     */
    public function getDeployLogPathAttribute()
    {
        return storage_path("logs/deploy-{$this->name}.log");
    }

    /**
     * Get the path to the site folder.
     *
     * @param  string  $path
     * @return string
     */
    public function storagePath($path = '')
    {
        $site = 'sites'.DIRECTORY_SEPARATOR.$this->name;

        return storage_path($path ? $site.DIRECTORY_SEPARATOR.$path : $site);
    }

    /**
     * Get the path to the web root folder.
     *
     * @param  string  $path
     * @return string
     */
    public function webRoot($path = '')
    {
        return $this->storagePath($path ? $this->directory.DIRECTORY_SEPARATOR.$path : $this->directory);
    }

    /**
     * Write a index.php file for an empty site.
     *
     * @return bool
     * @throws \Throwable
     */
    public function writeIndexPage()
    {
        return Storage::disk($this->disk)->put(
            "{$this->directory}/index.php",
            view('nginx.index', ['site' => $this])->render()
        );
    }

    /**
     * Write the Nginx config file for this site.
     *
     * @return bool
     * @throws \Throwable
     */
    public function writeNginxConfig()
    {
        return Storage::disk('nginx')->put(
            "{$this->name}.conf",
            view('nginx.config', ['site' => $this])->render()
        );
    }

    /**
     * Empty the site directory of all files and folders.
     */
    public function cleanDirectory()
    {
        Storage::disk($this->disk)->delete(Storage::disk($this->disk)->allFiles());

        foreach (Storage::disk($this->disk)->allDirectories() as $directory) {
            Storage::disk($this->disk)->deleteDirectory($directory);
        }
    }

    /**
     * Make a .env file if possible and one doesn't exist already.
     * @return bool
     */
    public function makeEnvironmentFile()
    {
        if (Storage::disk($this->disk)->exists('.env')) {
            return false;
        }

        return Storage::disk($this->disk)->copy('.env.example', '.env');
    }

    /**
     * Write the provided key and value to the sites environment file.
     *
     * @param $key
     * @param $value
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function setEnvironmentVariable($key, $value)
    {
        Storage::disk($this->disk)->put('.env', preg_replace(
            '/'.preg_quote($key).'=.*/',
            $key.'='.$value,
            Storage::disk($this->disk)->get('.env')
        ));
    }

    /**
     * Run the deploy script.
     */
    public function deploy()
    {
        file_put_contents($this->deploy_script_path, $this->deploy_script);
        chmod($this->deploy_script_path, 0755);
        shell_exec("cd {$this->path} && date > {$this->deploy_log_path} && /bin/sh -c {$this->deploy_script_path} >> {$this->deploy_log_path} 2>&1");
    }
}
