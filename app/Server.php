<?php

namespace App;

use Jenssegers\Model\Model;

class Server extends Model
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'public_key',
    ];

    /**
     * The public rsa key for the server.
     *
     * @return false|string|void
     */
    public function getPublicKeyAttribute()
    {
        if (! file_exists(storage_path('.ssh/id_rsa.pub'))) {
            return;
        }

        return file_get_contents(storage_path('.ssh/id_rsa.pub'));
    }
}
