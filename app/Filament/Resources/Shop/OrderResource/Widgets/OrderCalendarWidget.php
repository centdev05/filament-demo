<?php

namespace App\Filament\Resources\Shop\OrderResource\Widgets;

use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Shop\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class OrderCalendarWidget extends FullCalendarWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = "3s";

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Current year orders per month';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '500px';

    /**
     * Return events that should be rendered statically on calendar.
     */
    public function getViewData(): array
    {
        $orders = Order::all()
            ->where('created_at', '>', now()->startOfYear())
            ->where('created_at', '<', now()->endOfYear());

        $ordersData = [];

        foreach($orders as $index => $order) {
            $ordersData[] = [
                'id' => $index,
                'start' => $order->created_at->toDateString(),
                'end' => $order->created_at->toDateString()
            ];
        }

        // dd($ordersData);

        return $ordersData;
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        return [];
    }
}
