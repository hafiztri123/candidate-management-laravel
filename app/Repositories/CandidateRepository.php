<?php

namespace App\Repositories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

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
        return Candidate::create($data);
    }

    public function update(Candidate $candidate, array $data): bool
    {
        return $candidate->update($data);
    }

    public function delete(Candidate $candidate): bool
    {
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
        return $candidate->forceDelete();
    }

    public function restore($id)
    {
        $candidate = Candidate::onlyTrashed()->findOrFail($id);
        return $candidate->restore();
    }

    public function thrashed(): LengthAwarePaginator
    {
        return Candidate::onlyTrashed()->paginate(15);
    }




}
