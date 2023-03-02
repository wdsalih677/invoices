<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\section;
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
        $sections = section::get();
        $products = Product::get();
        return view('products.index',compact('sections','products'));
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
        // $validated = $request->validate([
        //     'product_name' => 'required|unique:sections|max:255',
        //     'description' => 'required',
        //     'section_id' => 'required',
        // ],[
        //     'product_name.required' =>'اسم المنتج مطلوب',
        //     'product_name.unique' =>'اسم المنتج مكرر',
        //     'description.required'=>'الملاحظات مطلوبه',
        //     'section_id.required'=>'يجب اختيار القسم'
        // ]);
        Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        session()->flash('success','تم إضافة المنتج بنجاح ');
        return redirect('products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $id = $request->id;

        $this->validate($request, [
            'product_name' => 'required|max:255|unique:products,product_name,'.$id,
            'section_id' => 'required',
            'description' => 'required',
        ],[
            'product_name.required' =>'اسم المنتج مطلوب',
            'product_name.unique' =>'اسم المنتج مكرر',
            'section_id.required'=>'يجب تحدبد القسم',
            'description.required'=>'الوصف مطلوب'
        ]);

        $sections = Product::find($id);
        $sections->update([
            'product_name' => $request->product_name,
            'section_id' => $request->section_id,
            'description' => $request->description
        ]);

        session()->flash('edit','تم تعديل المنتج بنجاح ');
        return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Product::find($id)->delete();
        session()->flash('delete','تم حذف المنتج بنجاح ');
        return redirect('products');
    }
}
