<?php

namespace App\Models;

use App\Scopes\ActiveStatusScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// use NumberFormatter;

class Product extends Model
{
    const STATUS_ACTIVE = 'active';
    const STATUS_DRAFT = 'draft';
    
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'category_id', 'description', 'image_path', 'price', 'sale_price',
        'quantity', 'width', 'height', 'length', 'weight', 'status'
    ];

    protected $perPage = 20;

    protected $appends = [
        'image_url',
        'formatted_price',
        'permalink',
    ];

    protected static function booted()
    {
        static::creating(function(Product $product){
            $slug = Str::slug($product->name);
            $count = Product::where('slug', 'LIKE', '{$slug}%')->count();

            if($count){
                $slug .= '-' . ( $count + 1 );
            }
            $product->slug = $slug;
        });
        // static::addGlobalScope(new ActiveStatusScope());
        // static::addGlobalScope('active', function(Builder $builder){
        //     $builder->where('products.status', '=', 'active');
        // });
        // static::addGlobalScope('owner', function(Builder $builder){
        //     $user = Auth::user();
        //     if($user && $user->type == 'store') {
        //         $builder->where('products.user_id', '=', $user->id);
        //     }
        // });
    }

    public function scopeActive(Builder $bluider)
    {
        $bluider->where('status', '=', 'active');
    }
    public function scopePrice(Builder $bluider, $from, $to)
    {
        $bluider->where('price', '>=', $from)
            ->where('price', '<=', $to);
    }

    public static function validateRules()
    {
        return [
            'name' => 'required|max:255',
            'category_id' => 'required|int|exists:categories,id',
            'description' => 'nullable',
            'image' => 'nullable|image|dimensions:min_width=200,min_height=200',
            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|int|min:0',
            'sku' => 'nullable|unique:products,sku',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'status'=> 'in:' . self::STATUS_ACTIVE .', ' . self::STATUS_DRAFT,
        ];
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image_path) {
            return asset('images/placeholder.png');
        }
        
        if (stripos($this->image_path, 'http') === 0) {
            return $this->image_path;
        }
        return asset('uploads/' . $this->image_path);
    }

    //Mutators

    public function setNameAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
        $this->attributes['name'] = Str::title($value);
    }

    public function getFormattedPriceAttribute()
    {
        // $formatter = new NumberFormatter(App::getLocale(), NumberFormatter::CURRENCY);
        // return $formatter->formatCurrency($this->price, 'EUR');
        return $this->price;
    }

    public function getPermalinkAttribute()
    {
        return route('product.details', $this->slug);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')
            ->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')
            ->withDefault();
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable', 'rateable_type', 'rateable_id', 'id');
    }
}
