<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Alert;

class CategoryController extends Controller
{

    public function index()
    {
        $data['categories'] = Category::where(['id_parent' => null])->get();
        return view('admin.category.index', $data);
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $data = [
            'name' => $request->name,
            'id_parent' => null
        ];
        $this->categoryModel->save($data);

        
        alert()->success('Berhasil menambah kategori', 'Sukses');
        return redirect()->route('admin.categories.index');
    }

    public function show(Category $category)
    {
        // return view('categories.show', compact('category'));
    }

    public function edit($category)
    {
        $dt = $this->categoryModel->getOne(['_id' => $category]);
        if(!$dt) abort(404);

        $data['category'] = $dt;
        return view('admin.category.edit', $data);
    }

    public function update(Request $request, $category)
    {
        $data = [
            '_id'       => $category,
            'name'      => $request->name,
            'id_parent' => null
        ];
        $this->categoryModel->save($data);

        alert()->success('Berhasil memperbarui kategori', 'Sukses');
        return redirect()->route('admin.categories.index');

    }

    public function destroy(Request $request, $category)
    {
        $this->categoryModel->deleteById($category);

        alert()->success('Berhasil menghapus kategori', 'Sukses');
        return redirect()->route('admin.categories.index');
    }


    public function childIndex($cat)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat]);
        if(!isset($parent)) abort(404);
        $data['parent'] = $parent;
        $data['categories'] = Category::where(['id_parent' => $cat])->get();
        return view('admin.category.child.index', $data);
    }

    public function childCreate($cat)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat]);
        if(!isset($parent)) abort(404);
        $data['parent'] = $parent;

        return view('admin.category.child.create', $data);
    }

    public function childStore(Request $request, $cat)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat]);
        if(!isset($parent)) abort(404);

        $data = [
            'name' => $request->name,
            'urutan' => $request->urutan,
            'id_parent' => $cat
        ];
        $this->categoryModel->save($data);

        
        alert()->success('Berhasil menambah subkategori', 'Sukses');
        return redirect()->route('admin.categories.child.index', [$cat]);
    }

    public function childEdit($cat, $category)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat]);
        if(!isset($parent)) abort(404);
        $data['parent'] = $parent;

        $dt = $this->categoryModel->getOne(['_id' => $category]);
        if(!$dt) abort(404);

        $data['category'] = $dt;
        return view('admin.category.child.edit', $data);
    }

    public function childUpdate(Request $request, $cat, $category)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat]);
        if(!isset($parent)) abort(404);

        $data = [
            '_id'       => $category,
            'name'      => $request->name,
            'urutan' => $request->urutan,
            'id_parent' => $cat
        ];
        $this->categoryModel->save($data);

        alert()->success('Berhasil memperbarui subkategori', 'Sukses');
        return redirect()->route('admin.categories.child.index', [$parent->_id]);

    }

    public function childDestroy(Request $request, $cat_parent, $category)
    {
        $parent = $this->categoryModel->getOne(['_id' => $cat_parent]);
        if(!isset($parent)) abort(404);

        $this->categoryModel->deleteById($category);

        alert()->success('Berhasil menghapus subkategori', 'Sukses');
        return redirect()->route('admin.categories.child.index', [$parent->_id]);
    }

}
