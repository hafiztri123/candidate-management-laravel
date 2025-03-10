<?php

namespace App\Http\Controllers\Api;

use App\ApiResponder;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCandidateRequest;
use App\Http\Resources\CandidateCollection;
use App\Http\Resources\CandidateResource;
use App\Models\Candidate;
use App\Repositories\CandidateRepositoryInterface;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    use ApiResponder;

    protected $candidateRepository;

    /**
     * Inject the CandidateRepositoryInterface into the controller.
     */
    public function __construct(CandidateRepositoryInterface $candidateRepository)
    {
        $this->candidateRepository = $candidateRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $candidates = $this->candidateRepository->getPaginated();
        return new CandidateCollection($candidates);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidateRequest $request)
    {
        $candidate = $this->candidateRepository->create($request->validated());
        return new CandidateResource($candidate);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $candidate = $this->candidateRepository->getById($id);

        if (!$candidate) {
            return $this->errorResponse('Candidate not found', 404);
        }

        return new CandidateResource($candidate);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCandidateRequest $request, Candidate $candidate)
    {
        $success = $this->candidateRepository->update($candidate, $request->validated());

        if (!$success) {
            return $this->errorResponse('Update failed', 400);
        }


        return $this->successResponse('Updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidate $candidate)
    {
        $success = $this->candidateRepository->delete($candidate);

        if (!$success) {
            return $this->errorResponse('Deletion failed', 400);
        }

        return response()->noContent();
    }
}
