<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Repositories\Interface\NutritionalRepository;
use App\Tools\Tools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NutricionalController extends Controller
{
    public function __construct(
        protected Request $request,
        private NutritionalRepository $nutritionalRepository
    ) {
    }

    public function dadosApi()
    {
        $tempoInicial = microtime(true);
        $pdo = DB::connection()->getReadPdo();
        $memoryKb = memory_get_usage() / 1024;
        $memoryMb = $memoryKb / 1024;

        return response()->json([
            "DB" => [
                "DRIVER_NAME" => $pdo->getAttribute($pdo::ATTR_DRIVER_NAME),
                "SERVER_INFO" => $pdo->getAttribute($pdo::ATTR_SERVER_INFO),
                "CONNECTION_STATUS" => $pdo->getAttribute($pdo::ATTR_CONNECTION_STATUS),
            ],
            "MEMORY" => round($memoryMb, 2) . " mb",
            "TIME_EXECUTE" => date('H:i:s:m', microtime(true) - $tempoInicial)
        ]);
    }

    public function atualizar(string $code)
    {
        $dados = Tools::selecionarParametros($this->request->all());
        if (empty($dados)) {
            return response()->json(["Error" => "Nenhum propriedade valida foi passada"], 422);
        }

        if ($food = $this->nutritionalRepository->update($code, $dados)) {
            $food = $this->nutritionalRepository->fetchByCode($code);
            return response()->json($food);
        } else {
            return response()->json(["Error" => "O 'code' não foi encontrado"], 422);
        }
    }

    public function deletar(int $code)
    {
        if ($this->nutritionalRepository->delete($code)) {
            return response()->json([], 200);
        } else {
            return response()->json(["Error" => "Não foi possivel deletar"], 400);
        }
    }

    public function buscar(string $code)
    {
        $food = $this->nutritionalRepository->fetchByCode($code);
        return response()->json($food);
    }

    public function listar()
    {
        $where = Tools::selecionarQuery($this->request->query('where'));
        $orderBy = Tools::selecionarQuery($this->request->query('orderBy'));
        
        $foods = $this->nutritionalRepository->getAll($where, $orderBy);
        return response()->json($foods);
    }
}
