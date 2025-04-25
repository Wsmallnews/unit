<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

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

            'sky_faq' => \LaraZeus\Sky\Models\Faq::class,
            'sky_post' => \LaraZeus\Sky\Models\Post::class,
            'sky_post_status' => \LaraZeus\Sky\Models\PostStatus::class,
            'sky_tag' => \LaraZeus\Sky\Models\Tag::class,
            'sky_library' => \LaraZeus\Sky\Models\Library::class,
            'sky_navigation' => \LaraZeus\Sky\Models\Navigation::class,
        ]);


        // 仅在特定环境（如本地）启用
        if (config('app.env') === 'local') {
            Event::listen(QueryExecuted::class, function (QueryExecuted $query) {
                // 处理 SQL 和绑定参数
                $sql = str_replace(['?'], ["%s"], $query->sql);
                $bindings = array_map(function ($value) {
                    if ($value instanceof \DateTime) {
                        return $value->format('Y-m-d H:i:s');
                    }
                    return is_string($value) ? "'$value'" : $value;
                }, $query->bindings);
                $fullSql = vsprintf($sql, $bindings);

                // 记录到日志
                Log::channel('sql')->debug("Time: {$query->time}ms | SQL: {$fullSql}");
            });
        }
    }
}
