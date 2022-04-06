<?php

namespace TheAMasoud\LaravelAttachments;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use TheAMasoud\LaravelAttachments\Models\Attachment;
use Illuminate\Support\Facades\Storage;

trait Attachmentable
{
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }

    /**
     * @return bool
     */
    public function checkIfExists($attachments): bool
    {
        return $attachments->exists();
    }

    /**
     * @param $files,$dirName
     * @return bool
     */
    public function newAttach($file, $dirName = 'public')
    {
        if (isset($file)) {
            $fileName = md5(time().rand(0, 1000000)).'.'.$file->extension();
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

    /**
     *  @param $files
     *  @return bool
     */
    public function newAttachs($files, $dirName = 'public')
    {
        foreach ($files as $file) {
            $this->newAttach($file, $dirName);
        }
    }

    /**
    * @return array
    */
    public function getAttachs()
    {
        if ($this->checkIfExists($this->attachments())) {
            return $this->attachments()->pluck('name');
        };
    }

    /**
     *  @param $dirName string
     *  @return bool
     */
    public function deleteAttach($dirName = 'public')
    {
        $fileExists = Storage::disk('public')->exists(ltrim($dirName."/".$this->attachments()->first()->name));
        if (!$fileExists) {
            return 'this attachment does not exists in this dir '.$dirName;
        }
        if ($this->checkIfExists($this->attachments())) {
            Storage::disk('public')->delete(ltrim($dirName."/".$this->attachments()->first()->name));
            $this->attachments()->first()->delete();
            return 'attachment has deleted successfuly';
        }
    }
}
