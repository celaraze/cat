<?php

namespace App\Filament\Components\ListRecords;

class Tab extends \Filament\Resources\Pages\ListRecords\Tab
{
    public ?string $url = null;

    public function url($url): Tab|static
    {
        $this->url = $url;

        return $this;
    }
}
