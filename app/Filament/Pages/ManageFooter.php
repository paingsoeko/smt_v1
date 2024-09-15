<?php

namespace App\Filament\Pages;

use App\Models\AppSettings;
use App\Models\FooterSettings;
//use App\Settings\FooterSettings;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageFooter extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = AppSettings::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('year_of_attendance_major')
                    ->label('Year of Attendance Major')
                    ->options(array_combine(range(2010, 2040), range(2010, 2040))) // Generates years from 2010 to 2040
                    ->required()->native(false),
                Select::make('year_of_attendance_university')
    ->label('Year of Attendance University')
    ->options(array_combine(range(2010, 2040), range(2010, 2040))) // Generates years from 2010 to 2040
    ->required()->native(false),

            ]);
    }
}
