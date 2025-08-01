<?php

namespace WallaceMaxters\FilamentIconPicker;

use Filament\Support\Assets\Css;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;

class FilamentIconPickerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        FilamentAsset::register(
            [
                Css::make('filament-icon-picker', __DIR__ . '/../resources/dist/filament-icon-picker.css'),
            ],
            'wallacemaxters/filament-icon-picker'
        );
    }
}
