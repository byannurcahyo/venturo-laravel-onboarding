<?php

namespace App\Helpers\Product;

use App\Helpers\Venturo;
use App\Models\ProductCategoryModel;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ProductCategoryHelper extends Venturo
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new ProductCategoryModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $categories = $this->categoryModel->getAll($filter, $itemPerPage, $sort);
        return [
            'status' => true,
            'data' => $categories,
            'links' => array_values($categories->getUrlRange(1, $categories->lastPage())),
            'total' => $categories->total()
        ];
    }

    public function getById(string $id): array
    {
        $category = $this->categoryModel->getById($id);
        if (empty($category)) {
            return [
                'status' => false,
                'data' => null
            ];
        }
        return [
            'status' => true,
            'data' => $category
        ];
    }

    public function create(array $payload): array
    {
        try {
            $category = $this->categoryModel->store($payload);
            return [
                'status' => true,
                'data' => $category
            ];
        } catch (Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update(array $payload, string $id): array
    {
        try {
            $this->categoryModel->edit($payload, $id);
            $category = $this->getById($id);
            return [
                'status' => true,
                'data' => $category['data']
            ];
        } catch (Throwable $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->categoryModel->drop($id);
            return true;
        } catch (Throwable $e) {
            return false;
        }
    }
}
