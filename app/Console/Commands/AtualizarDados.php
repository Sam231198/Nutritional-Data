<?php

namespace App\Console\Commands;

use App\Models\Food;
use App\Models\ImportLog;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class AtualizarDados extends Command
{
    private $path = 'storage/app/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:atualizar-dados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualizaa dados nutricionais dos alimentos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = microtime(true);
        if ($this->dowload()) {
            if ($this->exportaDados($date)) {
                ImportLog::create([
                    "status" => true,
                    "messager" => "Atualização concluida",
                    "import_dt" => $date
                ]);
            }
        }
    }

    private function exportaDados($date)
    {
        try {
            $tipos = array('json');
            if ($diretorio = opendir($this->path)) {
                while ($arquivos = readdir($diretorio)) {
                    $ext = strtolower(pathinfo($arquivos, PATHINFO_EXTENSION));
                    if (in_array($ext, $tipos)) {
                        $this->leituraArquivo($arquivos, $date);
                    };
                }
                closedir($diretorio);
            }

        } catch (Exception $e) {
            ImportLog::create([
                "status" => false,
                "messager" => $e->getMessage(),
                "import_dt" => $date
            ]);
        }
    }

    private function dowload()
    {
        try {
            foreach ($this->lista_arquivo() as $value) {
                if ($value != '') {
                    $this->dowload_file($value);
                    $this->extract_gzip_file($value);
                    $this->delete($value);
                }
            }

            return true;
        } catch (Exception $e) {
            ImportLog::create([
                "status" => false,
                "messager" => $e->getMessage(),
                "import_dt" => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function lista_arquivo()
    {
        try {
            $this->info("Buscando arquivos...");

            $result = Http::get("https://challenges.coode.sh/food/data/json/index.txt");
            return mb_split("\n", $result);
        } catch (\Throwable $th) {
            throw new Exception("Error ao buscar arquivos.");
        }
    }

    private function dowload_file($nameFile)
    {
        $this->info("Baixando arquivo...");
        set_time_limit(0);
        //Este é o arquivo onde salvamos as informações
        $fp = fopen($this->path . $nameFile, 'w+');
        //Aqui está o arquivo que estamos baixando, substitua os espaços por %20
        $ch = curl_init(str_replace(" ", "%20", "https://challenges.coode.sh/food/data/json/" . $nameFile));

        if (!$ch) {
            $this->error("URL não responde!");
            throw new Exception("URL não responde!");
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        // escrever resposta curl no arquivo 
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // obter resposta curl
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $this->info("Arquivo $nameFile baixado.");
    }

    private function extract_gzip_file($archive)
    {
        $this->info("Extraindo Arquivo...");

        if (!function_exists('gzopen')) {
            $this->error('Seu PHP não tem suporte para o zlib.');
            throw new Exception("Seu PHP não tem suporte para o zlib.");
        }

        $filename = pathinfo($this->path . $archive, PATHINFO_FILENAME);
        $gzipped = gzopen($this->path . $archive, "rb");
        if ($gzipped) {
            $file = fopen($this->path . $filename, "w");
            while (!gzeof($gzipped)) {
                $string = gzread($gzipped, 8192);
                fwrite($file, $string, strlen($string));
            }

            fclose($file);
        }

        gzclose($gzipped);

        if (file_exists($this->path . $filename)) {
            $this->info('Arquivo extraido.');
        } else {
            $this->error("Arquivo $filename não foi extraido.");
            throw new Exception("Arquivo $filename não foi extraido.");
        }
    }

    private function delete($name)
    {
        if (file_exists($this->path . $name)) {
            if (unlink($this->path . $name)) {
                $this->info("Arquivo $name deletado");
            } else {
                $this->error("Arquivo $name não foi deletado");
                throw new Exception("Arquivo $name não foi deletado");
            }
        }
    }

    private function leituraArquivo($nome, $date)
    {
        $this->info("Lendo arquivos $nome");

        $arquivo = fopen($this->path . $nome, "r");
        if ($arquivo) {
            while (($linha = fgets($arquivo)) !== false) {
                $dados = json_decode($linha, true);
                $this->updateStatusDraft($dados["code"]);

                $this->cadastro([
                    "code" => str_replace("\"", "", $dados["code"]),
                    "url" => $dados["url"],
                    "creator" => $dados["creator"] ?? null,
                    "created_t" => $dados["created_t"] ?? null,
                    "last_modified_t" => $dados["last_modified_t"] ?? null,
                    "product_name" => $dados["product_name"] ?? null,
                    "quantity" => $dados["quantity"] ?? null,
                    "brands" => $dados["brands"] ?? null,
                    "categories" => $dados["categories"] ?? null,
                    "labels" => $dados["labels"] ?? null,
                    "cities" => $dados["cities"] ?? null,
                    "purchase_places" => $dados["purchase_places"] ?? null,
                    "stores" => $dados["stores"] ?? null,
                    "ingredients_text" => $dados["ingredients_text"] ?? null,
                    "traces" => $dados["traces"] ?? null,
                    "serving_size" => $dados["serving_size"] ?? null,
                    "serving_quantity" => $dados["serving_quantity"] ?? 0,
                    "nutriscore_score" => $dados["nutriscore_score"] ?? null,
                    "nutriscore_grade" => $dados["nutriscore_grade"] ?? null,
                    "main_category" => $dados["main_category"] ?? null,
                    "image_url" => $dados["image_url"] ?? null,
                ], $date);
            }
            fclose($arquivo);
        } else {
            $this->error("Arquivo $nome não encontrado");
            throw new Exception("Arquivo $nome não encontrado");
        }
    }

    private function cadastro($dados, $date)
    {
        try {
            $dados['status'] = 'published';
            $dados['import_t'] = $date;
            $code = $dados['code'];
            unset($dados['code']);
            Food::updateOrCreate(['code' => $code], $dados);
        } catch (\Throwable $th) {
            $this->error("Erro ao cadastrar: " . $th->getMessage());
            throw new Exception("Erro ao cadastrar.");
        }
    }

    private function updateStatusDraft($code)
    {
        try {
            Food::where(['code' => $code])->update(['status' => 'draft']);
        } catch (\Throwable $th) {
            throw new Exception("Erro ao atualizar status.");
            $this->error("Erro ao atualizar status: " . $th->getMessage());
        }
    }
}
