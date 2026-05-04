<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;


class QuizController extends Controller
{

private function parseDuration($duration)
{
    preg_match('/(\d+)h/', $duration, $hours);
    preg_match('/(\d+)m/', $duration, $minutes);

    $h = isset($hours[1]) ? (int)$hours[1] : 0;
    $m = isset($minutes[1]) ? (int)$minutes[1] : 0;

    return ($h * 3600) + ($m * 60);
}
    public function show()
{
    $quiz = json_decode(
        file_get_contents(storage_path('app/questions.json')), 
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
        file_get_contents(storage_path('app/questions.json')),
        true
    );

    $pdf = PDF::loadView('quiz.pdf', [
        'quiz' => $quiz,
        'sections' => $quiz['sections'],
        'answers' => $answers,
        'candidate' => $request->candidate_name
    ]);

    // Generate PDF content (no file saved)
    $pdfContent = $pdf->output();

    Mail::send('emails.quiz', [
        'candidate' => $request->candidate_name
    ], function ($message) use ($pdfContent, $request) {

        $message->to('katongobupe444@gmail.com')
            ->subject('Quiz Submission - ' . $request->candidate_name)
            ->attachData($pdfContent, 'quiz.pdf');
    });

    return view('quiz.success');
}
}