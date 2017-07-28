<?php

namespace Carghaez\Larapi\Resource;

use Illuminate\Support\ServiceProvider;

use Illuminate\Auth\AuthManager;
use Illuminate\Database\DatabaseManager;

use Carghaez\Larapi\Resource\ResourceInfo;
use Carghaez\Larapi\Resource\ResourceService;
use Carghaez\Larapi\Resource\ResourceRepository;

class ResourceServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(ResourceInfo::class, function() {
            return new ResourceInfo;
        });

        $this->app->singleton(
            ResourceService::class,
            function(
                AuthManager $auth,
                DatabaseManager $database,
                ResourceRepository $repository
            ) {
                return new ResourceService($auth, $database, $repository);
            }
        );
    }
}
