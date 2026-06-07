<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected $sessionKey = 'pos_cart';

    public function getCart()
    {
        return session()->get($this->sessionKey, []);
    }

    public function addItem($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'subtotal' => $product->price * $quantity
            ];
        }
        $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
        session()->put($this->sessionKey, $cart);
        return $this->getCart();
    }

    public function updateQuantity($productId, $quantity)
    {
        $cart = $this->getCart();
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
            $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $quantity;
        }
        session()->put($this->sessionKey, $cart);
    }

    public function removeItem($productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        session()->put($this->sessionKey, $cart);
    }

    public function clearCart()
    {
        session()->forget($this->sessionKey);
    }

    public function getTotal()
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'subtotal'));
    }

    public function countItems()
    {
        return count($this->getCart());
    }
}
