<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Filament\Resources\UniversityResource\RelationManagers\StudentRelationManager;
use App\Filament\Resources\UniversityResource\RelationManagers\UniversitiesRelationManager;
use App\MajorType;
use App\Models\AppSettings;
use App\Models\MajorRegister;
use App\Models\NRC;
use App\Models\Student;
use App\Models\Team;
use App\UniversityType;
use Faker\Provider\Text;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Str;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'ကျောင်းသား အချက်လက်';
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {

            $count = static::getModel()::where('team_id', Filament::getTenant()->id)->count(); // Adjust 'attendance_year' based on your actual column name

            return (string) $count;
    }
    protected static ?string $navigationBadgeTooltip = 'ကျောင်းသား အရေအတွက်';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                TextInput::make('name')
                    ->label('ကျောင်းသား/သူ အမည်'),
                DatePicker::make('date_of_birth')
                    ->label('မွေးသက္ကရ်')
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
                        Fieldset::make('မိခင် အချက်လက်')
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
                        Textarea::make('address')->label('လိပ်စာ'),
                        Textarea::make('note')->label('မှတ်ချက်'),
                        Checkbox::make('create_major_register')
                            ->label('လက်ရှိကျောင်းသားအား မေဂျာတင်မည်')
                                    ->live(),
                        Checkbox::make('create_major_register')
                            ->label('လက်ရှိကျောင်းသားအား ကျောင်းအပ်မည်')
                            ->live(),

                Section::make('မေဂျာတင်')
                    ->visible(fn (Get $get): bool => $get('create_major_register'))
                    ->relationship('majorRegister')
                    ->description('မေဂျာတင်ရန် လိုအပ်သည်များဖြည့်ပါ')
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
                            ->label('မေဂျာ')
                            ->options(MajorType::class)
                            ->native(false),
                        Forms\Components\Select::make('get_university')
                            ->label('တက္ကသိုလ်')
                            ->options(UniversityType::class)
                            ->native(false),
                        Textarea::make('note')
                            ->label('မေဂျာတင် မှတ်ချက်'),
                    ]),


            ]);
    }
    protected function getData()
    {
        return Model::all(); // Replace Model with your actual model
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('အမည်'),

                TextColumn::make('year_of_attendance')
                    ->label('Current Level')
                    ->sortable()
                    ->getStateUsing(function (Student $record) {
                        // Access the last related university record and return the year_of_attendance
                        return optional($record->universities->last())->year_of_attendance;
                    }),

                TextColumn::make('student_nrc_code_and_no')
                    ->label('ကျောင်းသား မှတ်ပုံတင်')
                    ->sortable()
                    ->getStateUsing(function (Student $record) {
                        return "{$record->student_nrc_code} - {$record->student_nrc_no}";
                    }),

                TextColumn::make('student_code')
                ->label('Student ID')
                ->sortable(),


            TextColumn::make('date_of_birth')
                ->label('မွေးသက္ကရ်')
                ->sortable()
                ->date(), // Display as date

            TextColumn::make('grade_10_desk_id')
                ->label('၁၀တန်းခုံနံပါတ်')
                ->sortable(),

            TextColumn::make('grade_10_total_mark')
                ->label('၁၀တန်း အမှတ်ပေါင်း')
                ->sortable(),

            TextColumn::make('grade_10_passed_year')
                ->label('၁၀တန်း အောင်ခုနှစ်')
                ->sortable(),

            TextColumn::make('father_name')
                ->label('အဖေအမည်')
                ->sortable(),

                TextColumn::make('father_nrc_code_and_no')
                    ->label('အဖေ မှတ်ပုံတင်')
                    ->sortable()
                    ->getStateUsing(function (Student $record) {
                        return "{$record->father_nrc_code} - {$record->father_nrc_no}";
                    }),

            TextColumn::make('mother_name')
                ->label('အမေအမည်')
                ->sortable(),

                TextColumn::make('mother_nrc_code_and_no')
                    ->label('အမေ မှတ်ပုံတင်')
                    ->sortable()
                    ->getStateUsing(function (Student $record) {
                        return "{$record->mother_nrc_code} - {$record->mother_nrc_no}";
                    }),

            TextColumn::make('student_phone')
                ->label('ကျောင်းသား ဖုန်း')
                ->sortable(),

            TextColumn::make('parent_phone')
                ->label('အုပ်ထိန်းသူဖုန်း')
                ->sortable(),

            TextColumn::make('address')
                ->label('လိပ်စာ')
                ->sortable()
                ->searchable(),

            TextColumn::make('note')
                ->label('မှတ်ချက်')
                ->sortable()
                ->searchable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Action::make('transfer')
                    ->label('လွှဲပြောင်းမည်')
                    ->action(function (Student $record, array $data) {
                        $record->team_id = $data['team_id'];
                        $record->save();
                    })
                    ->form([
                        Forms\Components\Select::make('team_id')
                            ->label('ဆိုင်ခွဲ')
                            ->required()
                            ->options(Team::all()->pluck('name', 'id'))
                            ->placeholder('ဆိုင်ခွဲရွေးပါ'),
                    ])
                ]),
            ])
            ->headerActions([

            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
              ->extraViewData([
                  'myVariable' => 'ကျောင်းသား/သူ အချက်လက်',

              ]),
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
