<?php

namespace App\Filament\Widgets;

use App\Models\ShopifyAppEvent;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class UnInstallsChartWidget extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected static ?string $heading = 'Uninstalls';

    public ?string $filter = 'month';

    protected function getFilters(): ?array
    {
        return [
            'today' => 'today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $selectedApp = $this->filters['App'] ?? null;

        $start = match ($activeFilter) {
            'today' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $query = ShopifyAppEvent::query()
            ->when($selectedApp, fn ($query) => $query->where('app_id', $selectedApp))
            ->where('type', 'RELATIONSHIP_UNINSTALLED');

        $data = Trend::query($query)
            ->between(start: $start, end: now())
            ->perDay()
            ->dateColumn('occurred_at')
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Uninstalls',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    public function getDescription(): string
    {
        $totalUninstalls = $this->getData()['datasets'][0]['data']->sum();

        return "Total Uninstalls: $totalUninstalls";
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
