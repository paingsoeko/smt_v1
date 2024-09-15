<?php

namespace App\Filament\Resources\MajorRegisterResource\Pages;

use App\Filament\Resources\MajorRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMajorRegister extends EditRecord
{
    protected static string $resource = MajorRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
