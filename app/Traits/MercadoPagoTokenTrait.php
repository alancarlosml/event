<?php

namespace App\Traits;

use App\Models\MpAccount;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

trait MercadoPagoTokenTrait
{
    protected function isTokenExpiredOrExpiring($mpAccount)
    {
        if ($mpAccount->expires_in === null || $mpAccount->expires_in === '') {
            return false;
        }

        $raw = $mpAccount->expires_in;
        if ($raw instanceof \DateTimeInterface) {
            $expiresAt = \Carbon\Carbon::instance($raw);
        } elseif (is_numeric($raw)) {
            $expiresAt = \Carbon\Carbon::createFromTimestamp((int) $raw);
        } else {
            $expiresAt = \Carbon\Carbon::parse((string) $raw);
        }

        return now()->diffInDays($expiresAt, false) < 7;
    }

    protected function renewAccessToken($mpAccount)
    {
        if (empty($mpAccount->refresh_token)) {
            Log::warning('Cannot renew token: no refresh_token', ['mp_account_id' => $mpAccount->id]);
            return false;
        }

        try {
            $client = new Client();

            $response = $client->post('https://api.mercadopago.com/oauth/token', [
                'form_params' => [
                    'client_id' => config('services.mercadopago.client_id'),
                    'client_secret' => config('services.mercadopago.client_secret'),
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $mpAccount->refresh_token,
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!isset($responseData['access_token'])) {
                Log::error('Failed to renew token: invalid response', [
                    'mp_account_id' => $mpAccount->id,
                    'response' => $responseData
                ]);
                return false;
            }

            $mpAccount->update([
                'access_token' => $responseData['access_token'],
                'refresh_token' => $responseData['refresh_token'] ?? $mpAccount->refresh_token,
                'expires_in' => isset($responseData['expires_in'])
                    ? \Carbon\Carbon::now()->addSeconds($responseData['expires_in'])
                    : \Carbon\Carbon::now()->addDays(178),
            ]);

            Log::info('Token renewed successfully', [
                'mp_account_id' => $mpAccount->id,
                'organizer_id' => $mpAccount->participante_id
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Error renewing access token', [
                'mp_account_id' => $mpAccount->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    protected function ensureValidToken(MpAccount $mpAccount): bool
    {
        if ($this->isTokenExpiredOrExpiring($mpAccount)) {
            return $this->renewAccessToken($mpAccount);
        }
        return true;
    }
}
