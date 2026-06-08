<?php

namespace App\Helpers;

class TransactionCalculator
{
    public static function calculate($subtotal, $discountType, $discountValue, $taxPercentage, $servicePercentage)
    {
        // Discount
        $discountAmount = 0;
        if ($discountType == 'nominal') {
            $discountAmount = min($discountValue, $subtotal);
        } elseif ($discountType == 'percentage') {
            $discountAmount = $subtotal * $discountValue / 100;
        }
        $afterDiscount = $subtotal - $discountAmount;

        // Tax (dari after discount)
        $taxAmount = $afterDiscount * ($taxPercentage / 100);

        // Service (dari after discount)
        $serviceAmount = $afterDiscount * ($servicePercentage / 100);

        $grandTotal = $afterDiscount + $taxAmount + $serviceAmount;

        return [
            'discount_amount' => round($discountAmount),
            'tax_amount'      => round($taxAmount),
            'service_amount'  => round($serviceAmount),
            'grand_total'     => round($grandTotal),
        ];
    }
}
