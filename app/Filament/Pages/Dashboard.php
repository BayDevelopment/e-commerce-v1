<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProductPerCategoryChart;
use App\Filament\Widgets\ProductPurchasedChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';

    protected function getWidgetsColumns(): int | string | array
    {
        return 12;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->schema([   // ✅ ini yang benar (BUKAN components)
                Select::make('period')
                    ->label('Filter Keuntungan')
                    ->options([
                        'this_month' => 'Bulan Ini',
                        'last_month' => 'Bulan Kemarin',
                        'this_year'  => 'Tahun Ini',
                        'last_year'  => 'Tahun Kemarin',
                    ])
                    ->default('this_month')
                    ->live(),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }
    public function getWidgets(): array
    {
        return [
            ProductPerCategoryChart::class,
            ProductPurchasedChart::class,
        ];
    }
}
