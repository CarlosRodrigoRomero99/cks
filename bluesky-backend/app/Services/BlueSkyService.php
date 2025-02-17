<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BlueSkyService
{
    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * BlueSkyService constructor.
     * Initializes the base URL and API key for the BlueSky API.
     */
    public function __construct()
    {
        $this->baseUrl = 'https://public.api.bsky.app/xrpc/app.bsky.feed.searchPosts';
        $this->apiKey = env('BLUESKY_API_KEY');
    }

    /**
     * Search for BlueSky posts based on query parameters and filters.
     * 
     * @param string $query The search query.
     * @param array $filters Optional filters for the search (e.g., 'since', 'until').
     * @param int $limit The number of posts to return (default is 10).
     * @param string $sort The sort order of the posts (default is 'latest').
     * @return array The search results as an associative array.
     */
    public function searchPosts($query, $filters = [], $limit = 10, $sort = 'latest')
    {
        $params = [
            'q' => $query,
            'limit' => $limit,
            'sort' => $sort
        ];
    
        if (!empty($filters['since'])) {
            $params['since'] = $filters['since'];
        }
        if (!empty($filters['until'])) {
            $params['until'] = $filters['until'];
        }
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}", $params);
    
        return $response->json();
    }
}
