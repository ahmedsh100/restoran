<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function all(array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator;

    public function findById(int $id, array $columns = ['*']): ?object;

    public function findByUserId(int $userId, array $columns = ['*']): Collection;

    public function findByStatus(string $status, array $columns = ['*']): Collection;

    public function create(array $data): object;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getTotalRevenue(?string $status = null): float;

    public function count(?string $status = null): int;

    public function getRecent(int $limit = 10): Collection;
}
