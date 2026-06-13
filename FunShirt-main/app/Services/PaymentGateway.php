<?php

namespace App\Services;

class PaymentGateway
{
    public static function process(string $type, string $ref, float $amount): bool
    {
        // Simulated Payment Gateway transaction API
        if (empty($type) || empty($ref) || $amount <= 0) {
            return false;
        }
        
        return true;
    }
}