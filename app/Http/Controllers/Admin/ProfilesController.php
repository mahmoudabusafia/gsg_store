<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{
    
    public function show(Profile $profile) 
    {
        // SELECT * FEOM ratings WHERE rateable_id = 54 AND rateable_type = 'App\Models\Product'
        // $profile = Profile::withoutGlobalScope('active')->findOrFail($id);
        
        return $profile->ratings;
        
        $this->authorize('view', $profile);

        return view('admin.products.show', [
            'product' => $profile, 
        ]);
    }

}
