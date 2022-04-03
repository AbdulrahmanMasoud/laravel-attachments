<?php

namespace TheAMasoud\LaravelAttachments;

use TheAMasoud\LaravelAttachments\Models\Attachment;
use Illuminate\Support\Facades\Storage;

trait Attachmentable
{
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    public function newAttach($file, $dirName = 'public')
    {
        if (isset($file)) {
            $fileName = md5(time()).'.'.$file->extension();
            Storage::disk('public')->putFileAs(
                $dirName,
                $file,
                $fileName
            );
            $this->attachments()->create([
                'name' => $fileName
            ]);
        } else {
            return 'ther is no file';
        }
    }

    public function newAttachs($files, $dirName = 'public')
    {
        foreach ($files as $file) {
            $this->newAttach($file, $dirName);
        }
    }
}
