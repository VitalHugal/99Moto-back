<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Exception;

class ImportCoordinates extends Command
{
    // Definir o nome do parâmetro como 'file'
    protected $signature = 'import:coordinates {file}'; 
    protected $description = 'Importar coordenadas do arquivo CSV';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //obtenha o caminho do arquivo CSV
        $file = $this->argument('file'); 

        try {
            // Verifica se o arquivo existe
            if (!file_exists($file)) {
                $this->error("Arquivo CSV não encontrado: $file");
                return;
            }

            // Tente ler o arquivo CSV
            $csv = Reader::createFromPath($file, 'r');
            // Defina o cabeçalho (0 se houver cabeçalho no CSV)
            $csv->setHeaderOffset(0); 

            foreach ($csv as $record) {
                DB::table('vouchers_coordinates')->insert([
                    'latitudine_1' => $record['latitudine_1'],
                    'longitudine_1' => $record['longitudine_1'],
                ]);
            }

            $this->info('Importação de coordenadas concluída com sucesso!');
        } catch (Exception $e) {
            // Captura e exibe qualquer erro que ocorrer
            $this->error("Erro ao importar coordenadas: " . $e->getMessage());
        }
    }
}