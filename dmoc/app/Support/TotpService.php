<?php

namespace App\Support;

class TotpService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    public function generateSecret(int $length = 32): string
    {
        $secret = '';
        $max = strlen(self::BASE32_ALPHABET) - 1;

        for ($i = 0; $i < $length; $i++) {
            $secret .= self::BASE32_ALPHABET[random_int(0, $max)];
        }

        return $secret;
    }

    public function verify(string $secret, string $otp, int $window = 1): bool
    {
        $otp = preg_replace('/\s+/', '', $otp);

        if (! preg_match('/^\d{6}$/', $otp)) {
            return false;
        }

        $counter = (int) floor(time() / 30);

        for ($i = -$window; $i <= $window; $i++) {
            if (hash_equals($this->at($secret, $counter + $i), $otp)) {
                return true;
            }
        }

        return false;
    }

    public function provisioningUri(string $accountName, string $secret, string $issuer = 'dmoc'): string
    {
        $label = rawurlencode($issuer.':'.$accountName);
        $issuer = rawurlencode($issuer);

        return "otpauth://totp/{$label}?secret={$secret}&issuer={$issuer}&digits=6&period=30&algorithm=SHA1";
    }

    private function at(string $secret, int $counter): string
    {
        $secretKey = $this->base32Decode($secret);
        $binaryCounter = pack('N*', 0).pack('N*', $counter);
        $hash = hash_hmac('sha1', $binaryCounter, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $chunk = substr($hash, $offset, 4);
        $value = unpack('N', $chunk)[1] & 0x7FFFFFFF;
        $otp = (string) ($value % 1_000_000);

        return str_pad($otp, 6, '0', STR_PAD_LEFT);
    }

    private function base32Decode(string $value): string
    {
        $value = strtoupper(preg_replace('/[^A-Z2-7]/', '', $value));
        $bits = '';

        foreach (str_split($value) as $char) {
            $index = strpos(self::BASE32_ALPHABET, $char);

            if ($index === false) {
                continue;
            }

            $bits .= str_pad(decbin($index), 5, '0', STR_PAD_LEFT);
        }

        $decoded = '';

        foreach (str_split($bits, 8) as $byte) {
            if (strlen($byte) === 8) {
                $decoded .= chr(bindec($byte));
            }
        }

        return $decoded;
    }
}
