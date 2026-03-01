<?php

namespace App\Filament\Widgets;

use App\Models\CategoryModel;
use App\Models\OrderModel;
use App\Models\ProductModel;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $period = $this->filters['period'] ?? 'this_month';

        $now = Carbon::now();

        switch ($period) {

            case 'last_month':
                $start = $now->copy()->subMonthNoOverflow()->startOfMonth();
                $end   = $now->copy()->subMonthNoOverflow()->endOfMonth();
                $label = 'Bulan Kemarin (' . $start->translatedFormat('F Y') . ')';
                break;

            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end   = $now->copy()->endOfYear();
                $label = 'Tahun Ini (' . $now->year . ')';
                break;

            case 'last_year':
                $start = $now->copy()->subYearNoOverflow()->startOfYear();
                $end   = $now->copy()->subYearNoOverflow()->endOfYear();
                $label = 'Tahun Kemarin (' . $start->year . ')';
                break;

            default:
                $start = $now->copy()->startOfMonth();
                $end   = $now->copy()->endOfMonth();
                $label = 'Bulan Ini (' . $start->translatedFormat('F Y') . ')';
                break;
        }

        $totalRevenue = OrderModel::query()
            ->where('status', 'done')
            ->whereBetween('created_at', [$start, $end])
            ->sum('total_price') ?? 0;

        return [

            Stat::make('Produk', number_format(ProductModel::count(), 0, ',', '.'))
                ->description('Total Produk')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary'),

            Stat::make('Pengguna', number_format(User::count(), 0, ',', '.'))
                ->description('Total Pengguna')
                ->descriptionIcon('heroicon-o-users')
                ->color('success'),

            Stat::make('Kategori', number_format(CategoryModel::count(), 0, ',', '.'))
                ->description('Total Kategori')
                ->descriptionIcon('heroicon-o-tag')
                ->color('warning'),

            Stat::make(
                'Keuntungan',
                'Rp ' . number_format($totalRevenue, 0, ',', '.')
            )
                ->description($label)
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('danger'),

        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}
