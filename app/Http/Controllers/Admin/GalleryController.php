<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Helper\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Alert;
use App\Http\Requests\PhotoRequest;

class GalleryController extends Controller
{

    private $PATH;
    public function __construct() {
        parent::__construct();
        $this->PATH = config('filesystems.uploads.url_galeri');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['galleries'] = Gallery::all();
        return view('admin.gallery.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'date' => $request->date,
            'images' => []
        ];
        $this->galleryModel->save($data);

        
        alert()->success('Berhasil menambah gallery', 'Sukses');
        return redirect()->route('admin.gallery.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // return view('gallery.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($category)
    {
        $dt = $this->galleryModel->getOne(['_id' => $category]);
        if(!$dt) abort(404);

        $data['gallery'] = $dt;
        return view('admin.gallery.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category)
    {
        $data = [
            '_id'       => $category,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'date' => $request->date
        ];
        $this->galleryModel->save($data);

        alert()->success('Berhasil memperbarui gallery', 'Sukses');
        return redirect()->route('admin.gallery.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $category)
    {
        $this->galleryModel->deleteById($category);

        alert()->success('Berhasil menghapus gallery', 'Sukses');
        return redirect()->route('admin.gallery.index');
    }



    public function photoIndex($product)
    {
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;
        $data['product'] = $product_detail;
        return view('admin.gallery.photo.index', $data);
    }

    public function photoCreate($product)
    {   
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;
        $data['product'] = $product_detail;
        return view('admin.gallery.photo.create', $data);
    }


    public function photoEdit($product, $idphoto)
    {
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);
        $data['photos'] = $product_detail['images'];
        $data['id_product'] = $product;

        $photo = null;
        foreach((array) $product_detail['images'] as $img) {
            if($img['id'] == $idphoto) {
                $photo = $img;
            }
        }

        $data['photo'] = $photo;
        return view('admin.gallery.photo.edit', $data);
    }

    public function photoStore(PhotoRequest $request, $product)
    {
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);

        if($request->hasFile('photo')) {
            $imageName = ImageHelper::saveImage($request->photo, $this->PATH);
            $url = '/'.$imageName;
        } else {
            $url = "";
        }

        $newPhoto = [
            'id' => (string) new \MongoDB\BSON\ObjectId(),
            'url' => $url,
            'title' => $request->judul
        ];
        $product_detail['images'][] = $newPhoto;
        $this->galleryModel->save((array) $product_detail);

        alert()->success('Berhasil menambah gambar', 'Sukses');
        return redirect()->route('admin.gallery.photo.index', [$product]);
    }

    public function photoUpdate(PhotoRequest $request, $product, $idphoto)
    {
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);

        foreach((array) $product_detail['images'] as $key => $img) {
            if($img['id'] == $idphoto) {
                $idx = $key;
            }
        }

        if(isset($idx) && (!is_null($idx))){
            if($request->hasFile('photo')) {
                // deleted old photo
                ImageHelper::deletePhoto($request->photo_old, $this->PATH);

                $imageName = ImageHelper::saveImage($request->photo, $this->PATH);
                $url = '/'.$imageName;

            } else {
                $url = $request->photo_old;
            }
    
            $product_detail['images'][$idx] = [
                'id' => $product_detail['images'][$idx]['id'],
                'url' => $url,
                'title' => $request->judul
            ];
            $this->galleryModel->save((array) $product_detail);
        }

        alert()->success('Berhasil memperbarui gambar', 'Sukses');
        return redirect()->route('admin.gallery.photo.index', [$product]);
    }

    public function photoDestroy($product, $idphoto)
    {
        $product_detail = $this->galleryModel->getOne(['_id' => $product]);
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
            ImageHelper::deletePhoto($photo['url'], $this->PATH);
            \File::deleteDirectory(\Config::get('website.url_galeri').'/'.$photo['url']);
        }
        $this->galleryModel->save((array) $product_detail);
        alert()->success('Berhasil menghapus gambar', 'Sukses');
        return redirect()->route('admin.gallery.photo.index', [$product]);
    }
}
