<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public $laporan, $kasMasuk, $kasKeluar, $totalMasuk, $totalKeluar, $selisihKas;

    public function __construct($laporan, $kasMasuk, $kasKeluar, $totalMasuk, $totalKeluar, $selisihKas)
    {
        $this->laporan     = $laporan;
        $this->kasMasuk    = $kasMasuk;
        $this->kasKeluar   = $kasKeluar;
        $this->totalMasuk  = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->selisihKas  = $selisihKas;
    }


    public function view(): View
    {
        return view('laporan.excel', [
            'laporan'      => $this->laporan,
            'kasMasuk'     => $this->kasMasuk,
            'kasKeluar'    => $this->kasKeluar,
            'totalMasuk'   => $this->totalMasuk,
            'totalKeluar'  => $this->totalKeluar,
            'selisihKas'   => $this->selisihKas,
        ]);
    }


    public function styles(Worksheet $sheet)
    {
        // Header utama
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '4CAF50'] // hijau
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center'
            ]
        ]);

        // Border semua baris
        $lastRow = count($this->laporan) + 1;

        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Masuk
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Keluar
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Saldo
        ];
    }
}
