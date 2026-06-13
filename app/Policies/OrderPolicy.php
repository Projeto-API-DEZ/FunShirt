<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin() || $user->isStaff()) {
            return true;
        }
        return $user->isCustomer() && $user->id === $order->customer_id;
    }

    public function create(User $user): bool
    {
        return $user->isCustomer() && !$user->blocked;
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->isStaff();
    }

    public function cancel(User $user, Order $order): bool
    {
        if ($order->status !== 'pending') {
            return false;
        }
        if ($user->isAdmin()) {
            return true;
        }
        return $user->isCustomer() && $user->id === $order->customer_id;
    }
}
