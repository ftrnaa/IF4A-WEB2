<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $transactions;

    public function __construct(Collection $transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
{
    return $this->transactions->map(function ($tx) {
        return [
            'ID'       => $tx->id,
            'Pembeli'  => $tx->nama ?? '-',
            'Email'    => $tx->email ?? '-',
            'Produk'   => $tx->batik->nama ?? '-',
            'Total'    => $tx->total,
            'Status'   => $tx->status,
            'Tanggal'  => optional($tx->created_at)->format('d-m-Y H:i'),
        ];
    });
}

    public function headings(): array
    {
        return [
            'ID',
            'Pembeli',
            'Email',
            'Produk',
            'Total',
            'Status',
            'Tanggal',
        ];
    }
}