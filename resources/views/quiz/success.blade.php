<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Successful</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="bg-white p-10 rounded-2xl shadow text-center max-w-md">

    <!-- LOGO -->
    <img src="http://misc.tradesmartzm.com/logo.png" class="h-16 mx-auto mb-4">

    <!-- ICON -->
    <!-- <div class="text-green-500 text-5xl mb-4">
        ✔
    </div> -->

    <!-- MESSAGE -->
    <h1 class="text-xl font-bold text-gray-800 mb-2">
        Submission Successful
    </h1>

    <p class="text-gray-600 mb-6">
        Your responses have been submitted successfully.
        The examiner will review your answers.
    </p>

    <!-- OPTIONAL BUTTON -->
    <!-- <a href="/" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
        Done
    </a> -->

</div>

<script>
history.pushState(null, null, location.href);
window.onpopstate = function () {
    history.go(1);
};
</script>

</body>
</html>