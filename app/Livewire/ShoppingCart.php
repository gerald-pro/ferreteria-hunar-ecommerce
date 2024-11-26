<?php

namespace App\Livewire;

use App\Facades\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ShoppingCart extends Component
{
    public $total;
    public $content;
    public $lastSale = [];
    //public $paymentMethod = 'ELECTRONICO';
    public $deliveryAddress = '';
    public $billingName = '';
    public $billingId = '';

    public function render()
    {
        $this->updateCart();
        $productIds = $this->content->keys()->toArray();
        $products = Product::with('promotions')->find($productIds);

        $content = $this->content->map(function ($item, $id) use ($products) {
            $product = $products->find($id);
            $item['original_price'] = $product->price;
            $item['discounted_price'] = $product->discounted_price;
            $item['discount_percentage'] = $product->currentPromotion ? $product->currentPromotion->discount_percentage : 0;
            return $item;
        });

        return view('livewire.shopping-cart', [
            'total' => $this->total,
            'content' => $content,
        ]);
    }

    public function removeFromCart(string $id): void
    {
        Cart::remove($id);
        $this->updateCart();
    }

    public function clearCart(): void
    {
        Cart::clear();
        $this->updateCart();
    }

    public function increaseQuantity(string $id): void
    {
        $cartItem = Cart::content()->get($id);
        Cart::updateQuantity($id, $cartItem['quantity'] + 1);
        $this->updateCart();
    }

    public function decreaseQuantity(string $id): void
    {
        $cartItem = Cart::content()->get($id);
        if ($cartItem['quantity'] > 1) {
            Cart::updateQuantity($id, $cartItem['quantity'] - 1);
        } else {
            Cart::remove($id);
        }
        $this->updateCart();
    }

    #[On('create-order')]
    public function createOrder()
    {
        if ($this->content->isEmpty()) {
            $this->dispatch('swal:modal', [
                'title' => 'Carrito vacío!',
                'text' => 'No ha seleccionado productos para realizar el pedido',
                'icon' => 'info',
            ]);
            return;
        }

        $user = Auth::user();

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $this->total,
            'delivery_address' => $this->deliveryAddress,
            //'payment_method' => $this->paymentMethod,
            'payment_method' => 'ELECTRONICO',
            'billing_name' => $this->billingName,
            'billing_id' => $this->billingId,
            'delivery_status' => 'PENDIENTE',
        ]);

        
        foreach ($this->content as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        /* if ($this->paymentMethod === 'ELECTRONICO') {

            $paymentResult = PaymentService::processPayment($order->id);

            if ($paymentResult['status'] === 'error') {
                $this->dispatch('swal:modal', [
                    'title' => 'Error al generar la transacción!',
                    'text' => $paymentResult['message'],
                    'icon' => 'error',
                ]);
                $order->delete();
                return;
            }
        } */

        Cart::clear();
        return redirect()->route('customer.orders')->with('success', 'Pedido creado correctamente');
    }

    private function updateCart(): void
    {
        $this->total = floatval(Cart::total());
        $this->content = Cart::content();
    }
}
