<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login | Cuisine Compass</title>
	@vite("resources/css/app.css")
	<link rel="stylesheet" href="/fontawesome/css/all.css">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const errors = document.getElementById("errors");
            const errors_interval = setInterval(() => {
                if (errors.children.length === 0) {
                    errors.remove();
                    clearInterval(errors_interval);
                } else errors.children[0].remove();
            }, 2000);
        });
    </script>
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-100">
    @isset($errors)
        <div id="errors" class="absolute left-0 top-0 z-30 w-full pt-10">
            @foreach ($errors->all() as $error)
                <div class="mx-auto mb-3 w-fit rounded border-2 border-red-500 bg-red-100 bg-white p-4 text-red-500">{{ $error }}</div>
            @endforeach
        </div>
    @endisset
	<div class="w-full max-w-md rounded-lg bg-white p-8 shadow-lg">
		<h2 class="mb-6 text-center text-2xl font-bold text-gray-800">Admin Login</h2>
		<form action="{{ route("admin", [], false) }}" method="POST">
			@csrf
			<!-- Email Input -->
			<div class="mb-4">
				<label for="email" class="block text-sm font-medium text-gray-700">Email</label>
				<input type="email" name="email" id="email" required class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
			</div>

			<!-- Password Input -->
			<div class="mb-4">
				<label for="password" class="block text-sm font-medium text-gray-700">Password</label>
				<input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
			</div>

			<!-- Submit Button -->
			<button type="submit" class="w-full rounded-md bg-blue-500 px-4 py-2 text-white transition hover:bg-blue-600">
				Login
			</button>
		</form>
	</div>
</body>

</html>
