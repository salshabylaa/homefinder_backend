<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ApplicationsChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Pendaftar (7 Hari Terakhir)';

    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $labels[] = $date->format('d M');
            $count = \App\Models\Application::whereDate('created_at', $date)->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendaftar Baru',
                    'data' => $data,
                    'borderColor' => '#3b82f6', // Blue 500
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}
