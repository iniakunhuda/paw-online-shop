<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class IndexController extends Controller
{

    // $data = [
    //     'name' => 'Superadmin',
    //     'telp' => '08111',
    //     'otp' => '12345',
    //     'password' => Hash::make('admin123456'),
    //     'otp_inactive' => null,
    //     'role' => 'superadmin',
    //     'last_login' => new \MongoDB\BSON\UTCDateTime(new \DateTime())
    // ];

    // $admin = new Admin;
    // $admin->save($data);

    public function index() 
    {
        $data['merchants'] = Merchant::limit(5)->get();
        return view('index', $data);
    }

    public function panduan()
    {
        return view('panduan');
    }

    public function cartView()
    {
        $product = Product::with('merchant')->where(['slug' => 'almond-crispy-red-velvet'])->first();
        $carts = (Session::has('cart')) ? Session::get('cart') : [];
        $data['carts'] = (count($carts) > 0) ? array_values($carts) : [];

        $data['isLoggedIn'] = \Auth::check();
        return view('user.cart.view', $data);
    }

    public function cartCheckout()
    {
        $product = Product::with('merchant')->where(['slug' => 'almond-crispy-red-velvet'])->first();
        $carts = (Session::has('cart')) ? Session::get('cart') : [];
        $data['carts'] = (count($carts) > 0) ? array_values($carts) : [];
        $data['_items'] = $this->__getGroupedProduct();


        if(count($carts) < 1) return abort(404);

        return view('user.cart.checkout', $data);
    }

    public function tentangKami() 
    {
        return view('about');
    }

    public function kontak() 
    {
        return view('contact');
    }

    public function checkout(Request $req)
    {
        $carts = Session::get('cart');
        $_items = $this->__getGroupedProduct();
        $cart_arr = (array) array_values($carts);

        $buyer = [
            'id' => (string) auth()->user()->id,
            'name' => $req->nama,
            'address' => $req->alamat,
            'whatsapp' => $req->whatsapp,
            'province' => $req->provinsi,
            'city' => $req->kota,
            'ip' => request()->ip(),
            'user_agent' => $this->_getUserAgent()
        ];

        $merchants = [];
        $_merchants = [];
        foreach((array) $carts as $id => $ct) {
            $_merchants[$ct['merchant']['_id']] = $ct['merchant'];
        }
        foreach((array) $_merchants as $id => $m) {
            $merchants[] = [
                'id_merchant' => $m['_id'],
                'name' => $m['name'],
                'whatsapp' => $m['whatsapp'],
            ];
        }

        $isTglKirimSalah = false;
        $itemsTglKirimSalah = [];

        $total = 0;
        $products = [];
        foreach((array) $carts as $c) {
            $total += $c['total'];

            $items = [
                'id_item' => $c['id'],
                'name' => $c['title'],
                'qty' => (int) $c['qty'],
                'price' => (int) $c['price'],
                'note' => isset($c['note']) ? $c['note'] : "",
                'pict' => $c['image'],
                'merchant' => $c['merchant']['_id'],
                'category' => $_items[$c['id']]['category'],
                'parent_category' => $_items[$c['id']]['parent_category'],
                'merchant_price' => (int) $_items[$c['id']]['price']['merchant_price'],
                'subtotal' => (int) $c['total']
            ];

            $products[] = $items;

            $now = Carbon::now()->format('Y-m-d');
            $diff_day = (new Carbon($req->send_date))->diff($now)->days;
            $min_order_date = (int) $_items[$c['id']]['min_hari'];
            if($diff_day < $min_order_date) {
                $isTglKirimSalah = true;
                $items['min_hari'] = $min_order_date;
                $itemsTglKirimSalah[] = $items;
            }

        }

        if($isTglKirimSalah) {
            $listItem = "<ul>";
            foreach($itemsTglKirimSalah as $item) {
                $listItem .= "<li style='list-style-type:none'><b>" . $item['name'] . " (H-" . $item['min_hari'] . " hari)" . "</b></li>";
            }
            $listItem .= "</ul>";

            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Gagal! Terdapat item yang tidak dapat dibeli sesuai tanggal pengiriman, antara lain: <br><br>' . $listItem
            ]);
        }

        $price = [
            'subtotal' => $total,
            'shipping' => 0,
            'discount' => 0,
            'total'    => $total
        ];

        $shipping = [
            'method' => $req->metode_pengiriman,
            'price' => ''
        ];

        $counter = $this->counterModel->getOne(['code' => 'ORDER']);
        if(is_null($counter)) $counter['total'] = 0;
        else $counter['total'] += 1;

        $trans = [
            '_id' => (string) new \MongoDB\BSON\ObjectID(),
            'invno' => "KK" . date('Y') . str_pad($counter['total'], 4, '0', STR_PAD_LEFT), 
            'buyer' => $buyer, 
            'merchants' => $merchants,
            'products' => $products,
            'payment' => $req->payment,
            'price' => $price,
            'shipping' => $shipping,
            'notes' => [],
            'delivery_time' => \Carbon\Carbon::parse($req->send_date)->timestamp,
            'status' => 'pending',
            'date' => \Carbon\Carbon::now()->timestamp,
            'date_history' => [
                'pesan' => \Carbon\Carbon::now()->timestamp,
                'bayar' => null,
                'kirim' => null
            ]
        ];

        $save = $this->transModel->Save($trans);
        $this->counterModel->save((array) $counter);

        if($save) {
            Session::forget('cart');
            return response()->json([
                'status' => 200,
                'data' => $trans,
                'message' => 'Sukses membuat pesanan baru!'
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'data' => [],
                'message' => 'Gagal! Silahkan submit ulang'
            ]);
        }
    }
    
    public function blogIndex()
    {
        $data['blogs'] = Blog::orderBy('date', 'DESC')->get();
        return view('user.blog.index', $data);
    }
    
    public function blogDetail($id)
    {
        $blog = $this->blogModel->getOne(['_id' => $id]);
        if(!$blog) abort(404);
        
        $data['data'] = $blog;
        return view('user.blog.detail', $data);
    }
    

    private function _getUserAgent()
    {
        $user_agent = \request()->header('User-Agent');
        $bname = 'Unknown';
        $platform = 'Unknown';

        //First get the platform?
        if (preg_match('/linux/i', $user_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = 'mac';
        }
        elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = 'windows';
        }


        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$user_agent) && !preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$user_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$user_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$user_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$user_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$user_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        } else {
            $bname = "Don't Know";
            $ub = "Don't Know";
        }

        return $bname;
    }

}
