<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_subcategory($parent_category)
    {
        $parent = $this->categoryModel->getOne(['_id' => $parent_category]);
        if(!isset($parent)) {
            return response()->json([
                'status' => 404,
                'message' => 'Kategori tidak ditemukan'
            ]);
        }

        $categories = $this->categoryModel->getAll(['id_parent' => $parent->_id]);
        return response()->json([
            'status' => 200,
            'data' => $categories,
            'message' => 'Subcategory berhasil diambil'
        ]);
    }
}
