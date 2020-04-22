<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string path
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

    public function getPathAttribute()
    {
        $basePath = storage_path('sites');

        return $basePath.DIRECTORY_SEPARATOR.$this->name;
    }

    public function setStatus($status)
    {
        Status::validate($status);

        $this->status = $status;
        $this->save();
    }
}
