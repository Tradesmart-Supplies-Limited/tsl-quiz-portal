<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Technical Evaluation</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}
</style>
</head>

<body class="bg-gray-100">

<div class="max-w-4xl mx-auto p-6">

<div id="startOverlay"
    class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">

    <div class="bg-white w-full max-w-lg p-8 rounded-2xl shadow-lg text-center">

        <!-- LOGO -->
        <div class="flex justify-center mb-4">
            <img src="https://misc.tradesmartzm.com/logo.png"
                 class="h-14"
                 alt="Company Logo">
        </div>

        <!-- TITLE -->
        <h2 class="text-2xl font-bold text-gray-800 mb-1">
            Welcome to the Assessment
        </h2>

        <p class="text-gray-600 mb-6">
            Please read the instructions carefully before starting your evaluation.
        </p>

        <!-- QUIZ INFO CARD -->
        <div class="bg-gray-50 border rounded-xl p-4 text-sm text-left mb-6">

            <p class="mb-2">
                <span class="font-semibold text-gray-700">Title:</span>
                {{ $quiz['title'] }}
            </p>

            <p class="mb-2">
                <span class="font-semibold text-gray-700">Duration:</span>
                {{ $quiz['duration'] }}
            </p>

            <p>
                <span class="font-semibold text-gray-700">Instructions:</span><br>
                <span class="text-gray-600">
                    {{ $quiz['instructions'] }}
                </span>
            </p>

        </div>

        <!-- START BUTTON -->
        <button onclick="startQuiz()"
            class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition">

            Start Assessment

        </button>

        <p class="text-xs text-gray-400 mt-4">
            The timer will begin immediately once you start.
        </p>

    </div>

</div>

    <!-- HEADER -->
    <div class="bg-white sticky top-0 rounded-2xl shadow p-6 mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <img src="https://misc.tradesmartzm.com/logo.png" class="h-12">
            <div>
                <h1 class="text-xl font-bold text-gray-800">{{ $quiz['title'] }}</h1>
                <p class="text-sm text-gray-500">{{ $quiz['instructions'] }}</p>
            </div>
        </div>

        <!-- TIMER -->
        <div id="timer" class="text-lg font-semibold text-red-500 bg-red-50 px-4 py-2 rounded-xl"></div>
    </div>

    <div id="quizWrapper" class="filter blur-md pointer-events-none transition">

    <form method="POST" action="/quiz/submit" id="quizForm">
        @csrf

        <!-- Candidate -->
        <div class="bg-white p-5 rounded-2xl shadow mb-6">
            <label class="text-sm text-gray-600">Candidate Name</label>
            <input type="text" name="candidate_name" required
                   class="w-full border rounded-lg px-4 py-2 mt-2">
        </div>

        <!-- TABS -->
        <div class="sticky top-[150px] z-40 bg-white p-4 rounded-2xl shadow mb-6 flex flex-wrap gap-2">
            @foreach($sections as $index => $section)
                <button type="button"
                        onclick="goToSection({{ $index }})"
                        class="section-tab px-4 py-2 rounded-full border text-sm"
                        data-index="{{ $index }}">
                    Part {{ $index + 1 }}
                </button>
            @endforeach
        </div>

        <!-- PROGRESS -->
        <div class="text-center text-sm text-gray-500 mb-4">
            Section <span id="currentStep">1</span> of {{ count($sections) }}
        </div>

        <!-- SECTIONS -->
        @foreach($sections as $index => $section)

        <div class="section-panel hidden" data-section="{{ $index }}">

            <h2 class="text-xl font-bold text-gray-800 mb-4">
                {{ $section['title'] }} ({{ $section['points'] }} pts)
            </h2>

            @foreach($section['questions'] as $q)

            <div class="bg-white p-6 rounded-xl shadow mb-4">

                <h3 class="font-semibold text-blue-600">
                    {{ $q['title'] }} ({{ $q['points'] ?? 0 }} pts)
                </h3>

                <p class="mb-3">{{ $q['content'] }}</p>

                @foreach($q['inputs'] as $inputIndex => $input)

                <div class="mb-4">
                    <label class="text-sm text-gray-600 block mb-2">
                        {{ $input['label'] ?? '' }}
                    </label>

                    @if($input['type'] == 'radio')
                        @foreach($input['options'] as $opt)
                            <label class="flex items-center gap-2 mb-1">
                                <input type="radio"
                                       name="answers[{{ $q['id'] }}][{{ $inputIndex }}]"
                                       value="{{ $opt }}">
                                {{ $opt }}
                            </label>
                        @endforeach

                    @elseif($input['type'] == 'paragraph')
                        <textarea name="answers[{{ $q['id'] }}][{{ $inputIndex }}]"
                                  rows="4"
                                  class="w-full border rounded-lg px-3 py-2"></textarea>

                    @elseif($input['type'] == 'short_text')
                        <input type="text"
                               name="answers[{{ $q['id'] }}][{{ $inputIndex }}]"
                               class="w-full border rounded-lg px-3 py-2">
                    @endif
                </div>

                @endforeach

            </div>

            @endforeach

        </div>

        @endforeach

        <!-- NAVIGATION -->
        <div class="flex justify-between mt-6">

            <button type="button" id="prevBtn"
                    onclick="prevSection()"
                    class="bg-gray-300 px-5 py-2 rounded-lg">
                Previous
            </button>

            <button type="button" id="nextBtn"
                    onclick="nextSection()"
                    class="bg-blue-600 text-white px-5 py-2 rounded-lg">
                Next
            </button>

            <button type="submit" id="submitBtn"
                    class="hidden bg-green-600 text-white px-5 py-2 rounded-lg">
                Submit
            </button>

        </div>

    </form>

 </div>   

