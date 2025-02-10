<?php

namespace App\Helpers\Sales;

use Throwable;
use App\Helpers\Venturo;
use App\Models\SalesModel;
use App\Models\SalesDetailModel;
use Illuminate\Support\Facades\Hash;

class SalesHelper extends Venturo
{
    private $salesModel;
    private $salesDetailModel;
    public function __construct()
    {
        $this->salesModel = new SalesModel();
        $this->salesDetailModel = new SalesDetailModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $sales = $this->salesModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $sales,
            'total' => $sales->total()
        ];
    }

    public function getSalesByCustomer(array $filter): array
    {
        $sales = $this->salesModel->with(['customer', 'details.product']);

        if (!empty($filter['customer_id'])) {
            $sales->where('m_customer_id', $filter['customer_id']);
        }

        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $sales->whereBetween('date', [$filter['start_date'], $filter['end_date']]);
        }
        $sales = $sales->get();

        $groupedSales = $sales->groupBy('customer.name')->map(function ($customerSales) {
            return [
                'customer_name' => $customerSales->first()->customer->name,
                'total_sales' => $customerSales->sum('details.price'),
                'transactions' => $customerSales,
            ];
        });

        return [
            'status' => true,
            'data' => $groupedSales->values(),
            'total' => $groupedSales->sum('total_sales')
        ];
    }

    public function getById(string $id): array
    {
        $sales = $this->salesModel->getById($id);
        if (empty($sales)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $sales
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            $sales = $this->salesModel->create([
                'm_customer_id' => $payload['m_customer_id'],
                'date' => $payload['date'] ?? date('Y-m-d')
            ]);

            $this->insertSalesDetails($payload['product_detail'], $sales->id);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $sales
            ];
        } catch (Throwable $e) {
            $this->rollbackTransaction();
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update(array $payload, string $id): array
    {
        try {
            $this->beginTransaction();
            $sales = $this->salesModel->find($id);
            $sales->update([
                'm_customer_id' => $payload['m_customer_id'],
                'date' => $payload['date'] ?? date('Y-m-d')
            ]);

            $this->updateSalesDetails($payload['product_detail'], $sales->id);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $sales
            ];
        } catch (Throwable $e) {
            $this->rollbackTransaction();
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete(string $id): array
    {
        try {
            $this->beginTransaction();
            $sales = $this->salesModel->find($id);
            if (empty($sales)) {
                return [
                    'status' => false,
                    'message' => 'Sales not found'
                ];
            }
            $this->salesDetailModel->where('t_sales_id', $id)->delete();

            $sales->delete();
            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $id
            ];
        } catch (Throwable $e) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }


    private function insertSalesDetails(array $productDetails, string $salesId): void
    {
        foreach ($productDetails as $productDetail) {
            $this->salesDetailModel->store([
                't_sales_id' => $salesId,
                'm_product_id' => $productDetail['m_product_id'],
                'm_product_detail_id' => $productDetail['m_product_detail_id'],
                'total_item' => $productDetail['total_item'],
                'price' => $productDetail['price']
            ]);
        }
    }

    private function updateSalesDetails(array $productDetails, string $salesId): void
    {
        $checkSalesDetails = $this->salesDetailModel->where('t_sales_id', $salesId)->get();
        $checkId = $checkSalesDetails->pluck('id')->toArray();

        foreach ($productDetails as $productDetail) {
            if (!empty($productDetail['id']) && in_array($productDetail['id'], $checkId)) {
                $this->salesDetailModel->where('id', $productDetail['id'])->update([
                    'm_product_id' => $productDetail['m_product_id'],
                    'm_product_detail_id' => $productDetail['m_product_detail_id'],
                    'total_item' => $productDetail['total_item'],
                    'price' => $productDetail['price']
                ]);
            } else {
                $this->salesDetailModel->create([
                    't_sales_id' => $salesId,
                    'm_product_id' => $productDetail['m_product_id'],
                    'm_product_detail_id' => $productDetail['m_product_detail_id'],
                    'total_item' => $productDetail['total_item'],
                    'price' => $productDetail['price']
                ]);
            }
        }
    }
}
