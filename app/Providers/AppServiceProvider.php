<?php

namespace App\Providers;

use App\Models\Config;
use App\Models\Product;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Environment\Environment;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if(App::environment('production')){
            $this->app->bind('path.public', function($app){
                return base_path('public.html');
            });
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
        //
        Validator::extend('filter', function($attribute, $value, $params ){
            foreach ($params as $word){
                if (stripos($value, $word) !== false){
                    return false;
                }
            }
            return true;
        },'Some words are not allowed!');

        Paginator::useBootstrap();
        // Paginator::defaultView('pagination');

        $settings = Cache::get('app-settings');

        if(!$settings){
            // dd($settings);
            $settings = Config::all();
            Cache::put('app-settings', $settings);
        }

        foreach($settings as $config){
            config()->set($config->name, $config->value);
        }


        Relation::morphMap([
            'product' => Product::class,
            'profile' => Profile::class,
        ]);

    }
}
