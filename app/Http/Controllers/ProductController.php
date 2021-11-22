<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $req)
    {
        $product = Product::orderBy('rating', 'DESC')->orderBy('updated_at', 'DESC');
        if(isset($req->category) && ($req->category != "")) {
            $cat = $req->category;
            $cat_detail = Category::where('name', $cat)->first();
            if(isset($cat_detail)) {
                $product = $product->where('category', $cat_detail->_id)->orWhere('parent_category', $cat_detail->_id);
            }
        }

        if(isset($req->name) && ($req->name != "")) {
            $product = $product->orWhere('name', 'like', '%'.$req->name.'%');
        }

        $data['categories'] = Category::orderBy('name', 'ASC')->get();
        $data['products'] = $product->paginate(12);
        
        $merchants = $this->__getGroupedMerchant();
        $data['merchants'] = $merchants;
        return view('user.produk.index', $data);
    }

    public function detail(Request $req, $slug) 
    {
        $product = $this->productModel->getOne(['slug' => $slug]);
        if(!$product) abort(404);

        $merchant = $this->merchantModel->getOne(['_id' => $product->merchant ]);
        if(!$merchant) abort(404);

        $data['product'] = $product;
        $data['merchant'] = $merchant;
        return view('user.produk.detail', $data);
    }

    public function review(Request $req, $id_trans, $id_product)
    {
        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_products'] = $this->__getGroupedProduct();
        $data['trans'] = $this->transModel->getOne(['_id' => $id_trans]);
        $data['product_trans'] = $this->transModel->getOne(['products.id_item' => $id_product])['products'][0];
        $data['product'] = $this->productModel->getOne(['_id' => $id_product]);
        $data['merchant'] = $this->merchantModel->getOne(['_id' => $data['product']['merchant']]);
        return view('user.produk.review', $data);
    }

    public function reviewPost(Request $req, $id_trans, $id_product)
    {
        $trans = $this->transModel->getOne(['_id' => $id_trans]);
        $product = $this->productModel->getOne(['_id' => $id_product]);

        $review = [
            'name' => $req->nama,
            'rating' => $req->rating,
            'comment' => $req->review,
            'date' => \Carbon\Carbon::now()->timestamp,
            'id_trans' => $id_trans
        ];
        // add reviews
        $product['reviews'][] = $review;
        $this->productModel->save((array) $product);

        // update rating products
        $rating = 0;
        $count = 0;
        $sum_rating = 0;
        foreach((array) $product['reviews'] as $r) {
            $count += 1;
            $sum_rating += $r['rating'];
        }
        if($sum_rating != 0) {
            $rating = ($sum_rating/$count);
            $product = $this->productModel->getOne(['_id' => $id_product]);
            $product['rating'] = $rating;
            $this->productModel->save((array) $product);
        }
        
        // update trans is_reviewed
        foreach((array) $trans['products'] as $key => $pd) {
            if($pd['id_item'] == $id_product) {
                $trans['product'][$key]['is_reviewed'] = 1;
            }
        }
        $this->transModel->save((array) $trans);


        alert()->success('Berhasil menambah review', 'Sukses');
        return redirect()->route('product.review', [$trans['_id'], $id_product]);
    }
}
