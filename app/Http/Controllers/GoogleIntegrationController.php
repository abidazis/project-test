<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleApiService;
use Google\Service\Drive;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Support\Facades\Auth;

class GoogleIntegrationController extends Controller
{
    protected $googleService;

    public function __construct(GoogleApiService $googleService)
    {
        $this->googleService = $googleService;
    }

    /**
     * Redirect user ke halaman persetujuan Google.
     */
    public function redirectToGoogle()
    {
        $client = $this->googleService->getClient();
        $authUrl = $client->createAuthUrl();
        // Redirect ke URL Google OAuth
        return redirect()->away($authUrl);
    }

    /**
     * Handle callback dari Google setelah user memberikan persetujuan.
     */
    public function handleGoogleCallback(Request $request)
    {
        $client = $this->googleService->getClient();
        
        // Tukar kode otorisasi dengan Access Token
        $token = $client->fetchAccessTokenWithAuthCode($request->code);
        
        // Simpan token secara aman ke database user yang sedang login
        $user = Auth::user();
        $user->google_token = json_encode($token);
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil terhubung dengan Google!');
    }

    /**
     * Fungsi Upload File ke Google Drive.
     */
    public function uploadToDrive(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120'
        ]);

        $client = $this->googleService->getClient();
        if (!$client->getAccessToken()) {
            return redirect()->route('google.redirect');
        }

        $driveService = new Drive($client);
        $file = $request->file('file');

        $fileMetadata = new Drive\DriveFile([
            'name' => $file->getClientOriginalName()
        ]);

        $content = file_get_contents($file->getRealPath());
        
        // Minta Google mengembalikan 'webViewLink' juga
        $createdFile = $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink' 
        ]);

        // Kirim pesan sukses beserta link aslinya
        return back()
            ->with('success', 'File berhasil diunggah ke Google Drive!')
            ->with('action_url', $createdFile->webViewLink);
    }

    /**
     * Fungsi Membuat Event di Google Calendar.
     */
    public function createCalendarEvent(Request $request)
    {
        $request->validate([
            'summary' => 'required|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
        ]);

        $client = $this->googleService->getClient();
        if (!$client->getAccessToken()) {
            return redirect()->route('google.redirect');
        }

        $calendarService = new Calendar($client);

        $event = new Event([
            'summary' => $request->summary,
            'start' => [
                // PERBAIKAN ZONA WAKTU: Kunci di 'Asia/Jakarta'
                'dateTime' => \Carbon\Carbon::parse($request->start_datetime, 'Asia/Jakarta')->toIso8601String(),
                'timeZone' => 'Asia/Jakarta',
            ],
            'end' => [
                // PERBAIKAN ZONA WAKTU: Kunci di 'Asia/Jakarta'
                'dateTime' => \Carbon\Carbon::parse($request->end_datetime, 'Asia/Jakarta')->toIso8601String(),
                'timeZone' => 'Asia/Jakarta',
            ],
        ]);

        $createdEvent = $calendarService->events->insert('primary', $event);

        // Kirim pesan sukses beserta link aslinya
        return back()
            ->with('success', 'Jadwal berhasil ditambahkan ke Google Calendar!')
            ->with('action_url', $createdEvent->htmlLink);
    }
}