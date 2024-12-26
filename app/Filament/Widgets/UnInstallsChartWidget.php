<?php

namespace App\Filament\Widgets;

use App\Models\ShopifyAppEvent;
use Filament\Facades\Filament;
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
        $active_filter = $this->filter;
        $selected_app = $this->filters['App'] ?? null;

        $start = match ($active_filter) {
            'today' => now()->subDay(),
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };

        $query = ShopifyAppEvent::query()
            ->when($selected_app, fn ($query) => $query->where('app_id', $selected_app))
            ->where('type', 'RELATIONSHIP_UNINSTALLED')
            ->where('partner_id',Filament::getTenant()->id);

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
