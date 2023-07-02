<?php

namespace Domain\Product\Jobs;

use Domain\Product\Mails\ProductImportMail;
use Domain\Product\Services\ProductService;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProductImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly string $path,
        private readonly User|null $user,
        protected ProductService $service,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            $this->service->readExcelFile($this->path);

            Mail::to($this->user)
                ->send((new ProductImportMail('The import of products has been successfully completed'))
                    ->subject('Products Import Completed'));

        } catch (\Exception $e) {

            Mail::to($this->user)
                ->send((new ProductImportMail('Products import failed, please try again later'))
                    ->subject('Products Import Failed'));
        }
    }
}
