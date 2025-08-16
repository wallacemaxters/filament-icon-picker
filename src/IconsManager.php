<?php

namespace WallaceMaxters\FilamentIconPicker;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Filament\Schemas\Components\Icon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class IconsManager
{
    protected array $sets = ['default'];

    public static function make(): static
    {
        return new static;
    }

    public function sets(string ...$sets)
    {
        $this->sets = $sets;

        return $this;
    }

    public function find(string $name, bool $asHtml = false): array|string|null
    {
        $item = $this->getFromCache()->get($name) ?? null;

        if (is_array($item) && $asHtml) {
            return $this->formatLabelToHtml($item);
        }

        return $item;
    }

    public function search(?string $search = null)
    {
        return $this->getFromCache()
            ->filter($this->getSearchCallback($search));
    }

    public function getFormattedOptions(?string $search = null)
    {
        return $this->search($search)
            ->take(10)
            ->mapWithKeys(fn(array $item) => [$item['name'] => $this->formatLabelToHtml($item)]);
    }

    public function getFromCache()
    {
        return Cache::rememberForever('wallacemaxters_filament_icon_picker', fn () => new Collection($this->getIterator()));
    }

    public function getIterator()
    {
        $sets = app(\BladeUI\Icons\Factory::class)->all();


        foreach ($this->sets as $set) {

            if (empty($sets[$set])) continue;

            $prefix = $sets[$set]['prefix'] ?? null;

            $prefix && $prefix .= '-';

            foreach (File::allFiles($sets[$set]['paths']) as $item) {

                if (Str::lower($item->getExtension()) !== 'svg') continue;

                $basename = $item->getBasename('.svg');

                $name = $prefix . $basename;

                yield $name => [
                    'label'    => Str::headline($basename),
                    'filename' => $item->getRealPath(),
                    'icon'     => $name,
                    'name'     => $name,
                ];
            }
        }
    }

    public function formatLabelToHtml(array $item)
    {
        $icon = Icon::make($item['name'])->extraAttributes(['class' => 'fi-icon-picker-icon'])->toHtml();

        return <<<HTML
            <div class="fi-icon-picker-item">
                {$icon}
                <div class='grow'>{$item['label']}</div>
            </div>
        HTML;
    }

    protected function getSearchCallback(?string $search = null)
    {
        return fn(array $item) => blank($search) || Str::contains($item['label'], $search) || Str::contains($item['icon'], $search);
    }
}