</div>
<script>
// ======================================================
// 1. GLOBAL STATE
// ======================================================
let duration = {{ $duration }};
let interval = null;
let quizStarted = false;

let currentSection = 0;
let totalSections = {{ count($sections) }};


// ======================================================
// 2. START QUIZ (UNLOCK + FULLSCREEN + TIMER)
// ======================================================
function startQuiz() {
    quizStarted = true;

    // Hide start overlay
    const overlay = document.getElementById('startOverlay');
    if (overlay) overlay.style.display = 'none';

    // Unblur quiz
    const wrapper = document.getElementById('quizWrapper');
    if (wrapper) wrapper.classList.remove('blur-md', 'pointer-events-none');

    // Request fullscreen
    const docEl = document.documentElement;
    if (docEl.requestFullscreen) {
        docEl.requestFullscreen();
    } else if (docEl.webkitRequestFullscreen) {
        docEl.webkitRequestFullscreen();
    } else if (docEl.msRequestFullscreen) {
        docEl.msRequestFullscreen();
    }

    startTimer();
}


// ======================================================
// 3. TIMER LOGIC
// ======================================================
function startTimer() {
    const timerDisplay = document.getElementById('timer');

    interval = setInterval(() => {

        let minutes = Math.floor(duration / 60);
        let seconds = duration % 60;

        if (timerDisplay) {
            timerDisplay.innerHTML =
                `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }

        duration--;

        if (duration < 0) {
            clearInterval(interval);

            Swal.fire({
                icon: 'warning',
                title: 'Time is up!',
                text: 'Your answers will be submitted automatically.'
            }).then(() => {
                document.getElementById('quizForm').submit();
            });
        }

    }, 1000);
}


// ======================================================
// 4. SECTION NAVIGATION (PILLS / TABS)
// ======================================================
function showSection(index) {

    // Hide all sections
    document.querySelectorAll('.section-panel')
        .forEach(el => el.classList.add('hidden'));

    // Show active section
    const activePanel = document.querySelector(`[data-section="${index}"]`);
    if (activePanel) activePanel.classList.remove('hidden');

    // Update tabs
    document.querySelectorAll('.section-tab')
        .forEach(tab => {
            tab.classList.remove('bg-blue-600', 'text-white');
            tab.classList.add('bg-white');
        });

    const activeTab = document.querySelector(`[data-index="${index}"]`);
    if (activeTab) {
        activeTab.classList.add('bg-blue-600', 'text-blue-800');
    }

    // Update step indicator (optional)
    const step = document.getElementById('currentStep');
    if (step) step.innerText = index + 1;

    // Buttons control
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');

    if (prevBtn) prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
    if (nextBtn) nextBtn.style.display = index === totalSections - 1 ? 'none' : 'inline-block';
    if (submitBtn) submitBtn.classList.toggle('hidden', index !== totalSections - 1);
}

function nextSection() {
    if (currentSection < totalSections - 1) {
        currentSection++;
        showSection(currentSection);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function prevSection() {
    if (currentSection > 0) {
        currentSection--;
        showSection(currentSection);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
}

function goToSection(index) {
    currentSection = index;
    showSection(index);
}


// ======================================================
// 5. SECURITY / EXAM BEHAVIOR
// ======================================================

// Warn before refresh / close
// window.addEventListener('beforeunload', function (e) {
//     if (!quizStarted) return;

//     e.preventDefault();
//     e.returnValue = 'You are taking a quiz. Leaving will lose progress.';
// });

// Optional: tab switch warning (NOT auto-submit - safer UX)
window.addEventListener('visibilitychange', function () {
    if (document.hidden && quizStarted) {
        Swal.fire({
            icon: 'warning',
            title: 'Tab switch detected',
            text: 'Please stay on the quiz page.'
        });


    }
});


// ======================================================
// 6. FORM SUBMISSION HANDLER
// ======================================================
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('quizForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Submit your answers?',
            text: "You won't be able to edit after submission.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit'
        }).then((result) => {
            if (result.isConfirmed) {

                Swal.fire({
                    title: 'Submitting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                form.submit();
            }
        });
    });

    // Initialize first section
    showSection(0);
});
</script>
<script>

let fullscreenPenaltyTimer = null;
let countdownInterval = null;

document.addEventListener('fullscreenchange', handleFullscreenExit);
document.addEventListener('webkitfullscreenchange', handleFullscreenExit);
document.addEventListener('msfullscreenchange', handleFullscreenExit);

function handleFullscreenExit() {

    if (!quizStarted) return;

    const isFullscreen = !!(
        document.fullscreenElement ||
        document.webkitFullscreenElement ||
        document.msFullscreenElement
    );

    const wrapper = document.getElementById('quizWrapper');

    // ================================
    // EXIT FULLSCREEN
    // ================================
    if (!isFullscreen) {

        if (wrapper) {
            wrapper.classList.add('blur-md', 'pointer-events-none');
        }

        // Clear old timers
        if (fullscreenPenaltyTimer) clearTimeout(fullscreenPenaltyTimer);
        if (countdownInterval) clearInterval(countdownInterval);

        let timeLeft = 10;

        Swal.fire({
            title: '<span style="color:red;font-size:28px;font-weight:bold;">WARNING</span>',
            html: `
                <div style="font-size:18px;margin-bottom:10px;">
                    You left fullscreen mode
                </div>

                <div style="
                    font-size:60px;
                    font-weight:bold;
                    color:red;
                    animation: pulse 1s infinite;
                " id="countdown">
                    ${timeLeft}
                </div>

                <div style="font-size:14px;margin-top:10px;color:#555;">
                    Returning to fullscreen is required to continue.
                </div>
            `,
            showConfirmButton: true,
            confirmButtonText: 'Return to Fullscreen',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {

                const countdownEl = document.getElementById('countdown');

                // Live countdown animation
                countdownInterval = setInterval(() => {

                    timeLeft--;

                    if (countdownEl) {
                        countdownEl.innerHTML = timeLeft;
                    }

                    if (timeLeft <= 0) {
                        clearInterval(countdownInterval);
                    }

                }, 1000);

            }
        }).then(() => {

            // Try re-enter fullscreen
            const docEl = document.documentElement;

            docEl.requestFullscreen?.() ||
            docEl.webkitRequestFullscreen?.() ||
            docEl.msRequestFullscreen?.();

            // Clear countdown when user tries to return
            if (countdownInterval) clearInterval(countdownInterval);

            if (wrapper) {
                setTimeout(() => {
                    wrapper.classList.remove('blur-md', 'pointer-events-none');
                }, 300);
            }
        });

        // Auto submit after 10 seconds
        fullscreenPenaltyTimer = setTimeout(() => {

            const stillNotFullscreen = !(
                document.fullscreenElement ||
                document.webkitFullscreenElement ||
                document.msFullscreenElement
            );

            if (stillNotFullscreen && quizStarted) {

                // Swal.fire({
                //     title: 'Submitting...',
                //     allowOutsideClick: false,
                //     didOpen: () => Swal.showLoading()
                // });

                Swal.fire({
                    icon: 'error',
                    title: 'Quiz Submitted',
                    text: 'You did not return to fullscreen in time.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => Swal.showLoading()

                });

                const wrapper = document.getElementById('quizWrapper');

                if (wrapper) {
                    wrapper.classList.add('blur-md', 'pointer-events-none');
                }

                document.getElementById('quizForm').submit();
            }

        }, 10000);
    }

    // ================================
    // RETURNED TO FULLSCREEN
    // ================================
    else {

        if (fullscreenPenaltyTimer) clearTimeout(fullscreenPenaltyTimer);
        if (countdownInterval) clearInterval(countdownInterval);

        if (wrapper) {
            wrapper.classList.remove('blur-md', 'pointer-events-none');
        }
    }
}
</script>

</body>
</html>