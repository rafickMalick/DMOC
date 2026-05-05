<?php

namespace App\Livewire\Cart;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Collection;
use Livewire\Component;

class CartManager extends Component
{
    public Collection $items;

    public int $subtotal = 0;

    public int $itemsCount = 0;

    public function mount(): void
    {
        $this->items = collect();
        $this->refreshCart();
    }

    public function increase(int $itemId): void
    {
        $item = $this->resolveItem($itemId);
        if (! $item) {
            return;
        }

        if ($item->product && $item->quantity >= $item->product->stock) {
            $this->dispatch('toast', type: 'error', message: 'Stock insuffisant pour ce produit.');

            return;
        }

        $item->update(['quantity' => $item->quantity + 1]);
        $this->refreshCart();
        $this->dispatch('toast', type: 'success', message: 'Quantite mise a jour.');
    }

    public function decrease(int $itemId): void
    {
        $item = $this->resolveItem($itemId);
        if (! $item) {
            return;
        }

        if ($item->quantity <= 1) {
            $item->delete();
            $this->refreshCart();
            $this->dispatch('toast', type: 'info', message: 'Produit retire du panier.');

            return;
        }

        $item->update(['quantity' => $item->quantity - 1]);
        $this->refreshCart();
        $this->dispatch('toast', type: 'success', message: 'Quantite mise a jour.');
    }

    public function remove(int $itemId): void
    {
        $item = $this->resolveItem($itemId);
        if (! $item) {
            return;
        }

        $item->delete();
        $this->refreshCart();
        $this->dispatch('toast', type: 'info', message: 'Produit retire du panier.');
    }

    protected function resolveItem(int $itemId): ?CartItem
    {
        return CartItem::query()
            ->whereHas('cart', fn ($q) => $q->where('user_id', auth()->id()))
            ->with('product')
            ->find($itemId);
    }

    protected function refreshCart(): void
    {
        $cart = Cart::query()
            ->with(['items.product'])
            ->where('user_id', auth()->id())
            ->first();

        $this->items = $cart?->items ?? collect();
        $this->subtotal = (int) $this->items->sum(fn ($item) => $item->quantity * $item->unit_price_xof);
        $this->itemsCount = (int) $this->items->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart.cart-manager');
    }
}
