<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\CategoryModel;

class ProductPerCategoryChart extends ChartWidget
{
    protected ?string $heading = 'Jumlah Produk per Category';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $data = CategoryModel::withCount('products')->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Produk',
                    'data' => $data->pluck('products_count'),
                ],
            ],
            'labels' => $data->pluck('name'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
        // bisa juga: pie, doughnut, line
    }
}
