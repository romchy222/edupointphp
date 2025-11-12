<?php

namespace App\Services;

use App\Models\Certificate;

class CertificateService
{
    /**
     * Генерирует PDF-сертификат
     * Простая реализация для shared hosting без внешних библиотек
     */
    public function generate(Certificate $certificate): string
    {
        $certificate->load(['user', 'course']);

        // HTML шаблон сертификата
        $html = $this->getHtmlTemplate($certificate);

        // В production можно использовать библиотеки типа DomPDF или TCPDF
        // Для shared hosting используем простую HTML-версию
        return $html;
    }

    private function getHtmlTemplate(Certificate $certificate): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Сертификат {$certificate->certificate_number}</title>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0; 
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .certificate {
            width: 297mm;
            height: 210mm;
            padding: 40mm;
            box-sizing: border-box;
            background: white;
            position: relative;
            margin: 0 auto;
        }
        .border {
            border: 5px solid #667eea;
            padding: 30px;
            height: 100%;
            position: relative;
        }
        .inner-border {
            border: 2px solid #764ba2;
            padding: 40px;
            height: 100%;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        h1 {
            font-size: 48px;
            color: #667eea;
            margin: 0 0 20px 0;
            text-transform: uppercase;
            letter-spacing: 5px;
        }
        h2 {
            font-size: 32px;
            color: #333;
            margin: 30px 0;
        }
        .course-name {
            font-size: 36px;
            color: #764ba2;
            font-weight: bold;
            margin: 20px 0;
            font-style: italic;
        }
        .details {
            font-size: 18px;
            color: #666;
            margin: 30px 0;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
        }
        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            padding-top: 10px;
            font-size: 14px;
        }
        .certificate-number {
            position: absolute;
            bottom: 20px;
            right: 30px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border">
            <div class="inner-border">
                <h1>Сертификат</h1>
                <h2>настоящим подтверждается, что</h2>
                <h2 style="color: #667eea; font-size: 42px;">{$certificate->user->name}</h2>
                <p style="font-size: 24px; margin: 20px 0;">успешно завершил(а) курс</p>
                <div class="course-name">"{$certificate->course->title}"</div>
                <div class="details">
                    <p>Дата выдачи: {$certificate->issued_at->format('d.m.Y')}</p>
                    <p>Платформа: EduPoint</p>
                </div>
                <div class="signature">
                    <div class="signature-line">
                        Директор платформы
                    </div>
                    <div class="signature-line">
                        Преподаватель курса<br>
                        {$certificate->course->teacher->name}
                    </div>
                </div>
                <div class="certificate-number">
                    № {$certificate->certificate_number}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Альтернативный метод для генерации с использованием DomPDF
     * Раскомментируйте и используйте, если установите библиотеку:
     * composer require barryvdh/laravel-dompdf
     */
    /*
    public function generatePdf(Certificate $certificate): string
    {
        $certificate->load(['user', 'course']);
        
        $pdf = \PDF::loadView('certificates.template', compact('certificate'));
        return $pdf->output();
    }
    */
}
