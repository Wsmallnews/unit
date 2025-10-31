<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Wsmallnews\Category\CategoryPlugin;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;
use Wsmallnews\Category\Filament\Resources\CategoryTypes\BaseResource;
use Wsmallnews\Category\Filament\Pages\Category;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->resources([
                CategoryTypeResource::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                // CategoryPlugin::make()
                //     // Configure CategoryTypeResource
                //     ->forResource(CategoryTypeResource::class)
                //         ->modelLabel('分类类型')
                //         ->navigationLabel('分类类型')
                //         ->navigationGroup('分类管理')
                //         // ->slug('custom-slug')
                //         ->globalSearchResultsLimit(25)
                    
                //     // Configure Category Page
                //     ->forResource(Category::class)
                //         ->modelLabel('分类')
                //         ->navigationLabel('商品分类')
                //         ->navigationGroup('分类管理')
                //         ->customProperties([
                //             'title' => '商品分类aa',
                //             'emptyLabel' => '商品分类数据为空bb',
                //         ])
            ]);
    }
}
