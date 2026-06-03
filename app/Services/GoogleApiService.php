<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Auth;

class GoogleApiService
{
    protected $client;

    /**
     * Inisialisasi Google Client beserta konfigurasinya.
     */
    public function __construct()
    {
        $this->client = new Client();
        // Setup Client ID dan Secret dari environment (.env)
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setRedirectUri(route('google.callback'));
        
        // Scope yang dibutuhkan: Drive (Upload) & Calendar (Events)
        $this->client->addScope(\Google\Service\Drive::DRIVE_FILE);
        $this->client->addScope(\Google\Service\Calendar::CALENDAR_EVENTS);
        
        // Akses offline penting agar kita dapat Refresh Token
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    /**
     * Mengembalikan instance dari Google Client
     */
    public function getClient()
    {
        // Jika user sedang login dan punya token tersimpan
        if (Auth::check() && Auth::user()->google_token) {
            $token = json_decode(Auth::user()->google_token, true);
            $this->client->setAccessToken($token);

            // Jika token kedaluwarsa, otomatis perbarui menggunakan refresh token
            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    
                    // Simpan token yang baru diperbarui ke database
                    $user = Auth::user();
                    $user->google_token = json_encode($this->client->getAccessToken());
                    $user->save();
                }
            }
        }
        
        return $this->client;
    }
}