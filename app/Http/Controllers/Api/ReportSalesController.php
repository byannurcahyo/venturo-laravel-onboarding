<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Report\SalesCategoryHelper;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportSalesCategory;

class ReportSalesController extends Controller
{
    private $salesCategory;

    public function __construct()
    {
        $this->salesCategory = new SalesCategoryHelper();
    }
    public function viewSalesCategories(Request $request)
    {
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;
        $categoryId = $request->category_id ?? null;
        $isExportExcel = $request->is_export_excel ?? null;
        $sales = $this->salesCategory->get($startDate, $endDate, $categoryId);
        if ($isExportExcel) {
            return Excel::download(new ReportSalesCategory($sales), 'report-sales-categories.xlsx');
        }
        return response()->json([
            'success' => true,
            'data' => $sales['data'],
            'dates' => $sales['dates'] ?? [],
            'total_per_date' => $sales['total_per_date'] ?? [],
            'grand_total' => $sales['total'] ?? 0,
        ]);
    }
}
