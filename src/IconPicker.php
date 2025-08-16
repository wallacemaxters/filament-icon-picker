<?php

namespace WallaceMaxters\FilamentIconPicker;

use Filament\Forms\Components\Select;
use WallaceMaxters\FilamentIconPicker\IconsManager;

class IconPicker extends Select
{
    protected array $sets = ['default'];

    public function setUp(): void
    {
        parent::setUp();

        $this
            ->native(false)
            ->searchable()
            ->getOptionLabelUsing(fn($state) => $this->getIconManager()->find($state, asHtml: true))
            ->options(fn () => $this->getIconManager()->getFormattedOptions())
            ->getSearchResultsUsing(fn (?string $search) => $this->getIconManager()->getFormattedOptions($search))
            ->allowHtml();
    }

    public function sets(string ...$sets)
    {
        $this->sets = $sets;

        return $this;
    }

    protected function getIconManager(): IconsManager
    {
        return IconsManager::make()->sets(...$this->sets);
    }
}
