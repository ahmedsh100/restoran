<?php

if (! function_exists('format_currency')) {
    function format_currency($amount, $currency = '$', $decimals = 2)
    {
        return $currency.number_format((float) $amount, $decimals, '.', ',');
    }
}

if (! function_exists('calculate_subtotal')) {
    function calculate_subtotal($items)
    {
        return $items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }
}

if (! function_exists('calculate_total')) {
    function calculate_total($subtotal, $discount = 0)
    {
        return max(0, $subtotal - $discount);
    }
}

if (! function_exists('get_cart_count')) {
    function get_cart_count()
    {
        if (! auth()->check()) {
            return 0;
        }

        return auth()->user()->cart()->sum('quantity');
    }
}

if (! function_exists('generate_order_number')) {
    function generate_order_number()
    {
        return 'ORD-'.strtoupper(substr(md5(uniqid()), 0, 8));
    }
}

if (! function_exists('format_status_badge')) {
    function format_status_badge($status)
    {
        $colors = [
            'pending' => 'warning',
            'cooking' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'paid' => 'success',
            'failed' => 'danger',
        ];

        $color = $colors[strtolower($status)] ?? 'secondary';

        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $color,
            ucfirst($status)
        );
    }
}

if (! function_exists('truncate_text')) {
    function truncate_text($text, $length = 80, $suffix = '...')
    {
        return strlen($text) > $length ? substr($text, 0, $length).$suffix : $text;
    }
}

if (! function_exists('get_image_path')) {
    function get_image_path($image, $fallback = 'assets/img/menu-1.jpg')
    {
        if ($image && file_exists(public_path('storage/'.$image))) {
            return asset('storage/'.$image);
        }

        return asset($fallback);
    }
}

if (! function_exists('is_admin')) {
    function is_admin()
    {
        return auth()->check() && auth()->user()->is_admin;
    }
}
