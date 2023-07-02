<?php

namespace Domain\Product\Jobs;

use Domain\Product\Mails\ProductExportMail;
use Domain\Product\Services\ProductService;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProductExportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $maxExceptions = 1;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly User|null $user,
        protected ProductService $service,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $csvFile = $this->service->createExcelFile();

            Mail::to($this->user)
                ->send((new ProductExportMail($csvFile, 'The export of products has been successfully completed'))
                    ->subject('Products Export Completed'));

        } catch (\Exception $e) {

            Mail::to($this->user)
                ->send((new ProductExportMail('', 'Products export failed, please try again later'))
                    ->subject('Products Export Failed'));
        }
    }
}
