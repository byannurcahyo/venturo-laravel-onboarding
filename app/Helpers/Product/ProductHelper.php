<?php

namespace App\Helpers\Product;

use Throwable;
use App\Helpers\Venturo;
use App\Models\ProductModel;
use App\Models\ProductDetailModel;
use Illuminate\Support\Facades\Hash;

class ProductHelper extends Venturo
{
    private $productModel;
    private $productDetailModel;
    const PRODUCT_PHOTO_DIRECTORY = 'foto-produk';

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productDetailModel = new ProductDetailModel();
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
            $product = $this->productModel->create([
                'm_product_category_id' => $payload['m_product_category_id'],
                'name' => $payload['name'],
                'price' => $payload['price'],
                'description' => $payload['description'],
                'is_available' => $payload['is_available'],
                'photo' => $payload['photo'] ?? null,
            ]);

            $this->insertProductDetail($payload['detail'], $product->id);

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
            $product = $this->productModel->find($id);
            $product->update([
                'm_product_category_id' => $payload['m_product_category_id'],
                'name' => $payload['name'],
                'price' => $payload['price'],
                'description' => $payload['description'],
                'is_available' => $payload['is_available'],
                'photo' => $payload['photo'] ?? null,
            ]);

            $this->updateProductDetail($payload['detail'], $product->id);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $product
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
            $product = $this->productModel->find($id);
            if (empty($product)) {
                return [
                    'status' => false,
                    'message' => 'Product not found'
                ];
            }
            $this->productDetailModel->where('m_product_id', $id)->delete();

            $product->delete();
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

    private function insertProductDetail(array $details, string $productId): void
    {
        foreach ($details as $detail) {
            $this->productDetailModel->store([
                'm_product_id' => $productId,
                'type' => $detail['type'],
                'description' => $detail['description'],
                'price' => $detail['price']
            ]);
        }
    }

    private function updateProductDetail(array $details, string $productId): void
    {
        $checkProductDetail = $this->productDetailModel->where('m_product_id', $productId)->get();
        $checkId = $checkProductDetail->pluck('id')->toArray();

        foreach ($details as $detail) {
            if (!empty($detail['id']) && in_array($detail['id'], $checkId)) {
                $this->productDetailModel->where('id', $detail['id'])->update([
                    'type' => $detail['type'],
                    'description' => $detail['description'],
                    'price' => $detail['price']
                ]);
            } else {
                $this->productDetailModel->create([
                    'm_product_id' => $productId,
                    'type' => $detail['type'],
                    'description' => $detail['description'],
                    'price' => $detail['price']
                ]);
            }
        }
    }
}
