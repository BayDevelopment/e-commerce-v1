<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\OrderItemModel;
use Illuminate\Support\Facades\DB;

class ProductPurchasedChart extends ChartWidget
{
    protected ?string $heading = 'Produk yang Dibeli (Done)';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $data = OrderItemModel::select(
            'product_name',
            DB::raw('SUM(quantity) as total')
        )
            ->whereHas('order', function ($query) {
                $query->where('status', 'done');
            })
            ->groupBy('product_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Dibeli',
                    'data' => $data->pluck('total'),
                ],
            ],
            'labels' => $data->pluck('product_name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
