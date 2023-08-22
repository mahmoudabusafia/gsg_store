<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Scopes\ActiveStatusScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Product::class);
        $products = Product::withoutGlobalScope(ActiveStatusScope::class)
        //->leftjoin('categories', 'categories.id', 'products.category_id')
        ->with('category.parent')
        ->select([
            'products.*',
          //  'categories.name as category_name'
        ])
        ->Paginate(15); 

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if(!Gate::allows('products.create')) {
        //     abort(403);
        // }

        $this->authorize('create', Product::class);

        $categories = Category::pluck('name', 'id')->toArray();
        return view('admin.products.create',[
            'categories' => $categories,
            'product' => new Product(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $request->merge([
        //     'slug' => Str::slug($request->post('name')),
        // ]);
        // if(!Gate::allows('products.create')) {
        //     abort(403);
        // }
        $this->authorize('create', Product::class);
        
        $request->validate(Product::validateRules()); 

        $product = Product::create( $request->all() );

        return redirect()->route('products.index')
        ->with('success', "Product ($product->name) created.");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) 
    {
        // SELECT * FEOM ratings WHERE rateable_id = 54 AND rateable_type = 'App\Models\Product'
        $product = Product::withoutGlobalScope('active')->findOrFail($id);
        
        return $product->ratings()->dd();
        
        $this->authorize('view', $product);

        return view('admin.products.show', [
            'product' => $product, 
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::withoutGlobalScope('active')->findOrFail($id);
        
        $this->authorize('update', $product);

        return view('admin.products.edit', [
            'product' => $product, 
            'categories' => Category::pluck('name', 'id')->toArray(),
        ]);
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
        $product = Product::withoutGlobalScope('active')->findOrfail($id);

        $this->authorize('update', $product);

        $request->validate( Product::validateRules() );

        if($request->hasFile('image')) {
            $file = $request->file('image');

            $image_path = $file->store('/', [
                'disk' => 'uploads',
            ]);
                                                 // 'filename-' . time() . '.png' ,    عشان اخزن مع الوقت الي ارتفع فيه الملف
            // $image_path = $file->storeAs('/', $file->getClientOriginalName() , [
            //     'disk' => 'uploads',
            // ]);

            $request->merge([
                'image_path' => $image_path,
            ]);
        }

        $product->update( $request->all() ) ;

        return redirect()->route('products.index')
        ->with('success', "Product ($product->name) updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if(!Gate::allows('products.delete')) {
        //     abort(403);
        // }

        // Gate::authorize('products.delete');

        // $user = User::find(1);
        // Gate::forUser($user)->allows('products.delete');

        $product = Product::withoutGlobalScope('active')->findOrfail($id);
        
        $this->authorize('delete', $product);

        $product->delete();

        //Storage::disk('uploads')->delete($product->image_path);

        return redirect()->route('products.index')
        ->with('success', "Product ($product->name) deleted.");
    }

    public function trash()
    {
        $products = Product::withoutGlobalScope('active')->onlyTrashed()->paginate();
        return view('admin.products.trash', [
            'products' => $products,
        ]);
    }

    public function restore(Request $request, $id = null)
    {
        if($id){
        $product = Product::withoutGlobalScope('active')->onlyTrashed()->findOrfail($id);
        $product->restore();

        return redirect()->route('products.index')
        ->with('success', "Product ($product->name) reatored.");
        }

        Product::onlyTrashed()->restore();

        return redirect()->route('products.index')
        ->with('success', "All Trashed Product restored.");
    }

    public function forceDelete($id = null)
    {
        if($id){
        $product = Product::withoutGlobalScope('active')-> onlyTrashed()->findOrfail($id);
        $product->forceDelete();

        return redirect()->route('products.index')
        ->with('success', "Product ($product->name) deleted forever.");
        }

        Product::onlyTrashed()->forceDelete();

        return redirect()->route('products.index')
        ->with('success', "All Trashed Product deleted forever.");
    }
}
