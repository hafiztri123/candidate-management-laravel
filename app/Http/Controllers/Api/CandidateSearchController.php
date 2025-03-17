<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateCollection;
use App\Repositories\CandidateRepository;
use Illuminate\Http\Request;

class CandidateSearchController extends Controller
{
    protected $candidateRepository;

    public function __construct(CandidateRepository $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }



    public function __invoke(Request $request)
    {
        $criteria = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'sort_by' => $request->input('sort_by', 'created_at'),
            'sort_direction' => $request->input('sort_direction', 'desc'),
            'per_page' => $request->input('per_page', 10)
        ];

        $candidate = $this->candidateRepository->search($criteria);

        return new CandidateCollection($candidate);
    }
}
