<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;


class OperationsQuizController extends Controller
{

private function parseDuration($duration)
{
    // If it's already a number → treat as minutes
    if (is_numeric($duration)) {
        return (int)$duration * 60;
    }

    $duration = strtolower(trim($duration));

    $hours = 0;
    $minutes = 0;

    // Match hours (e.g. 1h)
    if (preg_match('/(\d+)\s*h/', $duration, $hMatch)) {
        $hours = (int)$hMatch[1];
    }

    // Match minutes (e.g. 30m)
    if (preg_match('/(\d+)\s*m/', $duration, $mMatch)) {
        $minutes = (int)$mMatch[1];
    }

    // Handle cases like "1h30" (no 'm')
    if (!$minutes && preg_match('/h(\d+)/', $duration, $hmMatch)) {
        $minutes = (int)$hmMatch[1];
    }

    // Handle plain number string like "60"
    if ($hours === 0 && $minutes === 0 && is_numeric($duration)) {
        $minutes = (int)$duration;
    }

    return ($hours * 3600) + ($minutes * 60);
}

    public function show()
{
    $quiz = json_decode(
        file_get_contents(storage_path('app/hq_operations_quiz.json')), 
        true
    );

    return view('quiz.index', [
        'quiz' => $quiz,
        'sections' => $quiz['sections'],
        'duration' => $this->parseDuration($quiz['duration'] ?? '1h30')
    ]);
}

public function submit(Request $request)
{
    $answers = $request->answers;

    $quiz = json_decode(
        file_get_contents(storage_path('app/hq_operations_quiz.json')),
        true
    );

    $pdf = PDF::loadView('quiz.pdf', [
        'quiz' => $quiz,
        'sections' => $quiz['sections'],
        'answers' => $answers,
        'candidate' => $request->candidate_name,
        'logoUrl' => asset('tsl_logo.png')
    ]);

    // Generate PDF content (no file saved)
    $pdfContent = $pdf->output();

    $date = date('Y-m-d');
    $filename = str_replace(' ', '_', $request->candidate_name) . '_' . str_replace(' ', '_', $quiz['title']) . '_' . $date . '.pdf';

    Mail::send('emails.quiz', [
        'candidate' => $request->candidate_name,
        'quiz' => $quiz,
    ], function ($message) use ($pdfContent, $request, $filename) {

        $message->to('katongobupe444@gmail.com')
            ->subject('Quiz Submission - ' . $request->candidate_name)
            ->attachData($pdfContent, $filename);
    });

    return view('quiz.success');
}
}