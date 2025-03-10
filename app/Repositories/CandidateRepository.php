<?php

namespace App\Repositories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class CandidateRepository implements CandidateRepositoryInterface
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Candidate $model)
    {
        $this->model = $model;
    }

    public function getAll(array $columns = ['*']): Collection
    {
        return $this->model->all($columns);
    }

    public function getPaginated(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function getById(int $id, array $columns = ['*']): ?Candidate
    {
        return $this->model->find($id, $columns);
    }

    public function create(array $data): Candidate
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id);
    }

    public function search(array $criteria): LengthAwarePaginator
    {
        $query = $this->model->query();

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


}
