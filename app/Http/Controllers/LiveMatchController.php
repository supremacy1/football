<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; // Import Log facade

class LiveMatchController extends Controller
{
    /**
     * Fetch live matches from API-Football via RapidAPI.
     */
    public function index()
    {
        try {
            // Use UTC for the date to match common API practices
            $date = Carbon::now('UTC')->format('Ymd');

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'x-rapidapi-key' => env('RAPID_API_KEY'),
                    'x-rapidapi-host' => 'free-api-live-football-data.p.rapidapi.com',
                ])->get('https://free-api-live-football-data.p.rapidapi.com/football-get-matches-by-date', [
                    'date' => $date,
                ]);

        } catch (\Exception $e) {
            Log::error('LiveMatchController API connection error: ' . $e->getMessage());
            return view('live', ['matches' => [], 'error' => 'Connection error: ' . $e->getMessage() . '. Please check your internet connection or API key.']);
        }

        $data = $response->json();

        if (!$response->successful()) {
            return view('live', ['matches' => [], 'error' => 'API Error: ' . ($data['message'] ?? 'Unable to fetch data.')]);
        }

        // Robustly extract the matches array from the response
        $matches = [];
        if (isset($data['data']) && is_array($data['data'])) {
            $matches = $data['data'];
        } elseif (isset($data['response']) && is_array($data['response'])) {
            $matches = $data['response'];
        } elseif (is_array($data)) {
            $matches = $data;
        }

        // Filter for matches happening today based on UTC
        $todayUtc = Carbon::now('UTC')->format('Y-m-d');
        $filteredMatches = array_filter($matches, function($match) use ($todayUtc) {
            if (!is_array($match)) return false;
            
            $status = strtolower($match['status']['type'] ?? '');
            if ($status === 'inprogress') return true;

            $matchDate = isset($match['startTimestamp']) 
                ? Carbon::createFromTimestamp($match['startTimestamp'], 'UTC')->format('Y-m-d') 
                : null;
            
            return $matchDate === $todayUtc;
        });

        // Sort matches: Live first, then Scheduled, then Finished
        usort($filteredMatches, function($a, $b) {
            $getRank = function($status) {
                $status = strtolower($status);
                if ($status === 'inprogress') return 1;
                if ($status === 'notstarted') return 2;
                if ($status === 'finished') return 3;
                return 4;
            };

            $rankA = $getRank($a['status']['type'] ?? '');
            $rankB = $getRank($b['status']['type'] ?? '');

            if ($rankA !== $rankB) return $rankA <=> $rankB;
            return ($a['startTimestamp'] ?? 0) <=> ($b['startTimestamp'] ?? 0);
        });

        return view('live', [
            'matches' => $filteredMatches,
            'error' => null
        ]);
    }
}