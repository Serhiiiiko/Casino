<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Gateway extends Model
{
    use HasFactory;

    protected $table = 'gateways';

    protected $fillable = [
        // Suitpay
        'suitpay_uri',
        'suitpay_cliente_id',
        'suitpay_cliente_secret',

        // Stripe
        'stripe_production',
        'stripe_public_key',
        'stripe_secret_key',
        'stripe_webhook_key',

        // BSPay
        'bspay_uri',
        'bspay_cliente_id',
        'bspay_cliente_secret',

        // Antrpay (новые поля)
        'antrpay_uri',
        'antrpay_public_key',
        'antrpay_secret_key',
        'antrpay_is_enable',
    ];

    protected $hidden = ['updated_at'];

    // Скрываем ключи в демо-режиме
    protected function suitpayClienteId(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    protected function suitpayClienteSecret(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    protected function bspayClienteId(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    protected function bspayClienteSecret(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    protected function stripePublicKey(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    protected function stripeSecretKey(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }

    // Новые геттеры для Antrpay
    protected function antrpayPublicKey(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }
    
    protected function antrpaySecretKey(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => env('APP_DEMO') ? '*********************' : $value,
        );
    }
}
