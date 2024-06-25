<?php

namespace App\Exports;

use App\Models\Mq2;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MQ2Export implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Mq2::query()->where('created_at', '>=', now()->subWeek());
    }

    public function headings(): array
    {
        return [
            'ID',
            'Gas Value',
            'Created At',
            'Updated At',
        ];
    }
}
