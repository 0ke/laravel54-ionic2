<?php

namespace App\Http\Controllers\Admin;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Forms\ProductForm;
use Kris\LaravelFormBuilder\FormBuilder;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(5);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $form = \FormBuilder::create(ProductForm::class, [
            'method' => 'POST',
            'url' => route('admin.products.store')
        ]);
        
        $form->add('submit', 'submit', [
            'label' => '<span class="glyphicon glyphicon-floppy-disk"></span>'
        ]);
        
        $title= "Novo Produto";
        
        return view('admin.products.save', compact('form', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(ProductForm::class);
        $formValues = $form->getFieldValues();
        if($formValues['value']){
            $formValues['value'] = str_replace(',', '.', str_replace('.', '', $formValues['value']));
        }
        Product::create($formValues);
        return redirect()->to('admin/products/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
        $product->value = number_format($product->value, 2, ',', '.');
        
        $form = \FormBuilder::create(ProductForm::class, [
            'method' => 'PUT',
            'model' => $product,
            'url' => route('admin.products.update', ['id' => $product->id])
        ]);
        
        $form->add('submit', 'submit', [
            'label' => '<span class="glyphicon glyphicon-floppy-disk"></span>'
        ]);
        
        $title= "Editar Produto";
        
        return view('admin.products.save', compact('form', 'title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(FormBuilder $formBuilder, Product $product)
    {
        $form = $formBuilder->create(ProductForm::class);
        $formValues = $form->getFieldValues();
        if($formValues['value']){
            $formValues['value'] = str_replace(',', '.', str_replace('.', '', $formValues['value']));
        }
        $product->fill($formValues);
        $product->save();
        return redirect()->to('admin/products/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->to('admin/products/');
    }
}
