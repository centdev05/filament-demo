<?php

namespace App\Filament\Resources\Shop\OrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Shop\Order;
use Filament\Widgets\PieChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderStatsLineChart extends PieChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = "10s";

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Current year orders per month';

    protected int | string | array $columnSpan = '1';

    protected static ?string $maxHeight = '500px';

    // protected static ?array $options = [
    //     'scales' => [
    //         'x' => [
    //             'display' => false,
    //         ],
    //         'y' => [
    //             'display' => false,
    //         ],
    //     ]
    // ];


    protected function getType(): string
    {
        return 'line';
    }

    protected function getTablePage(): string
    {
        return ListOrders::class;
    }

     protected function getData(): array
     {
        $orderData = Trend::model(Order::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $data = $orderData->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray();

         return [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'datasets' => [
                [
                    'label'         => 'Orders by month line',
                    'fill'          => true,
                    'backgroundColor'=> '#FFE5E5',
                    'data'          => $data,
                    'borderColor'   => 'red',
                    'tension'       => '0.1',
                ],
            ],
        ];
     }
}
