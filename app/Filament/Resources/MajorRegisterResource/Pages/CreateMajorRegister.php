<?php

namespace App\Filament\Resources\MajorRegisterResource\Pages;

use App\Filament\Resources\MajorRegisterResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMajorRegister extends CreateRecord
{
    protected static string $resource = MajorRegisterResource::class;


}
