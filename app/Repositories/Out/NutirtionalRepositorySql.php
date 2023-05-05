<?php

namespace App\Repositories\Out;

use App\Models\Food;
use App\Repositories\Interface\NutritionalRepository;

use function PHPSTORM_META\map;

class NutirtionalRepositorySql implements NutritionalRepository
{
    public function __construct(private Food $food)
    {
    }

    public function update(string $code, array $value): bool
    {
        return $this->food->where(['code' => $code])->update($value);
    }

    public function fetchByCode(string $code): array
    {
        $food = $this->food->where(['code' => $code])->first();
        if ($food) {
            return $food->toArray();
        }
        return [];
    }

    public function delete(string $code): bool
    {
        return $this->food->where(['code' => $code])->update(['status' => 'trash']);
    }

    public function getAll(array $where = [], array $orderBy = []): array
    {
        $foodsModel = new $this->food;
        if (!empty($where)) {
            $where = array_map(
                function ($key, $value) {
                    return [$key, 'like', $value];
                },
                array_keys($where),
                array_values($where)
            );
            $foodsModel = $foodsModel->where($where);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $key => $value) {
                $foodsModel = $foodsModel->orderBy($key, $value);
            }
        }

        $foodsModel = $foodsModel->paginate(100);
        $foodsTemp = $foodsModel->toArray();

        $foods['current_page'] = $foodsTemp['current_page'];
        $foods['nextPageUrl'] = $foodsModel->nextPageUrl();
        $foods['lastPage'] = $foodsModel->lastPage();
        $foods['perPage'] = $foodsModel->perPage();
        $foods['data'] = $foodsTemp['data'];

        return $foods;
    }
}
