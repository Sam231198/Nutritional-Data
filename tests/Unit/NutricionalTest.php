<?php

namespace Tests\Unit;

use App\Models\Food;
use App\Repositories\Out\NutirtionalRepositorySql;
use Mockery;
use Tests\TestCase;

class NutricionalTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function create_food()
    {
        return Food::factory()->create();
    }

    public function test_buscar_vazio(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->fetchByCode(0);

        $this->assertEquals([], $result);
        $food->delete();
    }

    public function test_buscar(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->fetchByCode($food->code);

        $this->assertEquals($food->toArray(), $result);
        $food->delete();
    }

    public function test_deletar_vazio(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->delete(0);

        $this->assertFalse($result);
        $food->delete();
    }

    public function test_deletar(): void
    {
        $food = $this->create_food();
        $this->assertEquals('publish', $food->status);

        $result = (new NutirtionalRepositorySql($food))->delete($food->code);
        $this->assertTrue($result);
        $food->delete();
    }

    public function test_update_vazio(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->update(0, ['labels' => 'test']);

        $this->assertFalse($result);
        $food->delete();
    }

    public function test_update(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->update(0, ['labels' => 'test']);
        $this->assertFalse($result);
        $food->delete();
    }

    public function test_(): void
    {
        $food = $this->create_food();
        $result = (new NutirtionalRepositorySql($food))->getAll();
        $this->assertIsArray($result);
        $food->delete();
    }
}
