<?php

namespace App\Repositories;

use App\Events\CandidateCreated;
use App\Events\CandidateDeleted;
use App\Events\CandidateForceDeleted;
use App\Events\CandidateRestored;
use App\Events\CandidateStatusChanged;
use App\Events\CandidateUpdated;
use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CandidateRepository implements CandidateRepositoryInterface
{
    /**
     * Create a new class instance.
     */


    public function getAll(array $columns = ['*']): Collection
    {
        return Candidate::all($columns);
    }

    public function getPaginated(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return Candidate::paginate($perPage, $columns);
    }

    public function getById($id, array $columns = ['*']): ?Candidate
    {
        return Candidate::find($id, $columns);
    }

    public function create(array $data): Candidate
    {
        $candidate = Candidate::create($data);
        event(new CandidateCreated($candidate));
        return $candidate;
    }

    public function update(Candidate $candidate, array $data): bool
    {
        if (array_keys($data) === ['status']){
            event(new CandidateStatusChanged($candidate, $candidate->status, $data['status'], Auth::user()));
        }

        $oldData = array_intersect_key($candidate->toArray(), $data);
        event(new CandidateUpdated($candidate, $oldData, $data));
        return $candidate->update($data);
    }

    public function delete(Candidate $candidate): bool
    {
        event(new CandidateDeleted($candidate));
        return $candidate->delete();
    }

    public function search(array $criteria): LengthAwarePaginator
    {
        $query = Candidate::query();

        if (isset($criteria['search'])) {
            $searchTerm = $criteria['search'];
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('skills', 'like', "%{$searchTerm}%");
            });
        }

        if (isset($criteria['status']) && $criteria['status'] !== 'all') {
            $query->where('status', $criteria['status']);
        }

        $sortField = $criteria['sort_by'] ?? 'created_at';
        $sortDirection = $criteria['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($criteria['per_page'] ?? 15);
    }

    public function forceDelete($id)
    {
        $candidate = Candidate::withTrashed()->findOrFail($id);
        event(new CandidateForceDeleted($candidate->toArray()));
        return $candidate->forceDelete();
    }

    public function restore($id)
    {
        $candidate = Candidate::onlyTrashed()->findOrFail($id);
        event(new CandidateRestored($candidate));
        return $candidate->restore();
    }

    public function thrashed(): LengthAwarePaginator
    {
        return Candidate::onlyTrashed()->paginate(15);
    }




}
