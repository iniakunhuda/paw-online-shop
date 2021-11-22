<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ImageHelper;
use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Illuminate\Http\Request;

class MerchantController extends Controller
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
        $data['merchants'] = Merchant::all();

        return view('admin.merchant.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.merchant.create');
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
            'name' => $request->name,
            'code' => $request->code,
            'whatsapp' => $request->whatsapp,
            'profile' => $request->profile,
            'owner_name' => $request->owner_name,
            'owner_address' => $request->owner_address,
            'owner_location' => $request->owner_location,
            'banner' => $request->banner,
            'open_hour' => $request->open_hour,
            'specialist' => $request->specialist,
            'rating' => 0,
            'social_media' => [
                'fb' => ($request->fb) ? $request->fb : "",
                'twitter' => ($request->twitter) ? $request->twitter : "",
                'ig' => ($request->ig) ? $request->ig : "",
                'yt' => ($request->yt) ? $request->yt : "",
            ]
        ];
        $code = $request->code;

        $PATH = $this->PATH .$code .'/';
        if($request->hasFile('banner')) {
            $imageName = ImageHelper::saveImage($request->banner, $PATH);
            $data['banner'] = "/".$code.'/'.$imageName;
        }
        if($request->hasFile('photo')) {
            $imageName = ImageHelper::saveImage($request->photo, $PATH);
            $data['photo'] = "/".$code.'/'.$imageName;
        }

        $this->merchantModel->save($data);

        alert()->success('Berhasil menambah unit dagang', 'Sukses');
        return redirect()->route('admin.merchants.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function show(Merchant $merchant)
    {
        // return view('merchants.show', compact('merchant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function edit($merchant)
    {
        $dt = $this->merchantModel->getOne(['_id' => $merchant]);
        if(!$dt) abort(404);

        $data['merchant'] = $dt;
        return view('admin.merchant.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $merchant)
    {
        $merchant_data = $this->merchantModel->getId($merchant); 
        $data = [
            '_id'  => $merchant_data->_id,
            'name' => $request->name,
            'code' => $request->code,
            'whatsapp' => $request->whatsapp,
            'profile' => $request->profile,
            'owner_name' => $request->owner_name,
            'owner_address' => $request->owner_address,
            // 'owner_location' => $request->owner_location,
            'open_hour' => $request->open_hour,
            'specialist' => $request->specialist,
            'social_media' => [
                'fb' => ($request->fb) ? $request->fb : "",
                'twitter' => ($request->twitter) ? $request->twitter : "",
                'ig' => ($request->ig) ? $request->ig : "",
                'yt' => ($request->yt) ? $request->yt : "",
            ]
        ];

        if($request->hasFile('banner')) {
            // deleted old photo
            $PATH = $this->PATH . '/';
            ImageHelper::deletePhoto($request->banner_old, $PATH);

            $PATH = $this->PATH .$merchant_data->code . '/';
            $imageName = ImageHelper::saveImage($request->banner, $PATH);
            $data['banner'] = "/".$merchant_data->code.'/'.$imageName;
        } else {
            $data['banner'] = $request->banner_old;
        }

        if($request->hasFile('photo')) {
            // deleted old photo
            $PATH = $this->PATH . '/';
            ImageHelper::deletePhoto($request->photo_old, $PATH);

            $PATH = $this->PATH .$merchant_data->code . '/';
            $imageName = ImageHelper::saveImage($request->photo, $PATH);
            $data['photo'] = "/".$merchant_data->code.'/'.$imageName;
        } else {
            $data['photo'] = $request->photo_old;
        }

        $this->merchantModel->save($data);

        alert()->success('Berhasil memperbarui unit dagang', 'Sukses');
        return redirect()->route('admin.merchants.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Merchant  $merchant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $merchant)
    {
        $dt = $this->merchantModel->getId($merchant);
        \File::deleteDirectory($this->PATH.'/'.$dt->code);
        $this->merchantModel->deleteById($merchant);
        alert()->success('Berhasil menghapus unit dagang', 'Sukses');
        return redirect()->route('admin.merchants.index');
    }
}
