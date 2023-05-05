<?php

namespace App\Repositories\Interface;


interface NutritionalRepository
{
    public function update(string $code, array $value): bool;
    public function fetchByCode(string $code): array;
    public function delete(string $code): bool;
    public function getAll(array $where = [], array $orderBy = []): array;
}
