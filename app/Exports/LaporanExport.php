<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $laporan;
    protected $saldoAwal;
    protected $totalMasuk;
    protected $totalKeluar;
    protected $saldoAkhir;
    // Tambahan Data Baru
    protected $sisaUangFisik;
    protected $selectedBulan;
    protected $selectedTahun;

    public function __construct(
        $laporan,
        $saldoAwal,
        $totalMasuk,
        $totalKeluar,
        $saldoAkhir,
        $sisaUangFisik,
        $selectedBulan,
        $selectedTahun
    ) {
        $this->laporan = $laporan;
        $this->saldoAwal = $saldoAwal;
        $this->totalMasuk = $totalMasuk;
        $this->totalKeluar = $totalKeluar;
        $this->saldoAkhir = $saldoAkhir;
        $this->sisaUangFisik = $sisaUangFisik;
        $this->selectedBulan = $selectedBulan;
        $this->selectedTahun = $selectedTahun;
    }

    /**
     * Menghubungkan ke View Blade
     */
    public function view(): View
    {
        return view('laporan.excel', [
            'laporan'       => $this->laporan,
            'saldoAwal'     => $this->saldoAwal,
            'totalMasuk'    => $this->totalMasuk,
            'totalKeluar'   => $this->totalKeluar,
            'saldoAkhir'    => $this->saldoAkhir,
            'sisaUangFisik' => $this->sisaUangFisik,
            'selectedBulan' => $this->selectedBulan,
            'selectedTahun' => $this->selectedTahun,
        ]);
    }

    /**
     * Styling Tambahan (Opsional karena sudah ada inline CSS di Blade)
     * Kita gunakan ini untuk memastikan font default Excel rapi
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style default untuk seluruh sheet
            1 => ['font' => ['size' => 11]],
        ];
    }
}
