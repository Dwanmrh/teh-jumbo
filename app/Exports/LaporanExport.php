<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $laporan;
    protected $saldoAwal;
    protected $totalMasuk;
    protected $totalKeluar;
    protected $saldoAkhir;

    public function __construct($laporan, $saldoAwal, $totalMasuk, $totalKeluar, $saldoAkhir)
    {
        $this->laporan = $laporan;
        $this->saldoAwal = $saldoAwal;
        $this->totalMasuk = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->saldoAkhir = $saldoAkhir;
    }

    public function collection()
    {
        // Pastikan ini mengembalikan collection, jika array ubah jadi collection
        return collect($this->laporan);
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN TEH SOLO JUMBO'],
            ['Tanggal Cetak: ' . date('d M Y H:i')],
            [''], // Spasi
            [
                'Tanggal',
                'Kode Transaksi',
                'Kategori',
                'Keterangan / Deskripsi',
                'Penerima',
                'Metode',
                'Masuk (Debet)',
                'Keluar (Kredit)',
                'Saldo Berjalan'
            ]
        ];
    }

    // PERBAIKAN UTAMA ADA DI SINI
    public function map($row): array
    {
        return [
            // Gunakan null coalescing operator (??) untuk mencegah error undefined index
            isset($row['tanggal']) ? Carbon::parse($row['tanggal'])->format('d/m/Y') : '-',
            $row['kode'] ?? '-',
            $row['kategori'] ?? '-',
            $row['keterangan'] ?? '-',
            $row['penerima'] ?? '-',
            $row['metode'] ?? '-', // Ini yang menyebabkan error sebelumnya
            $row['masuk'] ?? 0,
            $row['keluar'] ?? 0,
            $row['saldo'] ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            2 => ['font' => ['italic' => true, 'size' => 10]],
            4 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669'] // Emerald Green
                ],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow(); // Ambil baris terakhir data

                // Format Kolom Angka (G, H, I) dari baris 5 sampai terakhir
                // Pastikan ada data sebelum memformat agar tidak error range
                if ($lastRow >= 5) {
                    $sheet->getStyle('G5:I' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
                }

                // Tambahkan Summary di bawah
                $summaryRow = $lastRow + 2;

                // Judul Ringkasan
                $sheet->setCellValue('C' . $summaryRow, 'RINGKASAN:');
                $sheet->getStyle('C' . $summaryRow)->getFont()->setBold(true);

                // Isi Ringkasan
                $sheet->setCellValue('C' . ($summaryRow + 1), 'Saldo Awal');
                $sheet->setCellValue('D' . ($summaryRow + 1), $this->saldoAwal);

                $sheet->setCellValue('C' . ($summaryRow + 2), 'Total Masuk');
                $sheet->setCellValue('D' . ($summaryRow + 2), $this->totalMasuk);

                $sheet->setCellValue('C' . ($summaryRow + 3), 'Total Keluar');
                $sheet->setCellValue('D' . ($summaryRow + 3), $this->totalKeluar);

                $sheet->setCellValue('C' . ($summaryRow + 4), 'SALDO AKHIR');
                $sheet->setCellValue('D' . ($summaryRow + 4), $this->saldoAkhir);

                // Styling Ringkasan
                $sheet->getStyle('D' . ($summaryRow + 1) . ':D' . ($summaryRow + 4))->getNumberFormat()->setFormatCode('#,##0');

                // Bold Saldo Akhir & Beri Warna Background
                $sheet->getStyle('C' . ($summaryRow + 4) . ':D' . ($summaryRow + 4))->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'f3f4f6'] // Light Gray
                    ]
                ]);
            },
        ];
    }
}
