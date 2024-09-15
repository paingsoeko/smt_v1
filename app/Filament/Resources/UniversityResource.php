<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UniversityResource\Pages;
use App\Filament\Resources\UniversityResource\RelationManagers;
use App\MajorType;
use App\Models\Student;
use App\Models\University;
use App\UniversityType;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UniversityResource extends Resource
{
    protected static ?string $model = University::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                ->label('ကျောင်းသား/သူ')
                   ->relationship(name: 'student', titleAttribute: 'name')
                   ->searchable()
                   ->native(false)
                   ->preload()
                   ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    // Fetch the student with its related universities
                    $student = \App\Models\Student::with('universities')  // Replace with correct Student model path
                        ->where('id', $state)->first();

                    if ($student && $student->universities->isNotEmpty()) {
                        // If the student has no universities, get the last year of attendance
                        $lastYearOfAttendance = $student->universities->last()->year_of_attendance ?? null;
                        $lastMajor = $student->universities->last()->major ?? null;
                        $lastType =  $student->universities->last()->type ?? null;
                        $lastDeskId =  $student->universities->last()->current_desk_id ?? null;

                        $set('major', $lastMajor);
                        // Set year_of_attendance based on the last entry or keep it null

                        $set('type', $lastType);

                        switch ($lastYearOfAttendance) {
                            case "First Year":
                                $set('year_of_attendance', "Second Year");
                                break;  // Exit the switch once this case is matched

                            case "Second Year":
                                $set('year_of_attendance', "Third Year");
                                break;

                            case "Third Year":
                                $set('year_of_attendance', "Fourth Year");
                                break;

                            case "Fourth Year":
                                $set('year_of_attendance', "Fifth Year");
                                break;

                            default:
                                $set('year_of_attendance', "First Year");  // Default if no case matches
                                break;
                        }

                        $set('last_desk_id',$lastDeskId);

                    } else {
                        // If the student has universities, set year_of_attendance to 'First Year'
                        $set('year_of_attendance', 'First Year');
                    }
                }),

                   Forms\Components\Select::make('type')
                      ->options(UniversityType::class)
                   ->native(false),


                   Forms\Components\Select::make('major')
                      ->options(MajorType::class)
                   ->native(false)
       ->reactive(),
                   Forms\Components\Select::make('year_of_attendance')
                      ->options([
                            "First Year" => "ပထမနှစ်",
                            "Second Year" => "ဒုတိယနှစ်",
                            "Third Year" => "တတိယနှစ်",
                            "Fourth Year" => "စတုတ္ထနှစ်",
                            "Fifth Year" => "ပဥမနှစ်",
                        ])
                   ->native(false)
             ->reactive(),
                   Forms\Components\TextInput::make('last_desk_id')
                    ->prefix(function ($state, $get) {
                          // Fetch values for major and year_of_attendance
                          $major = $get('major');
                          $yearOfAttendance = $get('year_of_attendance');

                          // Prefix logic
                          $majorPrefix = $major ? strtoupper(substr($major, 0, 3)) : '';
                          $yearMap = [
                              'First Year' => '1',
                              'Second Year' => '2',
                              'Third Year' => '3',
                              'Fourth Year' => '4',
                              'Fifth Year' => '5',
                          ];

                          $yearPrefix = $yearOfAttendance ? $yearMap[$yearOfAttendance] ?? '' : '';

                          // Combine major and year prefixes
                          return $yearPrefix . ' / ' . $majorPrefix;
                      })
                   ->label('ယခင်နှစ်ခုံနံပါတ်'),
                   Forms\Components\TextInput::make('current_desk_id')
                      ->prefix(function ($state, $get) {
                          // Fetch values for major and year_of_attendance
                          $major = $get('major');
                          $yearOfAttendance = $get('year_of_attendance');

                          // Prefix logic
                          $majorPrefix = $major ? strtoupper(substr($major, 0, 3)) : '';
                          $yearMap = [
                              'First Year' => '1',
                              'Second Year' => '2',
                              'Third Year' => '3',
                              'Fourth Year' => '4',
                              'Fifth Year' => '5',
                          ];

                          $yearPrefix = $yearOfAttendance ? $yearMap[$yearOfAttendance] ?? '' : '';

                          // Combine major and year prefixes
                          return $yearPrefix . ' / ' . $majorPrefix;
                      })
                   ->label('ယခုနှစ်ခုံနံပါတ်'),

                   Forms\Components\Repeater::make('desk_id_history')
                      ->schema([
                          TextInput::make('desk_id')->label('ခုံနံပါတ်'),
                                TextInput::make('year')->label('ခုနှစ်'),


                      ])
                      ->columns(2)
                   ->label('ခုံနံပါတ် နှင့် ခုနှစ်'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListUniversities::route('/'),
            'create' => Pages\CreateUniversity::route('/create'),
            'view' => Pages\ViewUniversity::route('/{record}'),
            'edit' => Pages\EditUniversity::route('/{record}/edit'),
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
