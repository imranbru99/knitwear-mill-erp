<?php

namespace App\Support\Integrations;

use ImranDev\UniversalSlug\Facades\UniversalSlug;

class Slugger
{
    public function from(string $text): string
    {
        $slug = UniversalSlug::generate($text, '-', 'bn');

        return $slug !== '' ? $slug : 'item';
    }
}
