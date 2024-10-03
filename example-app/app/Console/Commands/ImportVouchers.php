<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Exception;

class ImportVouchers extends Command
{
    protected $signature = 'import:vouchers {file}'; // Definir o nome do parâmetro como 'file'
    protected $description = 'Importar vouchers do arquivo CSV';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = $this->argument('file'); // Obtenha o caminho do arquivo CSV

        try {
            // Verifica se o arquivo existe
            if (!file_exists($file)) {
                $this->error("Arquivo CSV não encontrado: $file");
                return;
            }

            // Tente ler o arquivo CSV
            $csv = Reader::createFromPath($file, 'r');
            $csv->setHeaderOffset(0); // Defina o cabeçalho (0 se houver cabeçalho no CSV)

            foreach ($csv as $record) {
                DB::table('vouchers')->insert([
                    'voucher' => $record['voucher'], // Insere o valor do voucher
                ]);
            }

            $this->info('Importação de vouchers concluída com sucesso!');
        } catch (Exception $e) {
            // Captura e exibe qualquer erro que ocorrer
            $this->error("Erro ao importar vouchers: " . $e->getMessage());
        }
    }
}