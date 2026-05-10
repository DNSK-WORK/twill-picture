<?php

namespace DnskWork\TwillPicture;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TwillPictureServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register as <x-twill-picture::picture> — or publish the view and use <x-picture>.
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components', 'twill-picture');

        $this->publishes([
            __DIR__ . '/../resources/views/components/picture.blade.php' => resource_path('views/components/picture.blade.php'),
        ], 'twill-picture-views');
    }
}
