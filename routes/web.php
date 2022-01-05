<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->post("login", ["as" => "login", "uses" => "AuthController@login"]);
$router->post("register", ["as" => "register", "uses" => "AuthController@register"]);
$router->post("reset-password", ["as" => "resetPassword", "uses" => "AuthController@resetPassword"]);
$router->post("reset-password-token", ["as" => "resetPasswordToken", "uses" => "AuthController@resetPasswordToken"]);
$router->post("check-reset-password-token", ["as" => "checkResetPasswordToken", "uses" => "AuthController@checkResetPasswordToken"]);



$router->group(["middleware" => "auth:web"], function () use ($router) {
    $router->get("/", function () {
        return "Authorization Successfully!";
    });
});
