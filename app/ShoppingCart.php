<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{

	//Mass assigment -> las variables a modificar 

	protected $fillable = ['status'];

	public function approve() {
		$this->updateCustomIDAndStatus();
	}

	public function generateCustomID() {
		return md5("$this->id $this->update_at");
	}

	public function updateCustomIDAndStatus()  {
		$this->status = "approved";
		$this->customid =  $this->generateCustomID();
		$this->save();
	}

	//CALCULANDO PRODUCTOS EN EL CARRITO

	public function inShoppingCarts(){

		return $this -> hasMany ('App\InShoppingCart');
	}

	public function  products(){

		return $this -> belongsToMany('App\Product', 'in_shopping_carts');
	}

	public function order() {
		return $this->hasOne("App\Order")->first();
	}

// FIN DE CALCULANDO PRODUCTOS EN EL CARRITO 

	public function productsSize(){

		return $this -> products() -> count();
		//return $this -> id;
	}

	//SUMAR PRECIO PRODUCTOS CARITO

	public function total(){

		return $this -> products() -> sum('pricing');

		
	}

    public static function findOrCreateBySessionID($shopping_cart_id){

    	if($shopping_cart_id)
    		// Buscar mi carrito de compras con este ID
    		return ShoppingCart::findBySession($shopping_cart_id);

    	else
    		// crear un carrito de compras
    		return ShoppingCart::createWithoutSession();
    }

	public static function findBySession($shopping_cart_id){

			return ShoppingCart::find($shopping_cart_id);
	}

	public static function createWithoutSession(){

			return ShoppingCart::create([
			'status' => 'incompleted'
			]);

		//Con la linea de arriba se ahorran estas de abajo

	  /*$chopping_cart = new ShoppingCart;
		$shopping_cart -> status = 'incompleted';
		$shopping_cart -> save();

		return $shopping_cart;*/
	}
}
