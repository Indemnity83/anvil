<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property string name
 * @property string port
 * @property mixed directory
 * @property string basePath
 * @property string webRoot
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
     * Returns the sites base path to the sites.
     *
     * @return string
     */
    public function getBasePathAttribute()
    {
        $storagePath = Storage::disk('sites')
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix();

        return $storagePath.$this->name;
    }

    /**
     * Returns the Nginx web root path.
     *
     * @return string
     */
    public function getWebRootAttribute()
    {
        return $this->basePath.$this->directory;
    }

    /**
     * Update the sites status.
     *
     * @param $status
     */
    public function updateStatus($status)
    {
        Status::validate($status);

        $this->status = $status;
        $this->save();

        // TODO broadcast when a sites status is updated
    }
}
