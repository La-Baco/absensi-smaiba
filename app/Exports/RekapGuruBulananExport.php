<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapGuruBulananExport implements FromView
{
    protected $exportData;

    public function __construct(array $exportData)
    {
        $this->exportData = $exportData;
    }

    public function view(): View
    {
        return view('admin.rekap.export.bulanan-guru-excel', $this->exportData);
    }
}
