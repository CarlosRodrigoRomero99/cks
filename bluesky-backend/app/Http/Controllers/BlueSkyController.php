<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BlueSkyService;

/**
 * Class BlueSkyController
 * 
 * This controller handles the search functionality for BlueSky posts.
 */
class BlueSkyController extends Controller
{
    /**
     * @var BlueSkyService
     */
    protected $blueSkyService;

    /**
     * BlueSkyController constructor.
     * 
     * @param BlueSkyService $blueSkyService
     */
    public function __construct(BlueSkyService $blueSkyService)
    {
        $this->blueSkyService = $blueSkyService;
    }

    /**
     * Search for BlueSky posts based on query parameters.
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
    
        if (!$query) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }
    
        $filters = [
            'since' => $request->input('since'),
            'until' => $request->input('until'),
        ];

        $limit = $request->input('limit', 10); // Default 10 posts per page
        $sort = $request->input('sort');
    
        $posts = $this->blueSkyService->searchPosts($query, $filters, $limit, $sort);
    
        return response()->json($posts);
    }
}
