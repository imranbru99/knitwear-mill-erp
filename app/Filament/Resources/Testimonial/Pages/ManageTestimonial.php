<?php

namespace App\Filament\Resources\Testimonial\Pages;

use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTestimonial extends ManageRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [\Filament\Actions\CreateAction::make()];
    }
}
