<?php

namespace App\Providers;

use App\Repositories\Contracts\ImagesRepositoryContract;
use App\Repositories\Contracts\OrderRepositoryContract;
use App\Repositories\Contracts\PaypalServiceContract;
use App\Repositories\Contracts\ProductsRepositoryContract;
use App\Repositories\ImagesRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaypalService;
use App\Repositories\ProductsRepository;
use App\Services\Contracts\FileServiceContract;
use App\Services\FileService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductsRepositoryContract::class => ProductsRepository::class,
        ImagesRepositoryContract::class => ImagesRepository::class,
        FileServiceContract::class => FileService::class,
        OrderRepositoryContract::class => OrderRepository::class,
        PaypalServiceContract::class => PaypalService::class,
    ];
    
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**q
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
