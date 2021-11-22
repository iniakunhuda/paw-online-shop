<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['trans'] = Transaction::orderBy('date', 'DESC')->get();
        return view('admin.trans.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $trans)
    {
        // return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($category)
    {
        $dt = $this->transModel->getOne(['_id' => $category]);
        if(!$dt) abort(404);

        $data['trans'] = $dt;
        $data['_merchants'] = $this->__getGroupedMerchant();
        return view('admin.trans.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $trans)
    {
        $data = $this->transModel->getOne(['_id' => $trans]);
        $data['status'] = $request->status;

        if($request->notes != "" && $request->notes != null)
            $data['notes'][0] = $request->notes;

        if($request->status == "in_progress") {
            $data['date_history']['bayar'] = \Carbon\Carbon::now()->timestamp;
        }

        if($request->status == "done") {
            $data['date_history']['kirim'] = \Carbon\Carbon::now()->timestamp;
        }

        $this->transModel->save((array) $data);

        alert()->success('Berhasil memperbarui status pesanan', 'Sukses');
        return redirect()->route('admin.trans.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $category)
    {
    }
}
