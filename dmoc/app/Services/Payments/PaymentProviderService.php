<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Str;

class PaymentProviderService
{
    public function initiate(Payment $payment): array
    {
        return match ($payment->method) {
            'cinet' => $this->cinetPayPayload($payment),
            'stripe' => $this->stripePayload($payment),
            'paypal' => $this->paypalPayload($payment),
            default => [
                'provider' => 'cod',
                'checkout_url' => null,
                'transaction_id' => null,
                'status' => 'pending',
            ],
        };
    }

    private function cinetPayPayload(Payment $payment): array
    {
        return [
            'provider' => 'cinetpay',
            'checkout_url' => url('/simulated-payment/cinet/'.$payment->reference),
            'transaction_id' => 'CINET-'.Str::upper(Str::random(10)),
            'status' => 'processing',
        ];
    }

    private function stripePayload(Payment $payment): array
    {
        return [
            'provider' => 'stripe',
            'checkout_url' => url('/simulated-payment/stripe/'.$payment->reference),
            'transaction_id' => 'STRIPE-'.Str::upper(Str::random(10)),
            'status' => 'processing',
        ];
    }

    private function paypalPayload(Payment $payment): array
    {
        return [
            'provider' => 'paypal',
            'checkout_url' => url('/simulated-payment/paypal/'.$payment->reference),
            'transaction_id' => 'PAYPAL-'.Str::upper(Str::random(10)),
            'status' => 'processing',
        ];
    }
}
