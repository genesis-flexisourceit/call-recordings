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
        'status',
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
        return Storage::disk('gcs')->url($this->path);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function transcription()
    {
        return $this->hasOne(Transcription::class, 'recording_id', 'id');
    }
}
