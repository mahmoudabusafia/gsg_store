<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\returnSelf;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //  Gate::authorize('roles.view-any');
        $this->authorize('view-any', Role::class);

        $roles = Role::paginate();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //  Gate::authorize('roles.create');
        $this->authorize('create', Role::class);

        return view('admin.roles.create', [
            'role' => new Role(),
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
        //  Gate::authorize('roles.create');
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => 'required',
            'abilities' => 'required|array',
        ]);
        // dd($request->all());

        $role = Role::create($request->all());
        
        return redirect()->route('roles.index')->with('success' , 'Role added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        dd($role->users);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //  Gate::authorize('roles.update');
        $role = Role::findOrFail($id);
        $this->authorize('update', $role);
        
        return view('admin.roles.edit', compact('role'));
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
        //  Gate::authorize('roles.update');
        

        $request->validate([
            'name' => 'required',
            'abilities' => 'required|array',
        ]);

        $role = Role::findOrFail($id);
        $this->authorize('update', $role);

        $role->update($request->all());
        
        return redirect()->route('roles.index')->with('success' , 'Role updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //  Gate::authorize('roles.delete');
       
        $role = Role::findOrFail($id);
        $this->authorize('delete', $role);

        $role->delete();
        
        return redirect()->route('roles.index')->with('success' , 'Role deleted');
    }
}
