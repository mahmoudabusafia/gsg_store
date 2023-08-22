<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Product;

class LatestProducts extends Component
{
    public $products; 

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($count = 10)
    {
        $this->products = Product::latest()->active()->price(200, 500)->limit($count)->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.latest-products');
    }
}
