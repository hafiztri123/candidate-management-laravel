<?php

namespace App\Repositories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CandidateRepositoryInterface
{
    public function getAll(array $columns = ['*']): Collection;
    public function getPaginated(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;
    public function getById(int $id, array $columns = ['*']): ?Candidate;
    public function create(array $data): Candidate;
    public function update(Candidate $candidate, array $data): bool;
    public function delete(Candidate $candidate): bool;
    public function search(array $criteria): LengthAwarePaginator;
}
