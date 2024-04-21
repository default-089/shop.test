<?php

namespace App\Providers;

use App\Contracts\OrderServiceInterface;
use App\Database\SqlServerConnection;
use App\Logging\FacebookApiLogger;
use App\Notifications\ChannelManagerWithLimits;
use App\Policies\RolePolicy;
use App\Services\Api\Facebook\ConversionsApiService;
use App\Services\OrderService;
use FacebookAds\Api;
use Illuminate\Database\Connection;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        OrderServiceInterface::class => OrderService::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ChannelManager::class, function ($app) {
            return new ChannelManagerWithLimits($app);
        });

        $this->app->singleton(ConversionsApiService::class, function () {
            $pixelId = config('services.facebook.pixel_id');
            $api = Api::init(null, null, config('services.facebook.token'));
            // $logger = new FacebookApiLogger(fopen(config('services.facebook.log_file'), 'a'));
            // $api->setLogger($logger->setJsonPrettyPrint(true));

            return new ConversionsApiService($api, $pixelId);
        });

        Connection::resolverFor('sqlsrv', function ($connection, $database, $prefix, $config) {
            return (new SqlServerConnection($connection, $database, $prefix, $config))->createTunnel();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @var \Illuminate\Foundation\Application */
        $app = $this->app;

        Paginator::useBootstrap();

        setlocale(LC_TIME, 'ru_RU.UTF-8');
        Carbon::setLocale(config('app.locale'));

        if ($app->isProduction()) {
            $app['request']->server->set('HTTPS', 'on');
        }

        if ($app->isLocal()) {
            $app['config']['filesystems.disks.public.url'] = 'https://barocco.by/media';
        }

        Gate::policy(Role::class, RolePolicy::class);
    }
}
