<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Http\Requests\CategoryRequest;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $entries = Category::with('parent')
        ->withCount('products as count')
        ->simplePaginate(10);

        $success = session()->get('success');

        return view('admin.categories.index', [
            'categories' => $entries,
            'title' => 'Categoires List',
            'success' => $success,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::all();
        $category = new Category();
       return view('admin.categories.create', compact('category', 'parents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255|min:3|unique:categories,name',
            'parent_id' => 'required|int|exists:categories,id',
            'description' => 'nullable|min:5',
            'status' => 'required|in:active,draft',
            'image' => 'image|max:512000|dimensions:min_width=300,min_height=300',
        ];

        // $clean = $request->validate($rules, [
        //     'name.required' =>'Category name is mandatory',
        //     'parent_id.required' =>'The parent is required',
        // ]);
        // $clean = $this->validate($request, $rules);

        // $data = $request->all();
        // $validator = Validator::make($data, $rules);
        // try{
        //     $clean = $validator->validate();
        // }catch (Throwable $e){
        //     // return $validator->failed();
        //     return redirect()->back()->withErrors($validator)
        //     ->withInput();
        // }

        // if ($validator->fails()){
        //     // $errors = $validator->errors();
        //     return redirect()->back()->withErrors($validator);
        // }

        // Request Merage
        $request->merge([
            'slug' => Str::slug($request->name),
            'status' => 'active',
        ]);

        $category = Category::create($request->all());

        // // return array of all form fields
        // $request->all();
        // dd($request->all());

        // // return signle filed value
        // $request->description;
        // $request->input('description');
        // $request->get('description');
        // $request->post('description');
        // $request->query('description'); // ?description=value

        // Method #1
        // $category = new Category();
        // $category->name = $request->post('name');
        // $category->slug = Str::slug($request->post('name'));
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->status = $request->post('status', 'active');
        // $category->save();



        // Method #2  // reqiured fillable on model // mass assignment
// Method #3                mass assignment
        // $category = new Category([
        // 'name' => $request->post('name'),
        // 'slug' => Str::slug($request->post('name')),
        // 'parent_id' => $request->post('parent_id'),
        // 'description' => $request->post('description'),
        // 'status' => $request->post('status', 'active'),
        // ]);
        //     $category->save();

        return redirect()->route('categories.index')->with('success', 'Category created');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category->load([
            'parent',
            'products' => function($query){
                $query->orderBy('price', 'ASC ')->where('status','active');
            }
        ]);
        // SELECT * FROM products WHERE category_id = ? ORDER BY name
        return $category->products()
        ->with('category:id,name,slug')
        ->where('price' , '>', 150)
        ->orderBy('price', 'ASC')
        ->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $category = Category::where('id', '=', $id)->first();
        $category = Category::findOrfail($id);
        if(!$category){
            abort(404);
        }
        $parents = Category::withTrashed()->where('id', '<>', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        // $rules = [
        //     'name' => ['required', 'string', 'max:255', 'min:3',
        //     'unique:categories,id,' . $id,
        //     Rule::unique('categories', 'id')->ignore($id),
        //     (new Unique('categories', 'id'))->ignore($id), // عشان لما اعمل تعديل على الاسم ما يمنعني من انو اعدل

        //     ],
        //     'parent_id' => 'nullable|int|exists:categories,id',
        //     'description' => 'nullable|min:5',
        //     'status' => 'required|in:active,draft',
        //     'image' => 'image|max:512000|dimensions:min_width=300,min_height=300',
        // ];
        // $clean = $request->validate($rules);

        $request->merge([
            'slug' => Str::slug($request->name),
        ]);

        // Mass assignment
        // Category::where('id', '=', $id)->update( $request->all() );
        //
        $category = Category::find($id);

        // Method #1
        // $category->name = $request->post('name');
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->status = $request->post('status');
        // $category->save();

        // Method #2  Mass assignment
        // $category->update( $request->all() );

        $category->update($request->all());

        // Method #3 Mass assignment
        // $category->fill( $request->all() )->save();

        return redirect()->route('categories.index')->with('success', 'Category updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Method #1 ***
        // $category = Category::find($id);
        // $category->delete();

        // Method #2
        Category::destroy($id);

        // Method #3
        // Category::where('id', '=', $id)->delete();

        // session()->put('success', 'Category deleted');
        // session()->flash('success', 'Category deleted');

        return redirect()->route('categories.index')->with('success', 'Category deleted');
    }
}
