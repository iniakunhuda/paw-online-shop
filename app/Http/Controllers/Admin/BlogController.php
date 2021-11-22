<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Merchant;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $PATH;
    
    public function __construct()
    {
        parent::__construct();
        $this->PATH = config('filesystems.uploads.url_blog');
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
        $data['blogs'] = Blog::orderBy('date', 'DESC')->get();
        return view('admin.blog.index', $data);
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

        return view('admin.blog.create', $data);
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
            'title' => $request->judul,
            'desc' => $request->desc,
            'category' => $request->kategori,
            'date' => Carbon::now()->timestamp
        ];

        if($request->hasFile('photo')) {
            $imageName = ImageHelper::saveImage($request->photo, $this->PATH);
            $data['img'] = "/".$imageName;
        } else {
            $data['img'] = null;
        }

        $this->blogModel->save($data);

        alert()->success('Berhasil menambah artikel baru', 'Sukses');
        return redirect()->route('admin.blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($product)
    {
        $dt = $this->blogModel->getOne(['_id' => $product]);
        if(!$dt) abort(404);
        $merchant = Merchant::orderBy('name', 'ASC')->get();

        $data['blog'] = $dt;
        $data['merchants'] = $merchant;
        return view('admin.blog.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $blog)
    {
        $blog_data = $this->blogModel->getId($blog); 
        $merchant = $this->merchantModel->getId($request->merchant);
        $data = [
            '_id' => $blog,
            'title' => $request->judul,
            'desc' => $request->desc,
            'category' => $request->kategori,
            'date' => Carbon::now()->timestamp
        ];

        if($request->hasFile('photo')) {
            // deleted old photo
            ImageHelper::deletePhoto($request->photo_old, $this->PATH);

            $imageName = ImageHelper::saveImage($request->photo, $this->PATH);
            $data['img'] = "/".$imageName;
        } else {
            $data['img'] = $request->photo_old;
        }

        $this->blogModel->save($data);

        alert()->success('Berhasil memperbarui artikel', 'Sukses');
        return redirect()->route('admin.blog.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $blog_id)
    {
        $blog_data = $this->blogModel->getId($blog_id); 
        
        // deleted old photo
        ImageHelper::deletePhoto($blog_data->img, $this->PATH);

        $this->blogModel->deleteById($blog_id);
        alert()->success('Berhasil menghapus artikel', 'Sukses');
        return redirect()->route('admin.blog.index');
    }
}
