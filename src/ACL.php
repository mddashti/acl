<?php
namespace Niyam\ACL;
use Illuminate\Support\Facades\Route;

class ACL
{
    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ? : function ($router) {
            $router->all();
        };

        $defaultOptions = [
            'namespace' => '\Niyam\ACL\Http\Controllers',
            'prefix' => ''
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}