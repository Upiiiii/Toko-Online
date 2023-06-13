<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::latest()->get();

        return view('pages.admin.product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::all();

        return view('pages.admin.product.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'required|integer',
            'description' => 'required|string',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'slug' => Str::slug($request->name),
            'description' => $request->description
        ]);

        if($product){
            return redirect()->route('dashboard.product.index')->with([
                'success' => 'Data berhasil Ditambahkan'
            ]);
        } else {
            return redirect()->route('dashboard.category.index')->with([
                'error' => 'Data gagal Ditambahkan'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('pages.admin.product.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        //orderBy ASC untuk mengurutkan data bersasarkan alfabet 
        $category = Category::orderBy('name', 'ASC')->get();

        return view('pages.admin.product.edit', compact(
            'product',
            'category'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //buat update data
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'required',
            'price' => 'required|integer|min:1000',
            'description' => 'required|string',
        ]);

        $product = Product::findOrFail($id);
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'slug' => Str::slug($request->name, '-'),
            'description' => $request->description
        ]);

        if($product){
            return redirect()->route('dashboard.product.index')->with([
                'success' => 'Data Berhasil Diupdate'
            ]);
        } else {
            return redirect()->route('dashboard.product.index')->with([
                'error' => 'Data Gagal Diupdate'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Buat hapus data
        $product = Product::findOrFail($id);

        $product->delete();

        if($product){
            return redirect()->route('dashboard.product.index')->with([
                'success' => 'Data Berhasil Dihapus'
            ]);
        } else {
            return redirect()->route('dashboard.product.index')->with([
                'error' => 'Data Gagal Dihapus'
            ]);
        }
    }
}
