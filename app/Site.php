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
 * @property string disk
 * @property string path
 * @property string web_root
 * @property string repository
 * @property string repository_provider
 * @property string repository_branch
 * @property string repository_status
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
     * Make a .env file if possible and one doesn't exist already
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
}
