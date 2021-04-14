<?php

namespace App\Providers;

use App\Models\Log;
use App\Observers\LogObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
		parent::boot();

		if (auth()->guard('panel')->check()) {

			//Listen eloquent save events
			Event::listen('eloquent.saved: *', function ($eventName, $models) {
				$logObserver = new LogObserver();
				//If model name is Log, Events not save
				if(!$models[0] instanceof Log) {
					$logObserver->save($models[0]);
				}
			});

			//Listen eloquent delete events
			Event::listen('eloquent.deleted: *', function ($eventName, $models) {
				$logObserver = new LogObserver();
				//If model name is Log, Events not save
				if(!$models[0] instanceof Log) {
					$logObserver->deleted($models[0]);
				}
			});
		}
    }
}
