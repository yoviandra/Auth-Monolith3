<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Modules\Circulation\Events\BookBorrowed;
use App\Modules\Circulation\Listeners\LogBookBorrowed;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(
            BookBorrowed::class,
            LogBookBorrowed::class
        );
    }
}
