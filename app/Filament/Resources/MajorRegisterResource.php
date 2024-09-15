<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MajorRegisterResource\Pages;
use App\Filament\Resources\MajorRegisterResource\RelationManagers;
use App\MajorType;
use App\Models\MajorRegister;
use App\Models\Student;
use App\UniversityType;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;



class MajorRegisterResource extends Resource
{
    protected static ?string $model = MajorRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                ->label('အမည်'),
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
