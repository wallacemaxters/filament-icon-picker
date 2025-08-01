<?php

namespace WallaceMaxters\FilamentIconPicker;

use Filament\Forms\Components\Select;
use WallaceMaxters\FilamentIconPicker\IconsManager;

class IconPicker extends Select
{
    public function setUp(): void
    {
        parent::setUp();

        $manager = IconsManager::make();

        $this
            ->native(false)
            ->searchable()
            ->getOptionLabelUsing(static fn($state) => $manager->find($state, asHtml: true))
            ->options($manager->getFormattedOptions(...))
            ->getSearchResultsUsing($manager->getFormattedOptions(...))
            ->allowHtml();
    }
}
