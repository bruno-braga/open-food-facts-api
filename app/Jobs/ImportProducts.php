<?php

namespace App\Jobs;

use App\Services\GzstreamServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Products\Services\ProductsServiceInterface;

class ImportProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(
        GzstreamServiceInterface $gzstreamService,
        ProductsServiceInterface $productsService
    ): void
    {
        $data = $gzstreamService->readFile($this->fileName);
        if (sizeof($data) == 0) {
            throw new \Exception('data cannot be empty');
        }

        try {
            DB::beginTransaction();

            $fileNameId = $productsService->getFileNameId($this->fileName);

            $productsService->saveHistory($fileNameId);
            $productsService->setAsDraft($fileNameId);
            $productsService->delete($fileNameId);

            $productsService->save($data, $fileNameId);

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();
        }
    }
}
