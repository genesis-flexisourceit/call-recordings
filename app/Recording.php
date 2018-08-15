<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recording extends Model
{
    /**
     * @var string
     */
    protected $table = 'recordings';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var array
     */
    protected $fillable = [
        'file_name',
        'path',
        'type',
        'file_size'
    ];

    /**
     * @var array
     */
    protected $appends = [
        'url'
    ];

    /**
     * @return mixed
     */
    protected function getUrlAttribute()
    {
        return Storage::url($this->path);
    }
}
