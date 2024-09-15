<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('ဆိုင်ခွဲ', Team::count()),
            Stat::make('အသုံးပြုသူ အရေတွက်', User::count()),
            Stat::make('ကျောင်းသား အရေအတွက်', Student::count()),
        ];
    }
}
