<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface FoodRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function findById(int $id, array $columns = ['*']): ?object;

    public function findByCategory(string $category, ?int $limit = null): Collection;

    public function create(array $data): object;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function search(string $term, array $columns = ['*']): Collection;
}
