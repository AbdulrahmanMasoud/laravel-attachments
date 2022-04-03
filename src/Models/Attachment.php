<?php

namespace TheAMasoud\LaravelAttachments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attachment extends Model
{
    use HasFactory;
    protected $table = 'attachments';
    protected $guarded = [];

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
