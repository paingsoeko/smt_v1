<?php

namespace App\Filament\Resources;

use AlperenErsoy\FilamentExport\Actions\FilamentExportBulkAction;
use App\Filament\Resources\MajorRegisterResource\Pages;
use App\Filament\Resources\MajorRegisterResource\RelationManagers;
use App\MajorType;
use App\Models\AppSettings;
use App\Models\MajorRegister;
use App\Models\Student;
use App\UniversityType;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Support\View\Components\Modal;

class MajorRegisterResource extends Resource
{
    protected static ?string $model = MajorRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getNavigationLabel(): string
    {
        $currentYear = AppSettings::where('team_id', Filament::getTenant()->id)->first()->year_of_attendance_major ?? '';
        if ($currentYear) {
            return 'မေဂျာတင်' . '(' . $currentYear . ')';
        }else{
            return 'မေဂျာတင်';
        }

    }
    protected static ?int $navigationSort = 1;
    public static function getNavigationBadge(): ?string
    {
        // Retrieve the current year of attendance major based on the team ID
        $appSetting = AppSettings::where('team_id', Filament::getTenant()->id)->first();

        // Check if the AppSettings entry exists and has the year_of_attendance_major
        if ($appSetting && $appSetting->year_of_attendance_major) {
            // Count the models that match the current year of attendance
            $count = static::getModel()::where('current_attendance_year', $appSetting->year_of_attendance_major)->count(); // Adjust 'attendance_year' based on your actual column name

            return (string) $count; // Convert the count to string
        }

        return 0; // Return null if no settings found or year is not set
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Student')
                           ->options(function (callable $get, string $operation) {

                               if ($operation === 'create') {
                                   return Student::whereDoesntHave('universities')
                                                ->whereDoesntHave('majorRegister')
                                                ->pluck('name', 'id');
                               }


                              return Student::where('id', $get('student_id'))->pluck('name', 'id');


                                                                  //                                   $currentStudentId = $get('student_id'); // Get current student_id in edit mode
//                                   return Student::whereDoesntHave('universities')
//                                       ->where('id', '!=', $currentStudentId) // Exclude current student
//                                        ->whereDoesntHave('majorRegister')
//                                       ->pluck('name', 'id');

                           })
                    ->searchable()  // Optional: allows searching through the list of students
                    ->preload()
                    ->required()
                ->disabled(fn (string $operation): bool => $operation === 'edit')
                   ->createOptionForm([

                       TextInput::make('student_code')

                               ->label('Student Code')
                               ->required()
                                ->default(fn() => strtoupper(substr(Filament::getTenant()->name, 0, 3)) . '-' . strtoupper(Str::random(8)))
                               ->readOnly()
                               ->maxLength(255),
                       TextInput::make('name')
                   ])
     ->createOptionUsing(function (array $data): int {
           // Create a new student record or find an existing one based on the name
    $student = Student::firstOrCreate(
        ['name' => $data['name']],
        ['student_code' => $data['student_code']]  // You can add other attributes if necessary
    );

    // Return the ID of the created or found student
    return $student->id;
     }),
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

            ]);
    }



    public static function table(Table $table): Table
    {
        $currentYear = AppSettings::where('team_id', Filament::getTenant()->id)->first()->year_of_attendance_major;
//        dd($currentYear);
        return $table
            ->query(\App\Models\MajorRegister::where('team_id', Filament::getTenant()->id)->where('current_attendance_year', $currentYear))
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                ->label('အမည်'),
                TextColumn::make('student.student_code')
                    ->label('Student ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ar_wa_tha_no')
                ->label('အဝသ'),
                Tables\Columns\TextColumn::make('major')
                ->label('မေဂျာ'),
                Tables\Columns\TextColumn::make('get_university')
                ->label('တက္ကသိုလ်'),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                 ->icon('heroicon-m-pencil-square')
    ->button()
    ->labeledFrom('md'),


            ])
            ->bulkActions([
                FilamentExportBulkAction::make('export')
                    ->extraViewData([
                        'myVariable' => 'မေဂျာတင် အချက်လက်',

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMajorRegisters::route('/'),
            'create' => Pages\CreateMajorRegister::route('/create'),
            'view' => Pages\ViewMajorRegister::route('/{record}'),
            'edit' => Pages\EditMajorRegister::route('/{record}/edit'),
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
