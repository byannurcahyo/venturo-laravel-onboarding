<?php

namespace App\Helpers\Product;

use App\Helpers\Venturo;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProductHelper extends Venturo
{
    private $productModel;
    private $productDetailModel;
    const PRODUCT_PHOTO_DIRECTORY = 'foto-produuk';

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    private function uploadGetPayload(array $payload)
    {
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'PRODUCT_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::PRODUCT_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $product = $this->productModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $product,
            'total' => $product->total()
        ];
    }

    public function getById(string $id): array
    {
        $product = $this->productModel->getById($id);
        if (empty($product)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $product
        ];
    }

    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $this ->beginTransaction();
            $product = $this->productModel->store($payload);
            $this->insertUpdateDetail($payload['details'] ?? [], $product->id);
            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $product
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
            $payload = $this->uploadGetPayload($payload);
            $this ->beginTransaction();
            $this->productModel->edit($payload, $id);
            $this->insertUpdateDetail($payload['details'] ?? [], $id);
            $this->deleteDetail($payload['details_deleted'] ?? []);
            $product = $this->getById($id);
            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $product['data']
            ];
        } catch (Throwable $e) {
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
            $this->productModel->drop($id);
            $this->productDetailModel->drop($id);
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

    private function insertUpdateDetail(array $details, string $productId): bool
    {
        if (empty($details)) {
            return false;
        }
        foreach ($details as $detail) {
            if (isset($detail['is_added']) && $detail['is_added']) {
                $detail['m_product_id'] = $productId;
                $this->productDetailModel->store($detail);
            } else {
                $detail['m_product_id'] = $productId;
                $this->productDetailModel->edit($detail, $detail['id']);
            }
        }
        return true;
    }

    private function deleteDetail(array $detailsDeleted): bool
    {
        if (empty($detailsDeleted)) {
            return false;
        }
        foreach ($detailsDeleted as $detail) {
            $this->productDetailModel->drop($detail['id']);
        }
    }

}
