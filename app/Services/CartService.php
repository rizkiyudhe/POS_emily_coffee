<?php

namespace App\Services;

use App\Models\Product;

class CartService
{
    protected $sessionKey = 'pos_cart';

    /**
     * Ambil seluruh isi keranjang
     */
    public function getCart()
    {
        return session()->get($this->sessionKey, []);
    }

    /**
     * Tambah item ke keranjang
     */
    public function addItem($productId, $quantity = 1)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price'      => $product->price,
                'quantity'   => $quantity,
                'subtotal'   => $product->price * $quantity
            ];
        }

        $cart[$productId]['subtotal'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
        session()->put($this->sessionKey, $cart);
        return $this->getCart();
    }

    /**
     * Update jumlah item di keranjang
     */
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

    /**
     * Hapus item dari keranjang
     */
    public function removeItem($productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        session()->put($this->sessionKey, $cart);
    }

    public function updateNote($productId, $note)
    {
        $cart = $this->getCart();
        if (isset($cart[$productId])) {
            $cart[$productId]['notes'] = $note;
            session()->put($this->sessionKey, $cart);
        }
    }

    /**
     * Kosongkan keranjang
     */
    public function clearCart()
    {
        session()->forget($this->sessionKey);
    }

    /**
     * Hitung total belanja
     */
    public function getTotal()
    {
        $cart = $this->getCart();
        return array_sum(array_column($cart, 'subtotal'));
    }

    /**
     * Hitung jumlah item dalam keranjang
     */
    public function countItems()
    {
        return count($this->getCart());
    }
}
