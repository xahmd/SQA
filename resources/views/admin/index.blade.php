<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Cuisine Compass</title>
    @vite('resources/js/admin.js')
    @vite('resources/css/app.css')
    @vite('resources/css/ckeditor-tailwind-reset.css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="../fontawesome/css/all.css">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <script src="/ckeditor/ckeditor.js"></script>
</head>

<body class="h-screen bg-gray-200 flex flex-col w-screen overflow-hidden">
    <div>
        <nav class="bg-gray-800 flex">
            <div class="bg-gray-800 text-white py-4 w-64 text-center">
                <span class="text-lg font-semibold">Admin Panel</span>
            </div>
            <ul class="ml-auto mr-5">
                <li>
                    <form action="{{ route('admin.logout', [], false) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white block py-4 px-4">Logout</button>
                    </form>
            </ul>
        </nav>
    </div>
    <div id="app" class="flex overflow-y-auto flex-[1]"></div>
</body>

</html>