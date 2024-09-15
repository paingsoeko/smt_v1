<?php

namespace App\Filament\Resources\MajorRegisterResource\Pages;

use App\Filament\Resources\MajorRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMajorRegister extends ViewRecord
{
    protected static string $resource = MajorRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
