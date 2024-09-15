<?php

namespace App\Filament\Resources\MajorRegisterResource\Pages;

use App\Filament\Resources\MajorRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMajorRegisters extends ListRecords
{
    protected static string $resource = MajorRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
