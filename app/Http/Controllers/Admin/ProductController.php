<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $PATH;
    
    public function __construct()
    {
        parent::__construct();
        $this->PATH = config('filesystems.uploads.url_photo');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['merchant'] = $this->__getGroupedMerchant();
        $data['categories'] = $this->__getGroupedCategory();
        $data['products'] = Product::all();
        return view('admin.product.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $merchant = Merchant::orderBy('name', 'ASC')->get();
        $categories = Category::where(['id_parent' => null])->orderBy('name', 'ASC')->get();
        $data['merchants'] = $merchant;
        $data['categories'] = $categories;

        return view('admin.product.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $merchant = $this->merchantModel->getId($request->merchant);
        $data = [
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => [
                'real_price' => (int) $request->price['real_price'],
                'discount_price' => (int) $request->price['discount_price'],
                'merchant_price' => (int) $request->price['merchant_price'],
            ],
            'parent_category' => $request->parent_category,
            'category' => $request->category,
            'merchant' => $request->merchant,
            'note' => $request->note,
            'min_hari' => $request->min_hari,
            'min_order' => $request->min_order,
            'is_active' => 1,
            'desc_long' => $request->desc_long,
            'slug' => $merchant->code.'-'. \Str::slug($request->name),
            'images' => [],
            'rating' => 5,
            'reviews' => [],
            'is_best_seller' => 1
        ];
        $this->productModel->save($data);

        alert()->success('Berhasil menambah produk baru', 'Sukses');
        return redirect()->route('admin.products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $dt = $this->productModel->getOne(['_id' => $product]);
        if(!$dt) abort(404);
        $merchant = Merchant::orderBy('name', 'ASC')->get();
        $categories = Category::where(['id_parent' => null])->orderBy('name', 'ASC')->get();

        $data['product'] = $dt;
        $data['merchants'] = $merchant;
        $data['categories'] = $categories;
        return view('admin.product.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product)
    {
        $product_data = $this->productModel->getId($product); 
        $merchant = $this->merchantModel->getId($request->merchant);
        $data = [
            '_id' => $product,
            'name' => $request->name,
            'desc' => $request->desc,
            'price' => [
                'real_price' => (int) $request->price['real_price'],
                'discount_price' => (int) $request->price['discount_price'],
                'merchant_price' => (int) $request->price['merchant_price'],
            ],
            'parent_category' => $request->parent_category,
            'category' => $request->category,
            'merchant' => $request->merchant,
            'min_order' => $request->min_order,
            'min_hari' => $request->min_hari,
            'note' => $request->note,
            'is_active' => 1,
            'desc_long' => $request->desc_long,
            'slug' => $merchant->code.'-'. \Str::slug($request->name)
        ];

        if(!isset($product_data['rating'])) {
            $data['rating'] = 5;
            $data['reviews'] = [];
            $data['is_best_seller'] = 1;
        }

        if(!isset($product_data['images'])) {
            $data['images'] = [];
        }

        $this->productModel->save($data);

        alert()->success('Berhasil memperbarui produk', 'Sukses');
        return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $product)
    {
        $this->productModel->deleteById($product);
        alert()->success('Berhasil menghapus produk', 'Sukses');
        return redirect()->route('admin.products.index');
    }



    public function photoIndex($product)
    {
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;
        $data['product'] = $product_detail;
        return view('admin.product.photo.index', $data);
    }

    public function photoCreate($product)
    {   
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;
        $data['product'] = $product_detail;
        return view('admin.product.photo.create', $data);
    }


    public function photoEdit($product, $idphoto)
    {
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;

        $photo = null;
        foreach((array) $product_detail['images'] as $img) {
            if($img['id'] == $idphoto) {
                $photo = $img;
            }
        }

        $data['photo'] = $photo;
        return view('admin.product.photo.edit', $data);
    }

    public function photoStore(Request $request, $product)
    {
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $merchant_data = $this->merchantModel->getId($product_detail['merchant']); 

        if($request->hasFile('photo')) {
            $PATH = $this->PATH .$merchant_data->code . '/';
            $imageName = ImageHelper::saveImage($request->photo, $PATH);
            $url = "/".$merchant_data->code.'/'.$imageName;
        } else {
            $url = "";
        }

        $newPhoto = [
            'id' => (string) new \MongoDB\BSON\ObjectId(),
            'url' => $url,
            'title' => $request->judul
        ];
        $product_detail['images'][] = $newPhoto;
        $this->productModel->save((array) $product_detail);

        alert()->success('Berhasil menambah gambar', 'Sukses');
        return redirect()->route('admin.products.photo.index', [$product]);
    }

    public function photoUpdate(Request $request, $product, $idphoto)
    {
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $merchant_data = $this->merchantModel->getId($product_detail['merchant']); 

        foreach((array) $product_detail['images'] as $key => $img) {
            if($img['id'] == $idphoto) {
                $idx = $key;
            }
        }

        if(isset($idx) && (!is_null($idx))){
            if($request->hasFile('photo')) {
                // deleted old photo
                $PATH = $this->PATH . '/';
                ImageHelper::deletePhoto($request->photo_old, $PATH);
                
                $PATH = $this->PATH .$merchant_data->code . '/';
                $imageName = ImageHelper::saveImage($request->photo, $PATH);
                $url = "/".$merchant_data->code.'/'.$imageName;
            } else {
                $url = $request->photo_old;
            }
    
            $product_detail['images'][$idx] = [
                'id' => $product_detail['images'][$idx]['id'],
                'url' => $url,
                'title' => $request->judul
            ];
            $this->productModel->save((array) $product_detail);
        }

        alert()->success('Berhasil memperbarui gambar', 'Sukses');
        return redirect()->route('admin.products.photo.index', [$product]);
    }

    public function photoDestroy($product, $idphoto)
    {
        $product_detail = $this->productModel->getOne(['_id' => $product]);
        $merchant_data = $this->merchantModel->getId($product_detail['merchant']);
        foreach((array) $product_detail['images'] as $key => $img) {
            if($img['id'] == $idphoto) {
                $photo = $img;
                unset($product_detail['images'][$key]);
            }
        }
        if(count($product_detail['images']) > 0) {
            $product_detail['images'] = array_values((array) $product_detail['images']);
        }

        if(($photo != null) && ($photo['url'] !="")) {
            // deleted old photo
            ImageHelper::deletePhoto($photo->url, $this->PATH);
        }
        $this->productModel->save((array) $product_detail);
        alert()->success('Berhasil menghapus gambar', 'Sukses');
        return redirect()->route('admin.products.photo.index', [$product]);
    }
}
