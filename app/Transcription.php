<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transcription extends Model
{
    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $table = 'transcriptions';

    /**
     * @var array
     */
    protected $fillable = [
        'words',
        'recording_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recording()
    {
        return $this->belongsTo(Recording::class, 'recording_id', 'id');
    }

    /**
     * @param $value
     */
    public function setWordsAttribute($value)
    {
        $this->attributes['words'] = htmlentities($value);
    }

    /**
     * @return string
     */
    public function getWordsAttribute()
    {
        return html_entity_decode($this->attributes['words']);
    }
}
