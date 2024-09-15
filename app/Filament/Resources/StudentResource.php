<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\UniversityResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\UniversityResource\RelationManagers\UniversitiesRelationManager;
use App\MajorType;
use App\Models\NRC;
use App\Models\Student;
use App\UniversityType;
use Faker\Provider\Text;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Str;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('student_code')
                        ->label('Student Code')
                        ->required()
                         ->default(fn() => strtoupper(substr(Filament::getTenant()->name, 0, 3)) . '-' . strtoupper(Str::random(8)))
                        ->readOnly()
                        ->maxLength(255),
                TextInput::make('name')
                    ->label('ကျောင်းသား/သူ အမည်'),
                DatePicker::make('date_of_birth')
//                    ->minDate(now()->subYears(150))
                    ->maxDate(now()->subYear(10))
                    ->native(true),
                    Fieldset::make('ကျောင်းသား/သူ မှတ်ပုံတင်')
                        ->schema([
                            Select::make('student_nrc_code')
                                ->label('အမှတ်')
                                 ->options(function (): array {
                                     $nrcs = NRC::all();
                                     return $nrcs->mapWithKeys(function ($nrc) {
                                         return [$nrc->nrc_code . ' / ' . $nrc->name_mm => $nrc->nrc_code . ' / ' . $nrc->name_mm];
                                     })->toArray();
                                 })
                                ->searchable(['nrc_code', 'name_mm', 'name_en'])
                                ->searchPrompt('မြန်မာ,အင်္ဂလိပ် နှစ်မျိုးဖြစ်ရှာနိုင်သည်။')
                                ->searchingMessage('မှတ်ပုံတင်အမှတ်ရှဖွေနေသည်...')
                                ->noSearchResultsMessage('မှတ်ပုံတင်အမှတ် ရှာလို့မတွေ့ပါ။')
                                ->preload()
                                ->searchable(),
                            TextInput::make('student_nrc_no')
                                ->label('နံပါတ်')
                                ->ascii()
                                ->alphaNum()
                                ->length(6)
                                ->numeric(),
                        ])
                        ->columns(2),
                          Fieldset::make('၁၀တန်း အချက်လက်')
                        ->schema([
                            TextInput::make('grade_10_passed_year')
                                ->label('ခုနှစ်')
                                ->ascii()
                                ->alphaNum()
                                ->length(4)
                                ->numeric(),
                            TextInput::make('grade_10_desk_id')
                                ->label('ခုံနံပါတ်'),
                            TextInput::make('grade_10_total_mark')
                                ->label('အမှတ်ပေါင်း')
                                  ->ascii()
                                ->alphaNum()
                                ->minValue(100)
                                ->maxValue(600)
                                ->length(3)
                                ->numeric(),
                        ])
                        ->columns(3),
                        Fieldset::make('ဖခင် အချက်လက်')
                        ->schema([
                            TextInput::make('father_name')
                                ->label('အမည်'),
                            Select::make('father_nrc_code')
                                ->label('အမှတ်')
                                 ->options(function (): array {
                                     $nrcs = NRC::all();
                                     return $nrcs->mapWithKeys(function ($nrc) {
                                         return [$nrc->nrc_code . ' / ' . $nrc->name_mm => $nrc->nrc_code . ' / ' . $nrc->name_mm];
                                     })->toArray();
                                 })
                                ->searchable(['nrc_code', 'name_mm', 'name_en'])
                                ->searchPrompt('မြန်မာ,အင်္ဂလိပ် နှစ်မျိုးဖြစ်ရှာနိုင်သည်။')
                                ->searchingMessage('မှတ်ပုံတင်အမှတ်ရှဖွေနေသည်...')
                                ->noSearchResultsMessage('မှတ်ပုံတင်အမှတ် ရှာလို့မတွေ့ပါ။')
                                ->preload()
                                ->searchable(),
                            TextInput::make('father_nrc_no')
                                ->label('နံပါတ်')
                                ->ascii()
                                ->alphaNum()
                                ->length(6)
                                ->numeric(),
                        ])
                        ->columns(3),
                        Fieldset::make('ဖခင် အချက်လက်')
                        ->schema([
                            TextInput::make('mother_name')
                                ->label('အမည်'),
                            Select::make('mother_nrc_code')
                                ->label('အမှတ်')
                                 ->options(function (): array {
                                     $nrcs = NRC::all();
                                     return $nrcs->mapWithKeys(function ($nrc) {
                                         return [$nrc->nrc_code . ' / ' . $nrc->name_mm => $nrc->nrc_code . ' / ' . $nrc->name_mm];
                                     })->toArray();
                                 })
                                ->searchable(['nrc_code', 'name_mm', 'name_en'])
                                ->searchPrompt('မြန်မာ,အင်္ဂလိပ် နှစ်မျိုးဖြစ်ရှာနိုင်သည်။')
                                ->searchingMessage('မှတ်ပုံတင်အမှတ်ရှဖွေနေသည်...')
                                ->noSearchResultsMessage('မှတ်ပုံတင်အမှတ် ရှာလို့မတွေ့ပါ။')
                                ->preload()
                                ->searchable(),
                            TextInput::make('mother_nrc_no')
                                ->label('နံပါတ်')
                                ->ascii()
                                ->alphaNum()
                                ->length(6)
                                ->numeric(),
                        ])
                        ->columns(3),

                          Fieldset::make('ဆက်သွယ်ရန်')
                        ->schema([
                            TextInput::make('student_phone')
                                 ->tel()
                                 ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                 ->label('ကျောင်းသား/သူ ဖုန်း'),
                              TextInput::make('parent_phone')
                                 ->tel()
                                 ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                 ->label('အုပ်ထိန်းသူ ဖုန်း'),
                        ])
                        ->columns(2),
                        Textarea::make('address'),
                        Textarea::make('note'),
                        Checkbox::make('create_major_register')
                                    ->live(),
                        Fieldset::make('majorRegister')
                         ->visible(fn (Get $get): bool => $get('create_major_register'))
    ->relationship('majorRegister')
    ->schema([
Forms\Components\Select::make('type')
                ->label('မေဂျာတင် အမျိုးအစား')
                ->default('distance')
                ->native(false)
                ->options([
                'distance' => 'Distance','day' => 'Day','vip' =>'VIP'
                    ]),
                Forms\Components\TextInput::make('ar_wa_tha_no')
                ->label('အဝသ အမှတ်')
                ->numeric(),
                Forms\Components\TextInput::make('aprove_no')
                ->label('ဝဥ်ခွင့်စဥ်')
                ->numeric(),
                 Forms\Components\Select::make('major')
                      ->options(MajorType::class)
                   ->native(false),
                     Forms\Components\Select::make('get_university')
                      ->options(UniversityType::class)
                   ->native(false),
                        Textarea::make('note'),
    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
                UniversitiesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
