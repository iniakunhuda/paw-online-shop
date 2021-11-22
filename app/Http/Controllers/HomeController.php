<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    
    public function profile()
    {
        $data['user'] = User::find(auth()->user()->_id);
        return view('user.profile', $data);
    }

    public function profile_update_info(Request $req)
    {
        $user = User::find(auth()->user()->_id);
        $user->name = $req->nama;
        $user->whatsapp = $req->whatsapp;
        $user->address = $req->alamat;
        $user->postcode = $req->kodepos;
        $user->province = $req->provinsi;
        $user->city = $req->kota;
        $user->save();

        alert()->success('Berhasil memperbarui profil', 'Success');
        return redirect()->route('profile');
    }

    public function profile_update_login(Request $req)
    {
        $user = User::find(auth()->user()->_id);
        $user->email = $req->email;

        if($req->password != "") {
            if($req->pass_confirm != $req->password) {
                alert()->error('Konfirmasi password tidak sama', 'Error');
                return redirect()->route('profile');
            }
            
            $user->password = Hash::make($req->password);
        }
        $user->save();

        alert()->success('Berhasil memperbarui akun login', 'Success');
        return redirect()->route('profile');
    }

    public function order()
    {
        $data['trans'] = $this->transModel->getAll(['buyer.id' => auth()->user()->_id], null, ['date' => -1]);
        return view('user.order.index', $data);
    }

    public function order_detail($id)
    {
        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_products'] = $this->__getGroupedProduct();
        $data['trans'] = $this->transModel->getOne(['_id' => $id]);
        return view('user.order.detail', $data);
    }

    public function order_cancel($id)
    {
        $trans = $this->transModel->getOne(['_id' => $id]);
        $trans['status'] = "cancel";
        $this->transModel->save((array) $trans);

        alert()->success('Berhasil membatalkan pesanan', 'Success');

        return redirect()->back();
    }

    public function order_wa($id)
    {
        $trans = $this->transModel->getOne(['_id' => $id]);
        $pesan = "*[Konfirmasi Pembayaran Kampung Kue]*

*Detail Transaksi*
Kode Transaksi : {$trans->invno}
Total Bayar: {$trans->price['total']}
Bank: {$trans->payment}

*Biodata Saya*
Nama Lengkap:
Jenis Bank:
Nomor Bank:

Upload Bukti Pembayaran dilampirkan di bawah";
        $url = 'https://wa.me/'.config('website.whatsapp')."?text=".urlencode($pesan);
        // dd($url);
        return redirect(url($url));
    }
}
