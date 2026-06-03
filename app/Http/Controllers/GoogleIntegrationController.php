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
            'file' => 'required|file|max:5120' // Maks 5MB
        ]);

        $client = $this->googleService->getClient();
        
        // Jika token tidak valid, arahkan untuk login Google lagi
        if (!$client->getAccessToken()) {
            return redirect()->route('google.redirect');
        }

        $driveService = new Drive($client);
        $file = $request->file('file');

        // Setup metadata file Drive
        $fileMetadata = new Drive\DriveFile([
            'name' => $file->getClientOriginalName()
        ]);

        // Proses eksekusi upload ke Google Drive
        $content = file_get_contents($file->getRealPath());
        $driveService->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return back()->with('success', 'File berhasil diunggah ke Google Drive!');
    }

    /**
     * Fungsi Tambah Jadwal ke Google Calendar.
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

        // Bentuk format data event sesuai standar Google Calendar API
        $event = new Event([
            'summary' => $request->summary,
            'start' => [
                'dateTime' => \Carbon\Carbon::parse($request->start_datetime)->toIso8601String(),
                'timeZone' => 'Asia/Jakarta',
            ],
            'end' => [
                'dateTime' => \Carbon\Carbon::parse($request->end_datetime)->toIso8601String(),
                'timeZone' => 'Asia/Jakarta',
            ],
        ]);

        // Insert event ke kalender utama user
        $calendarService->events->insert('primary', $event);

        return back()->with('success', 'Jadwal berhasil ditambahkan ke Google Calendar!');
    }
}