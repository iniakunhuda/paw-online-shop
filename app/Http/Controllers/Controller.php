<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\Counter;
use App\Models\Gallery;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public $productModel, $merchantModel;

    public function __construct()
    {
        $this->blogModel = new Blog;
        $this->productModel = new Product;
        $this->merchantModel = new Merchant;
        $this->categoryModel = new Category;
        $this->galleryModel = new Gallery;
        $this->transModel = new Transaction;
        $this->counterModel = new Counter;
    }

    public function __getGroupedMerchant()
    {
        $merchants = Merchant::orderBy('name', 'ASC')->get()->toArray();
        $_cat = [];
        foreach((array) $merchants as $m) {
            $_merchant[$m['_id']] = $m;
        }

        return $_merchant;
    }

    public function __getGroupedProduct()
    {
        $prods = Product::get()->toArray();
        $_prod = [];
        foreach((array) $prods as $m) {
            $_prod[$m['_id']] = $m;
        }

        return $_prod;
    }

    public function __getGroupedCategory()
    {
        $cats = Category::orderBy('name', 'ASC')->get()->toArray();
        $_cat = [];
        foreach((array) $cats as $m) {
            $_cat[$m['_id']] = $m;
        }

        return $_cat;
    }
}
