<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Evaluation Answer Booklet</title>

    <style>
    @page {
        margin: 100px 30px 60px 30px;
    }

    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 12px;
        color: #333;
        margin: 0;
    }

    /* HEADER */
    .header {
        position: fixed;
        top: -80px;
        left: 0;
        right: 0;
        height: 80px;
        border-bottom: 2px solid #e5e7eb;
        padding: 10px 20px;
    }

    .header img {
        height: 50px;
    }

    .header .title {
        position: absolute;
        right: 20px;
        top: 20px;
        text-align: right;
    }

    .header .title h2 {
        margin: 0;
        font-size: 16px;
    }

    .header .title p {
        margin: 0;
        font-size: 11px;
        color: #777;
    }

    /* FOOTER */
    .footer {
        position: fixed;
        bottom: -40px;
        left: 0;
        right: 0;
        height: 40px;
        border-top: 1px solid #e5e7eb;
        font-size: 10px;
        color: #777;
        text-align: center;
        line-height: 40px;
    }

    /* CONTENT */
    .content {
        margin: 0;
    }

    .candidate-box {
        border: 1px solid #ddd;
        padding: 10px;
        margin-bottom: 20px;
        background: #f9fafb;
    }

    .candidate-box strong {
        font-size: 13px;
    }

    .question {
        margin-bottom: 25px;
        page-break-inside: avoid;
    }

    .question-title {
        font-weight: bold;
        font-size: 13px;
        margin-bottom: 5px;
        color: #111827;
    }

    .question-content {
        margin-bottom: 10px;
        color: #374151;
    }

    .answer-box {
        border: 1px solid #ccc;
        padding: 10px;
        min-height: 60px;
        background: #ffffff;
    }

    .no-answer {
        color: #999;
        font-style: italic;
    }

    .section-divider {
        border-top: 1px dashed #ddd;
        margin: 20px 0;
    }

    .section-header {
        background: #f3f4f6;
        padding: 8px;
        border-left: 4px solid #2563eb;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <img src="https://misc.tradesmartzm.com/logo.png" alt="Logo">

        <div class="title">
            <h2>Technical Evaluation</h2>
            <p>HR Officer Assessment</p>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Generated on {{ date('d M Y H:i') }} | Confidential Document
    </div>

    <!-- CONTENT -->
    <div class="content">

        <!-- Candidate Info -->
        <div class="candidate-box">
            <strong>Candidate:</strong> {{ $candidate }} <br>
            <strong>Date:</strong> {{ date('d M Y') }}
        </div>

        <!-- QUESTIONS -->
        @foreach($sections as $section)

        <!-- SECTION HEADER -->
        <div style="margin-bottom:15px; padding:8px; background:#f3f4f6; border-left:4px solid #2563eb;">
            <strong>{{ $section['title'] }}</strong>
            @if(isset($section['points']))
            ({{ $section['points'] }} pts)
            @endif
        </div>

        @foreach($section['questions'] as $index => $q)

        <div class="question">

            <div class="question-title">
                Q{{ $q['id'] }}. {{ $q['title'] }}
                @if(isset($q['points']))
                ({{ $q['points'] }} pts)
                @endif
            </div>

            <div class="question-content">
                {{ $q['content'] }}
            </div>

            <div class="answer-box">

                @php
                $ansGroup = $answers[$q['id']] ?? [];
                @endphp

                @foreach($q['inputs'] as $i => $input)

                <div style="margin-top:10px;">
                    <strong>{{ $input['label'] ?? 'Answer' }}:</strong><br>

                    @php
                    $ans = $ansGroup[$i] ?? null;
                    @endphp

                    @if(is_array($ans))
                    {{ implode(', ', $ans) }}
                    @elseif($ans)
                    {{ $ans }}
                    @else
                    <span class="no-answer">No Answer Provided</span>
                    @endif
                </div>

                @endforeach

            </div>

        </div>

        <div class="section-divider"></div>

        @endforeach

        @endforeach

    </div>

</body>

</html>