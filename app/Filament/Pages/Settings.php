<?php

namespace App\Filament\Pages;

use App\Models\AppSettings;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    protected static string $view = 'filament.pages.settings';

    protected static ?string $navigationLabel = 'အသုံးပြုသူ ချိန်ညှိရန်';

    protected static ?int $navigationSort = 5;

    public function mount()
    {
        // Assuming you are fetching existing settings based on team_id
        $teamId = Filament::getTenant()->id;

        // Fetch existing settings, or set default values if not found
        $settings = AppSettings::where('team_id', $teamId)
            ->with('updater')
            ->first();

        $updaterName = $settings->updater->name ?? 'Unknown updater';
        // Initialize form data with existing settings or empty values
        $this->data = [
            'year_of_attendance_major' => $settings->year_of_attendance_major ?? null,
            'year_of_attendance_university' => $settings->year_of_attendance_university ?? null,
            'created_by' => $settings->created_by ?? null,
            'updated_by' => $settings->updated_by ?? null,
            'created_at' => $settings->created_at ?? null,
            'updated_at' => $settings->updated_at ?? null,
            'updater_name' => $updaterName ?? null,
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Placeholder::make('updated')
                    ->label('')
                    ->content(fn (): string => $this->data['updated_at']
                        ? \Carbon\Carbon::parse($this->data['updated_at'])->diffForHumans() . " တွင် " . ($this->data['updater_name'] ?? 'အမည်မသိသူ') . " ချိန်ညှိခဲ့သည်။"
                        : 'ချိန်ညှိမှုများ မပြုလုပ်ရသေးပါ'),
                Select::make('year_of_attendance_major')
                    ->label('မေဂျာတင် ပညာသင်နှစ်')
                    ->options(
                        collect(range(2015, 2050))->mapWithKeys(function ($year) {
                            return [$year => $year];
                        })->toArray()
                    ),

                Select::make('year_of_attendance_university')
                    ->label('ကျောင်းအပ် ပညာသင်နှစ်')
                    ->options(
                        collect(range(2015, 2050))->mapWithKeys(function ($year) {
                            return [$year => $year];
                        })->toArray()
                    ),

            ])->statePath('data'); // Bind the form state to the 'data' property
    }


    public function getFormActions(): array{
        return [
            Action::make('save')->label('Save')->submit('save'),
        ];
    }

    public function save()
    {
        try {
            $data = $this->form->getState();
            $teamId = Filament::getTenant()->id;

            // Check if a record already exists for the given team_id
            $settings = AppSettings::updateOrCreate(
                ['team_id' => $teamId], // Condition to check if a record exists for this team_id
                [
                    'year_of_attendance_major' => $data['year_of_attendance_major'],
                    'year_of_attendance_university' => $data['year_of_attendance_university'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]
            );

        } catch (Halt $ex) {
            return;
        }

        // Send a success notification
        Notification::make()->success()->title('ပြောင်းလဲမှု အောင်မြင်ပါသည်')->send();
    }

}
