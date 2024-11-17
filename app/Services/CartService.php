<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Session\SessionManager;

class CartService
{
    const MINIMUM_QUANTITY = 1;
    const DEFAULT_INSTANCE = 'shopping-cart';

    protected $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function add($id, $name, $price, $quantity = self::MINIMUM_QUANTITY, $options = []): void
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            $existingItem = $content->get($id);
            $existingItem['quantity'] += $quantity;
            $existingItem['price'] = $price; // Actualiza el precio por si ha cambiado
            $content->put($id, $existingItem);
        } else {
            $cartItem = $this->createCartItem($name, $price, $quantity, $options);
            $content->put($id, $cartItem);
        }
        $this->session->put(self::DEFAULT_INSTANCE, $content);
    }

    public function remove(string $id): void
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            $content->forget($id);
            $this->session->put(self::DEFAULT_INSTANCE, $content);
        }
    }

    public function clear(): void
    {
        $this->session->forget(self::DEFAULT_INSTANCE);
    }

    public function content(): Collection
    {
        return $this->getContent();
    }

    public function total(): string
    {
        $content = $this->getContent();

        $total = $content->reduce(function ($total, $item) {
            return $total += $item['price'] * $item['quantity'];
        }, 0);

        return number_format($total, 2, '.', '');
    }

    protected function getContent(): Collection
    {
        return $this->session->get(self::DEFAULT_INSTANCE, collect([]));
    }

    protected function createCartItem(string $name, float $price, int $quantity, array $options): Collection
    {
        return collect([
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'options' => $options,
        ]);
    }

    public function updateQuantity(string $id, int $quantity): void
    {
        $content = $this->getContent();

        if ($content->has($id)) {
            $cartItem = $content->get($id);
            $cartItem['quantity'] = max(self::MINIMUM_QUANTITY, $quantity);
            $content->put($id, $cartItem);
            $this->session->put(self::DEFAULT_INSTANCE, $content);
        }
    }
}
