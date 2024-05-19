<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GzstreamService implements GzstreamServiceInterface
{
    private const FILE_URL = 'https://challenges.coode.sh/food/data/json/';

    private const FILES_PATH = 'products_gz/';

    private const MAX_PRODUCTS = 1;

    private array $data = [];

    public function readFile(string $fileName): void {
        try {
            $res = Http::get(self::FILE_URL . $fileName);
            $res->throwUnlessStatus(Response::HTTP_OK);

            Storage::disk('productsgz')->put(
                $fileName,
                $res->body()
            );

            $file = storage_path() . '/app/' . self::FILES_PATH . $fileName;
            $stream = gzopen($file, 'r');

            for ($line = 1; $line == self::MAX_PRODUCTS; $line++) { 
                echo $line . PHP_EOL;
                $this->data[$line] = json_decode(fgets($stream));
            }

            fclose($stream);

            if (Storage::disk('productsgz')->exists($fileName)) {
                Storage::disk('productsgz')->delete($fileName);
            }
        } catch (RequestException $e) {
            Log::error($e);
        }
    }
}
