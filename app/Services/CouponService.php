<?php

namespace App\Services;

use App\Models\Coupon;
use Illuminate\Support\Facades\Session;

class CouponService
{
    public function apply(string $code): array
    {
        $code = strtoupper(trim($code));

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => 'Invalid coupon code.'];
        }

        if (! $coupon->is_active) {
            return ['success' => false, 'message' => 'This coupon is no longer active.'];
        }

        if ($coupon->expiry_date->isPast()) {
            return ['success' => false, 'message' => 'This coupon has expired.'];
        }

        if ($coupon->usage_limit > 0 && $coupon->used_count >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        Session::put('coupon_code', $coupon->code);

        return ['success' => true, 'message' => 'Coupon applied successfully!', 'coupon' => $coupon];
    }

    public function remove(): void
    {
        Session::forget('coupon_code');
    }

    public function getActive(): ?Coupon
    {
        $code = Session::get('coupon_code');

        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (! $coupon || ! $coupon->isValid()) {
            $this->remove();

            return null;
        }

        return $coupon;
    }

    public function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        return $coupon->calculateDiscount($subtotal);
    }

    public function incrementUsage(int $couponId): void
    {
        Coupon::where('id', $couponId)->increment('used_count');
    }
}
