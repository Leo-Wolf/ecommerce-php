<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@home');

Route::get('/carrito', 'ShoppingCartsController@index');
Route::post('/carrito', 'ShoppingCartsController@checkout');
Route::get('/payments/store', 'PaymentsController@store');

Auth::routes();

Route::resource('products','ProductsController');

Route::resource('in_shopping_carts','InShoppingCartsController', [
	'only' => ['store','destroy']
	]);

Route::resource('compras','ShoppingCartsController',[
	 'only' => ['show']
	]);

Route::resource('orders','OrdersController',[ 
	'only' => ['index','update']
	]);


//ESTA MANERA DE SERVIR IMAGENES ES UNA MALA PRACTICA SE RECOMIENDA USAR CONTROLLERS

Route::get('products/images/{filename}', function($filename) {
	$path = storage_path("app/images/$filename");

	if(!\File::exists($path)) abort(404);

	$file = \File::get($path);

	$type = \File::mimeType($path);

	$response  = Response::make($file,200);

	$response->header("Content-Type",$type);

	return $response;
});

/*

GET /products => Index
POST /products => store
GET /products/create ==> Formulario para crear

GET /products/:id => Mostrar un producto con ID
GET /products/:id/edit
PUT/PATCH /products/:id
DELETE /products/:id


*/

Route::get('/home', 'HomeController@index');
