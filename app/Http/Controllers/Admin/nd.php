// Select categories.* , parents.name from categories LEFT JOIN categories as parents ON
// parent.id = categories.parent_id where status = 'active' ORDER BY created_at DESC, name ASC
// return collection of Category model object
// SELECT id, name FROM categories where status = 'active'

$entries = Category::with('parent')
->withCount('products as count')
/*->has('parent')
->whereHas('products', function($query) {
$query->where('price', '<', 200);
})*/
->simplePaginate(10);

/*
$entries = Category::leftjoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
->Select([
'categories.*',
'parents.name as parent_name'
])
//->where('categories.status', '=', 'active')
->orderBy('categories.created_at', 'DESC')
->orderBy('categories.name', 'ASC')
->withTrashed()
->paginate(15);
*/

// return collection of sdtObj object
// $entries1 = DB::table('categories')
// ->where('status', '=', 'active')
// ->orderBy('created_at', 'DESC')
// ->orderBy('name', 'ASC')
// ->get();

$success = session()->get('success');
// session()->forget('success');

return view('admin.categories.index', [
'categories' => $entries,
'title' => 'Categoires List',
'success' => $success,
]);
