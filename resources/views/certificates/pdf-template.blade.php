<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Сертификат {{ $certificate->certificate_number }}</title>
    <style>
        @page { 
            size: A4 landscape; 
            margin: 0; 
        }
        body { 
            font-family: 'DejaVu Sans', Arial, sans-serif; 
            margin: 0; 
            padding: 0;
        }
        .certificate {
            width: 100%;
            height: 100%;
            padding: 30px;
            box-sizing: border-box;
            background: white;
            position: relative;
        }
        .border {
            border: 8px solid #667eea;
            padding: 25px;
            height: 100%;
            position: relative;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }
        .inner-border {
            border: 3px solid #764ba2;
            padding: 30px;
            height: 100%;
            text-align: center;
            background: white;
        }
        .header {
            margin-bottom: 20px;
        }
        h1 {
            font-size: 56px;
            color: #667eea;
            margin: 20px 0;
            text-transform: uppercase;
            letter-spacing: 8px;
            font-weight: bold;
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin: 15px 0;
            font-weight: normal;
        }
        .user-name {
            font-size: 48px;
            color: #667eea;
            font-weight: bold;
            margin: 25px 0;
            padding: 10px 0;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
        }
        .course-name {
            font-size: 32px;
            color: #764ba2;
            font-weight: bold;
            margin: 25px 0;
            font-style: italic;
            line-height: 1.4;
        }
        .details {
            font-size: 16px;
            color: #666;
            margin: 30px 0;
            line-height: 1.8;
        }
        .signature {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-block {
            display: table-cell;
            width: 33%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            width: 200px;
            border-top: 2px solid #333;
            padding-top: 8px;
            font-size: 13px;
            margin: 0 auto;
            color: #666;
        }
        .certificate-number {
            position: absolute;
            bottom: 15px;
            right: 25px;
            font-size: 11px;
            color: #999;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 25px;
            font-size: 14px;
            color: #667eea;
            font-weight: bold;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 120px;
            color: rgba(102, 126, 234, 0.05);
            font-weight: bold;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border">
            <div class="inner-border">
                <div class="watermark">★</div>
                <div class="logo">
                    <i>EduPoint</i>
                </div>
                
                <div class="header">
                    <h1>СЕРТИФИКАТ</h1>
                    <h2>об успешном завершении курса</h2>
                </div>
                
                <h2 style="margin-top: 30px;">Настоящим подтверждается, что</h2>
                
                <div class="user-name">{{ $certificate->user->name }}</div>
                
                <p style="font-size: 20px; margin: 20px 0;">успешно завершил(а) онлайн-курс</p>
                
                <div class="course-name">"{{ $certificate->course->title }}"</div>
                
                <div class="details">
                    <p><strong>Дата выдачи:</strong> {{ $certificate->issued_at->format('d.m.Y') }}</p>
                    <p><strong>Платформа:</strong> EduPoint - Онлайн обучение</p>
                    @if($certificate->course->teacher)
                    <p><strong>Преподаватель:</strong> {{ $certificate->course->teacher->name }}</p>
                    @endif
                </div>
                
                <div class="signature">
                    <div class="signature-block">
                        <div class="signature-line">
                            Директор платформы
                        </div>
                    </div>
                    <div class="signature-block">
                        <div style="margin-bottom: 30px;">
                            <svg width="60" height="60" viewBox="0 0 24 24" fill="#667eea">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="signature-block">
                        <div class="signature-line">
                            Преподаватель курса
                        </div>
                    </div>
                </div>
                
                <div class="certificate-number">
                    Номер сертификата: {{ $certificate->certificate_number }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
