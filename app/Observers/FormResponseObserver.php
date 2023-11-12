<?php

namespace App\Observers;

use App\Models\FormResponse;
use App\Notifications\FormRespondedNotification;
use Log;

class FormResponseObserver
{
    /**
     * Handle the FormResponse "created" event.
     */
    public function created(FormResponse $formResponse): void
    {

        $formResponse->user->notify(new FormRespondedNotification());
    }

    /**
     * Handle the FormResponse "updated" event.
     */
    public function updated(FormResponse $formResponse): void
    {
        //
    }

    /**
     * Handle the FormResponse "deleted" event.
     */
    public function deleted(FormResponse $formResponse): void
    {
        //
    }

    /**
     * Handle the FormResponse "restored" event.
     */
    public function restored(FormResponse $formResponse): void
    {
        //
    }

    /**
     * Handle the FormResponse "force deleted" event.
     */
    public function forceDeleted(FormResponse $formResponse): void
    {
        //
    }
}
