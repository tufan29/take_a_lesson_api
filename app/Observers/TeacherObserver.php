<?php

namespace App\Observers;

use App\Models\teacher\Teacher;
use Storage;

class TeacherObserver
{
    /**
     * Handle the Teacher "created" event.
     *
     * @param  \App\Models\teacher\Teacher  $teacher
     * @return void
     */
    public function creating(Teacher $teacher)
    {
        $uniqid=(uniqid().rand(111,999));
        $teacher->uid=$uniqid;

    }
    public function created(Teacher $teacher)
    {
        if(!is_dir(public_path('storage/'.$teacher->uid)))
        Storage::disk('public')->makeDirectory($teacher->uid);
        
    }

    /**
     * Handle the Teacher "updated" event.
     *
     * @param  \App\Models\teacher\Teacher  $teacher
     * @return void
     */
    public function updated(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the Teacher "deleted" event.
     *
     * @param  \App\Models\teacher\Teacher  $teacher
     * @return void
     */
    public function deleted(Teacher $teacher)
    {
        if(is_dir(public_path('storage/'.$teacher->uid)))
        Storage::disk('public')->deleteDirectory($teacher->uid);
    }

    /**
     * Handle the Teacher "restored" event.
     *
     * @param  \App\Models\teacher\Teacher  $teacher
     * @return void
     */
    public function restored(Teacher $teacher)
    {
        //
    }

    /**
     * Handle the Teacher "force deleted" event.
     *
     * @param  \App\Models\teacher\Teacher  $teacher
     * @return void
     */
    public function forceDeleted(Teacher $teacher)
    {
        //
    }
}
