<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(protected Order $model) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->with(['user', 'items.food'])->latest()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->with(['user', 'items.food'])->latest()->paginate($perPage, $columns);
    }

    public function findById(int $id, array $columns = ['*']): ?object
    {
        return $this->model->with(['user', 'items.food', 'coupon'])->find($id, $columns);
    }

    public function findByUserId(int $userId, array $columns = ['*']): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['items.food'])
            ->latest()
            ->get($columns);
    }

    public function findByStatus(string $status, array $columns = ['*']): Collection
    {
        return $this->model->where('status', $status)
            ->with(['user', 'items.food'])
            ->latest()
            ->get($columns);
    }

    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->findById($id);

        if (! $record) {
            return false;
        }

        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = $this->findById($id);

        if (! $record) {
            return false;
        }

        return $record->delete();
    }

    public function getTotalRevenue(?string $status = null): float
    {
        $query = $this->model->query();

        if ($status) {
            $query->where('status', '!=', $status);
        }

        return (float) $query->sum('total_price');
    }

    public function count(?string $status = null): int
    {
        if ($status) {
            return $this->model->where('status', $status)->count();
        }

        return $this->model->count();
    }

    public function getRecent(int $limit = 10): Collection
    {
        return $this->model->with('user')->latest()->take($limit)->get();
    }
}
