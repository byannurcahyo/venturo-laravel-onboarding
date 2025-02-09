<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportSalesCategory implements FromView
{
    private $reports;
    public function __construct(array $reports)
    {
        $this->reports = $reports;
    }

    public function view(): View
    {
        return view('generate.excel.report-sales', $this->reports);
    }
}
