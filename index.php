<?php

use controller\ArticleController;
use controller\AuthenticateController;
use controller\UserController;
use infra\Route;

session_start();

//source : https://steampixel.de/en/simple-and-elegant-url-routing-with-php/

// Include router class
require_once 'vendor/autoload.php';
// Include view session class
require_once 'view/ViewSession.php';

/*
 ADD CONTROLLERS HERE
 */

Route::addController(ArticleController::class, ArticleController::getInstance());
Route::addController(UserController::class, UserController::getInstance());
Route::addController(AuthenticateController::class, AuthenticateController::getInstance());

/*
 ADD ROUTES HERE
 */

// Add base route (startpage)
Route::add('/', 'get', function() {
    require('view/page/home_page.php');
});

Route::add('/article/edit', 'get', function() {
    require('view/page/article/article_form_page.php');
});
Route::add('/article', 'get', function() {
    require('view/page/article/article_page.php');
});
Route::add('/articles', 'get', function() {
    require('view/page/article/articles_page.php');
});

Route::add('/user/edit', 'get', function() {
    require('view/page/user/user_form_page.php');
});
Route::add('/user/role/edit', 'get', function() {
    require('view/page/user/user_change_role_form.php');
});
Route::add('/users', 'get', function() {
    require('view/page/user/users_page.php');
});

Route::add('/login', 'get', function() {
    require('view/page/auth/login_page.php');
});
Route::add('/registration', 'get', function() {
    require('view/page/user/user_form_page.php');
});

Route::run('/');
