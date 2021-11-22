<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all = 0;
        $in_progress = 0;
        $pending = 0;
        $done = 0;
        $cancel = 0;

        $trans = Transaction::get();
        if(isset($trans) && count($trans) > 0) {
            foreach($trans as $tr) {
                if($tr['status'] == 'pending') $pending += $tr['price']['total'];
                if($tr['status'] == 'done') $done += $tr['price']['total'];
                if($tr['status'] == 'cancel') $cancel += $tr['price']['total'];
                if($tr['status'] == 'in_progress') $in_progress += $tr['price']['total'];
    
                if($tr['status'] == 'pending' || $tr['status'] == 'done' || $tr['status'] == 'in_progress') $all += $tr['price']['total'];
            }
        }

        $trans = Transaction::where('status', 'pending')->get();

        $data['all'] = $all;
        $data['in_progress'] = $in_progress;
        $data['cancel'] = $cancel;
        $data['trans'] = $trans;
        $data['done'] = $done;
        $data['pending'] = $pending;
        return view('admin.home', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    public function settingIndex()
    {
        $id = auth()->guard('admin')->user()->id;
        $data['akun'] = Admin::where(['_id' => $id])->first();
        return view('admin.setting.index', $data);
    }

    public function settingPost(Request $req)
    {
        $id = auth()->guard('admin')->user()->id;
        $user = Admin::where(['_id' => $id])->first();

        $user['telp'] = $req->telp;
        if($req->password != "") {
            $user['password'] = Hash::make($user['password']);
        }

        $user->save();

        alert()->success('Berhasil memperbarui akun', 'Sukses');
        return redirect()->route('admin.setting');
    }
}
