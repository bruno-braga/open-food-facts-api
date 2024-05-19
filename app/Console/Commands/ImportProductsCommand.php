<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class ImportProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-products-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $fileList = [];

    private const PRODUCTS_FILE_LIST = 'https://challenges.coode.sh/food/data/json/index.txt';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $res = Http::get(self::PRODUCTS_FILE_LIST);
            $res->throwUnlessStatus(Response::HTTP_OK);

            $fileArray = explode(PHP_EOL, $res->body());
            foreach($fileArray as $file) {
                if (strlen($file) > 0) {
                    $this->fileList[] = $file;
                }
            }
        } catch(RequestException $e) {
            Log::error($e);
        }
    }
}
