<?php

namespace App\Services;

use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Генерирует PDF-сертификат с использованием DomPDF
     */
    public function generate(Certificate $certificate): string
    {
        $certificate->load(['user', 'course']);

        // Генерируем PDF используя DomPDF
        $pdf = Pdf::loadView('certificates.pdf-template', compact('certificate'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable_php', true)
            ->setOption('enable_remote', true);

        return $pdf->output();
    }
}
