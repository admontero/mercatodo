<?php

namespace App\ApiAdmin\Shared\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
use Domain\Shared\Contracts\ReportFactoryInterface;
use Domain\Shared\DTOs\ReportDTO;
use Domain\Shared\Mails\ReportMail;
use Domain\User\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerateReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public int $maxExceptions = 1;

    /**
     * Create a new job instance.
     *
     */
    public function __construct(
        private readonly User|null $user,
        private readonly ReportDTO $dto,
        protected ReportFactoryInterface $reportFactory,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $processor = $this->reportFactory->initializeReport($this->dto->name);

            [$data, $view] = $processor->generate($this->dto);

            $pdf = Pdf::loadView($view, ['data' => $data]);

            Mail::to($this->user)
                ->send((new ReportMail($pdf, 'The report has been generated successfully'))
                    ->subject('Report Completed'));
        } catch (\Exception $e) {
            Mail::to($this->user)
                ->send((new ReportMail(null, 'Report generation has failed, please try again later'))
                    ->subject('Report Failed'));
        }
    }
}
