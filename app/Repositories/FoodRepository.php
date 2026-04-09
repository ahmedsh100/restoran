<?php

namespace App\Repositories;

use App\Models\Food\Food;
use App\Repositories\Contracts\FoodRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class FoodRepository implements FoodRepositoryInterface
{
    public function __construct(protected Food $model) {}

    public function all(array $columns = ['*']): Collection
    {
        return $this->model->where('is_available', true)->latest()->get($columns);
    }

    public function paginate(int $perPage = 15, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->model->where('is_available', true)->latest()->paginate($perPage, $columns);
    }

    public function findById(int $id, array $columns = ['*']): ?object
    {
        return $this->model->with('reviews')->find($id, $columns);
    }

    public function findByCategory(string $category, ?int $limit = null): Collection
    {
        $query = $this->model->where('category', $category)->where('is_available', true);

        if ($limit) {
            $query->take($limit);
        }

        return $query->get();
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

    public function search(string $term, array $columns = ['*']): Collection
    {
        return $this->model->where('is_available', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->get($columns);
    }
}
