<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Config;

class LiveMatchController extends Controller
{
    /**
     * Fetch live matches from API-Football via RapidAPI.
     */
    public function index()
    {
        $apiKey = env('API_SPORTS_KEY'); // Get API key from .env
        $today = Carbon::now()->format('Y-m-d'); // Use Carbon for date

        $matches = [];
        $error = null;
        $rawData = null;

        if (!$apiKey) {
            $error = 'API_SPORTS_KEY is not set in the .env file.';
            Log::error($error);
            return view('live', ['matches' => [], 'error' => $error]);
        }

        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://v3.football.api-sports.io/fixtures?date=$today",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30, // Set a timeout
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => [
                    "x-apisports-key: $apiKey"
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                $error = 'cURL Error: ' . curl_error($curl);
            }

            curl_close($curl);

            $data = json_decode($response, true);
            $rawData = $data; // Store raw data for debugging

            // Check for HTTP errors first or API specific errors
            if ($httpCode !== 200 || (isset($data['errors']) && !empty($data['errors']))) {
                $error = 'API Error: ' . ($data['message'] ?? json_encode($data['errors'] ?? "HTTP status $httpCode"));
            }

            // Extract matches if no error
            if (!$error && isset($data['response']) && is_array($data['response'])) {
                $matches = $data['response'];
            }
        } catch (\Exception $e) {
            $error = 'Exception during API call: ' . $e->getMessage();
        }

        // Sort matches: Live first, then Scheduled, then Finished (only if no error)
        usort($matches, function($a, $b) {
            $getRank = function($status) {
                $status = strtolower($status);
                if ($status === 'inprogress') return 1;
                if ($status === 'notstarted') return 2;
                if ($status === 'finished') return 3;
                return 4;
            };

            $rankA = $getRank($a['fixture']['status']['short'] ?? '');
            $rankB = $getRank($b['fixture']['status']['short'] ?? '');

            if ($rankA !== $rankB) return $rankA <=> $rankB;
            return ($a['fixture']['timestamp'] ?? 0) <=> ($b['fixture']['timestamp'] ?? 0);
        });

        Log::error('LiveMatchController API Error: ' . $error);
        return view('live', compact('matches', 'error', 'rawData'));
    }
}