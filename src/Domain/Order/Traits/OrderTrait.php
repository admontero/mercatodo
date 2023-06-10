<?php

namespace Domain\Order\Traits;

use Illuminate\Support\Str;

trait OrderTrait
{
    /**
     * @return array<string, mixed>
     */
    public function getAuth(): array
    {
        $nonce = Str::random();
        $seed = date('c');

        return [
            'login' => config('placetopay.login'),
            'tranKey' => base64_encode(
                hash(
                    'sha256',
                    $nonce.$seed.config('placetopay.tranKey'),
                    true
                )
            ),
            'nonce' => base64_encode($nonce),
            'seed' => $seed,
        ];
    }
}

