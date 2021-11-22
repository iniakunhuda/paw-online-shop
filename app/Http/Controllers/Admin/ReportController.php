<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.report.index');
    }

    public function utama()
    {
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }

        $filter = [
            'date' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$nin'] = ['cancel'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter2['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter2['products.category'] = $_GET['kategori'];
        }

        if(isset($_GET['status']) && ($_GET['status'] != "")) {
            $filter['status'] = $_GET['status'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter2['products.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$unwind'] = '$products';
        if(isset($filter2)) $query[]['$match'] = $filter2;

        $data['trans'] = $this->transModel->aggregate($query);

        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_category'] = $this->__getGroupedCategory();
        $data['cats'] = $this->categoryModel->getAll(['id_parent' => ['$ne' => null]], null, ['name' => 1]);
        $data['start'] = $start;
        $data['end'] = $end;

        // dd($data);
        $agent = new Agent();

        if($agent->isPhone() || $agent->isMobile()) {
            return view('admin.report.utama_mobile', $data);
        } else {
            return view('admin.report.utama', $data);
        }
    }

    public function unitdagang()
    {
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }

        $filter = [
            'date' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$in'] = ['done'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter2['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter2['products.category'] = $_GET['kategori'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter2['products.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$unwind'] = '$products';
        if(isset($filter2)) $query[]['$match'] = $filter2;
        $query[]['$group'] = [
            '_id' => [
                'merchant' => '$products.merchant',
                'id_item' => '$products.id_item'
            ],
            'qty' => ['$sum' => '$products.qty'],
            'price' => ['$sum' => '$products.price'],
            'merchant_price' => ['$first' => '$products.merchant_price'],
            'subtotal' => ['$sum' => '$products.subtotal']
        ];
        $query[]['$project'] = [
            '_id' => false,
            'merchant' => '$_id.merchant',
            'id_item' => '$_id.id_item',
            'qty' => '$qty',
            'subtotal' => '$subtotal',
            'total_produsen' => ['$multiply' => ['$merchant_price', '$qty']]
        ];
        $data_trans = $this->transModel->aggregate($query);

        // sum keuntungan produsen
        foreach((array) $data_trans as $idx => $tr) {
            $data_trans[$idx]['total_paguyuban'] = $tr['subtotal'] - $tr['total_produsen'];
        }

        $_group_by_merchant = [];
        $total_produsen = 0;
        $total_paguyuban = 0;
        if(count($data_trans) > 0) {
            foreach((array) $data_trans as $tr) {
                if (!array_key_exists($tr['merchant'], $_group_by_merchant)) {
                    $_group_by_merchant[$tr['merchant']] = [
                        'merchant' => $tr['merchant'],
                        'total_produsen' => $tr['total_produsen'],
                        'total_paguyuban' => $tr['total_paguyuban'],
                    ];
                } else {
                    $_group_by_merchant[$tr['merchant']]['total_produsen'] += $tr['total_produsen'];
                    $_group_by_merchant[$tr['merchant']]['total_paguyuban'] += $tr['total_paguyuban'];
                }

                $total_produsen += $tr['total_produsen'];
                $total_paguyuban += $tr['total_paguyuban'];
            }
        }
        $data['trans'] = $_group_by_merchant;
        $data['total_produsen'] = $total_produsen;
        $data['total_paguyuban'] = $total_paguyuban;

        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_category'] = $this->__getGroupedCategory();
        $data['cats'] = $this->categoryModel->getAll(['id_parent' => ['$ne' => null]], null, ['name' => 1]);
        $data['start'] = $start;
        $data['end'] = $end;

        // dd($data_trans);
        return view('admin.report.unitdagang', $data);
    }

    public function kategori()
    {
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }

        $filter = [
            'date' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$in'] = ['done'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter2['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter2['products.category'] = $_GET['kategori'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter2['products.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$unwind'] = '$products';
        if(isset($filter2)) $query[]['$match'] = $filter2;
        $query[]['$group'] = [
            '_id' => [
                'category' => '$products.category'
            ],
            'qty' => ['$sum' => '$products.qty'],
            'price' => ['$sum' => '$products.price'],
            'merchant_price' => ['$first' => '$products.merchant_price'],
            'subtotal' => ['$sum' => '$products.subtotal']
        ];
        $query[]['$project'] = [
            '_id' => false,
            'category' => '$_id.category',
            'id_item' => '$_id.id_item',
            'qty' => '$qty',
            'subtotal' => '$subtotal',
            'total_produsen' => ['$multiply' => ['$merchant_price', '$qty']]
        ];
        $data_trans = $this->transModel->aggregate($query);
        
        // sum keuntungan produsen
        foreach((array) $data_trans as $idx => $tr) {
            $data_trans[$idx]['total_paguyuban'] = $tr['subtotal'] - $tr['total_produsen'];
        }
        
        $total_produsen = 0;
        $total_paguyuban = 0;
        if(count($data_trans) > 0) {
            foreach((array) $data_trans as $tr) {
                $total_produsen += $tr['total_produsen'];
                $total_paguyuban += $tr['total_paguyuban'];
            }
        }

        $data['trans'] = $data_trans;
        $data['total_produsen'] = $total_produsen;
        $data['total_paguyuban'] = $total_paguyuban;
        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_category'] = $this->__getGroupedCategory();
        $data['cats'] = $this->categoryModel->getAll(['id_parent' => ['$ne' => null]], null, ['name' => 1]);
        $data['start'] = $start;
        $data['end'] = $end;

        return view('admin.report.kategori', $data);
    }

    public function kue()
    {
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }

        $filter = [
            'date' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$in'] = ['done'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter2['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter2['products.category'] = $_GET['kategori'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter2['products.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$unwind'] = '$products';
        if(isset($filter2)) $query[]['$match'] = $filter2;
        $query[]['$group'] = [
            '_id' => [
                'merchant' => '$products.merchant',
                'id_item' => '$products.id_item'
            ],
            'qty' => ['$sum' => '$products.qty'],
            'price' => ['$sum' => '$products.price'],
            'merchant_price' => ['$first' => '$products.merchant_price'],
            'subtotal' => ['$sum' => '$products.subtotal']
        ];
        $query[]['$project'] = [
            '_id' => false,
            'merchant' => '$_id.merchant',
            'id_item' => '$_id.id_item',
            'qty' => '$qty',
            'subtotal' => '$subtotal',
            'total_produsen' => ['$multiply' => ['$merchant_price', '$qty']]
        ];
        $data_trans = $this->transModel->aggregate($query);

        // sum keuntungan produsen
        $total_produsen = 0;
        $total_paguyuban = 0;
        foreach((array) $data_trans as $idx => $tr) {
            $data_trans[$idx]['total_paguyuban'] = $tr['subtotal'] - $tr['total_produsen'];

            $total_produsen += $tr['total_produsen'];
            $total_paguyuban += $data_trans[$idx]['total_paguyuban'];
        }

        $data['trans'] = $data_trans;
        $data['total_produsen'] = $total_produsen;
        $data['total_paguyuban'] = $total_paguyuban;

        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['_items'] = $this->__getGroupedProduct();
        $data['_category'] = $this->__getGroupedCategory();
        $data['cats'] = $this->categoryModel->getAll(['id_parent' => ['$ne' => null]], null, ['urutan' => 1]);
        $data['start'] = $start;
        $data['end'] = $end;

        return view('admin.report.kue', $data);
    }

    public function pesanan(){
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }


        $filter = [
            'delivery_time' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$in'] = ['pending'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter['status'] = $_GET['kategori'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter['buyer.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$project'] = [
            '_id' => true,
            'tgl_pesan' => '$date_history.pesan',
            'delivery_time' => '$delivery_time',
            'invno' => '$invno',
            'name' => '$buyer.name',
            'whatsapp' => '$buyer.whatsapp',
            'total_bayar' => '$price.total',
            'status' => '$status'
        ];

        $data_trans = $this->transModel->aggregate($query);

        $data['_merchants'] = $this->__getGroupedMerchant();
        $data['cats'] = $this->__getGroupedCategory();
        $data['trans'] = $data_trans;
        $data['start'] = $start;
        $data['end'] = $end;

        return view('admin.report.pesanan', $data);
        // dd($query, $data);
    }

    public function konsumen(){
        if(isset($_GET['awal'])) {
            $start = strtotime($_GET['awal']);
        } else {
            $start = strtotime(date('01-m-Y'));
        }

        if(isset($_GET['akhir'])) {
            $end = strtotime($_GET['akhir']);
        } else {
            $end = strtotime(date('t-m-Y'));
        }

        $filter = [
            'date' => [
                '$gte' => (int) $start,
                '$lte' => (int) $end
            ]
        ];
        $filter['status']['$in'] = ['done'];

        if(isset($_GET['produsen']) && ($_GET['produsen'] != "")) {
            $filter['products.merchant'] = $_GET['produsen'];
        }

        if(isset($_GET['kategori']) && ($_GET['kategori'] != "")) {
            $filter['status'] = $_GET['kategori'];
        }

        if(isset($_GET['nama']) && ($_GET['nama'] != "")) {
            $filter['buyer.name'] = new \MongoDB\BSON\Regex($_GET['nama']);
        }

        $query[]['$match'] = $filter;
        $query[]['$unwind'] = '$buyer';
        $query[]['$group'] = [
            '_id' => [
                'name' => '$buyer.name',
                'id' => '$buyer.id',
                'whatsapp' => '$buyer.whatsapp'
            ],
            'subtotal' => ['$sum' => '$price.total']
        ];
        $query[]['$project'] = [
            '_id' => false,
            'name' => '$_id.name',
            'whatsapp' => '$_id.whatsapp',
            'id' => '$_id.id',
            'total' => '$subtotal'
        ];
        $data_trans = $this->transModel->aggregate($query);

        $data['trans'] = $data_trans;
        $data['start'] = $start;
        $data['end'] = $end;

        // dd($filter, $data);
        return view('admin.report.konsumen', $data);
    }


    public function detil($id){
        if(!isset($id)){
            return redirect('admin.report.konsumen');
        }

        $filter['buyer.id'] = $id;
        $filter['status'] = 'done';

        $query[]['$match'] = $filter;
        $query[]['$project'] = [
            'tgl_transaksi' => '$date_history.pesan',
            'invno' => '$invno',
            'produk' => '$products',
            'total_bayar' => '$price.total',
            'nama' => '$buyer.name',
            'whatsapp' => '$buyer.whatsapp',
            'alamat' => '$buyer.address',
        ];

        $data_trans = $this->transModel->aggregate($query);
        $data['trans'] = $data_trans;

        return view('admin.report.detil_transaksi', $data);
    }
}
