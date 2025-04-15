<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 注册模型别名
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'test_image' => 'App\Models\TestImage',
        ]);
    }
}
