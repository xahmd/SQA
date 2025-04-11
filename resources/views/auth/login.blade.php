<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login | {{ env('APP_NAME', 'Cuisine Compass') }}</title>
    <meta property="og:title" content="{{ env('APP_NAME', 'Cuisine Compass') }} | Taste the World | Food Blog" />
    <meta property="og:description" content="{{ env('APP_NAME', 'Cuisine Compass') }} | Taste the World | Food Blog" />
    <meta property="og:image" content="" />
    @include('inc.common_head_tags')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll("button[data-action]").forEach(button => {
                button.addEventListener("click", () => {
                    if (button.getAttribute('data-action') === "login") document.getElementById(
                        "blind").style.left = "50%"
                    else document.getElementById("blind").style.left = "0"
                });
            });
            const errors = document.getElementById("errors");
            const errors_interval = setInterval(() => {
                if (errors.children.length === 0) {
                    errors.remove();
                    clearInterval(errors_interval);
                } else errors.children[0].remove();
            }, 2000);
        });
    </script>
    <style>
        #blind {
            background-color: #EAB221;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25' viewBox='0 0 1600 800'%3E%3Cg fill-opacity='0.34'%3E%3Cpath fill='%23f0be33' d='M486 705.8c-109.3-21.8-223.4-32.2-335.3-19.4C99.5 692.1 49 703 0 719.8V800h843.8c-115.9-33.2-230.8-68.1-347.6-92.2C492.8 707.1 489.4 706.5 486 705.8z'/%3E%3Cpath fill='%23f5c946' d='M1600 0H0v719.8c49-16.8 99.5-27.8 150.7-33.5c111.9-12.7 226-2.4 335.3 19.4c3.4 0.7 6.8 1.4 10.2 2c116.8 24 231.7 59 347.6 92.2H1600V0z'/%3E%3Cpath fill='%23f9d35a' d='M478.4 581c3.2 0.8 6.4 1.7 9.5 2.5c196.2 52.5 388.7 133.5 593.5 176.6c174.2 36.6 349.5 29.2 518.6-10.2V0H0v574.9c52.3-17.6 106.5-27.7 161.1-30.9C268.4 537.4 375.7 554.2 478.4 581z'/%3E%3Cpath fill='%23fcdd6f' d='M0 0v429.4c55.6-18.4 113.5-27.3 171.4-27.7c102.8-0.8 203.2 22.7 299.3 54.5c3 1 5.9 2 8.9 3c183.6 62 365.7 146.1 562.4 192.1c186.7 43.7 376.3 34.4 557.9-12.6V0H0z'/%3E%3Cpath fill='%23FFE585' d='M181.8 259.4c98.2 6 191.9 35.2 281.3 72.1c2.8 1.1 5.5 2.3 8.3 3.4c171 71.6 342.7 158.5 531.3 207.7c198.8 51.8 403.4 40.8 597.3-14.8V0H0v283.2C59 263.6 120.6 255.7 181.8 259.4z'/%3E%3Cpath fill='%23fddd70' d='M1600 0H0v136.3c62.3-20.9 127.7-27.5 192.2-19.2c93.6 12.1 180.5 47.7 263.3 89.6c2.6 1.3 5.1 2.6 7.7 3.9c158.4 81.1 319.7 170.9 500.3 223.2c210.5 61 430.8 49 636.6-16.6V0z'/%3E%3Cpath fill='%23fad45b' d='M454.9 86.3C600.7 177 751.6 269.3 924.1 325c208.6 67.4 431.3 60.8 637.9-5.3c12.8-4.1 25.4-8.4 38.1-12.9V0H288.1c56 21.3 108.7 50.6 159.7 82C450.2 83.4 452.5 84.9 454.9 86.3z'/%3E%3Cpath fill='%23f7ca48' d='M1600 0H498c118.1 85.8 243.5 164.5 386.8 216.2c191.8 69.2 400 74.7 595 21.1c40.8-11.2 81.1-25.2 120.3-41.7V0z'/%3E%3Cpath fill='%23f3c034' d='M1397.5 154.8c47.2-10.6 93.6-25.3 138.6-43.8c21.7-8.9 43-18.8 63.9-29.5V0H643.4c62.9 41.7 129.7 78.2 202.1 107.4C1020.4 178.1 1214.2 196.1 1397.5 154.8z'/%3E%3Cpath fill='%23EEB522' d='M1315.3 72.4c75.3-12.6 148.9-37.1 216.8-72.4h-723C966.8 71 1144.7 101 1315.3 72.4z'/%3E%3C/g%3E%3C/svg%3E");
            background-attachment: fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="bg-gray-200">
    @isset($errors)
        <div id="errors" class="absolute top-0 left-0 w-full pt-10 z-30">
            @foreach ($errors->all() as $error)
                <div class="w-fit p-4 text-red-500 border-2 border-red-500 mx-auto bg-red-100 rounded bg-white mb-3">
                    {{ $error }}</div>
            @endforeach
        </div>
    @endisset

    <div class="px-40 pt-24">
        <div class="grid relative md:grid-cols-2">
            <div class="p-16 bg-white">
                <div class="text-7xl text-amber-200 py-5">
                    <span class="font-medium">Login</span>
                </div>
                <form action="{{ route('auth.login', [], false) }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="text" name="username" id="username">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="username">Username or Email *</label>
                    </div>
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="password" name="password" id="password">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="password">Password *</label>
                    </div>
                    <div class="grid grid-cols-2 my-2">
                        <div>
                            <input class="accent-amber-300 cursor-pointer" type="checkbox" name="remember"
                                id="remember">
                            <label class="pl-1 cursor-pointer" for="remember">Remember Me</label>
                        </div>
                        <div class="text-right">
                            <a href="?" class="italic text-gray-400 text-sm underline hover:no-underline">Forgot
                                Password?</a>
                        </div>
                    </div>
                    <div class="my-6">
                        <button type="submit"
                            class="py-3 px-8 bg-amber-300 border border-amber-400 hover:bg-amber-400 mr-4">
                            Login
                        </button>
                        <button data-action="register" type="button"
                            class="py-3 px-8 bg-gray-200 border border-gray-300 hover:bg-gray-300">
                            Register
                        </button>
                    </div>
                </form>
            </div>
            <div class="p-16 bg-white">
                <div class="text-7xl text-amber-200 py-5">
                    <span class="font-medium">Register</span>
                </div>
                <form action="{{ route('auth.register', [], false) }}" method="POST" autocomplete="off">
                    @csrf
                    <div class="grid grid-cols-2 gap-6 my-6">
                        <div class="relative bg-gray-100">
                            <input placeholder=""
                                class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                                type="text" name="firstname" id="firstname">
                            <label
                                class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                                for="firstname">First Name *</label>
                        </div>
                        <div class="relative bg-gray-100">
                            <input placeholder=""
                                class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                                type="text" name="lastname" id="lastname">
                            <label
                                class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                                for="lastname">Last Name *</label>
                        </div>
                    </div>
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="text" name="username" id="username1">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="username1">Username *</label>
                    </div>
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="email" name="email" id="email">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="email">Email *</label>
                    </div>
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="password" name="password" id="password1">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="password1">Password *</label>
                    </div>
                    <div class="relative bg-gray-100 my-6">
                        <input placeholder=""
                            class="peer placeholder-transparent relative z-10 border-l-8 border-gray-100 focus:border-amber-200 outline-none block w-full pt-5 p-2 bg-transparent"
                            type="password" name="password_confirmation" id="password_confirmation">
                        <label
                            class="pt-1 pb-0 pl-2.5 peer-focus:pt-1 peer-focus:pb-0 peer-focus:pl-2.5 text-xs peer-placeholder-shown:text-base peer-focus:text-xs absolute top-0 left-0 max-h-full w-full transition-all peer-placeholder-shown:py-3.5 peer-placeholder-shown:pl-4"
                            for="password_confirmation">Confirm Password *</label>
                    </div>
                    <div class="my-2">
                        <input class="accent-amber-300 cursor-pointer" type="checkbox" name="remember"
                            id="remember1">
                        <label class="pl-1 cursor-pointer" for="remember1">Remember Me</label>
                    </div>
                    <div class="my-6">
                        <button type="submit"
                            class="py-3 px-8 bg-amber-300 border border-amber-400 hover:bg-amber-400 mr-4">
                            Register
                        </button>
                        <button data-action="login" type="button"
                            class="py-3 px-8 bg-gray-200 border border-gray-300 hover:bg-gray-300">
                            Login
                        </button>
                    </div>
                </form>
            </div>
            <div id="blind"
                class="absolute w-1/2 h-full top-0 left-1/2 z-20 transition-all duration-500 flex items-center justify-center flex-wrap flex-col">
                <img src="/images/logo.svg" alt="Logo" title="{{ env('APP_NAME', 'Cuisine Compass') }}"
                    class="w-96 max-w-full mx-auto block mb-20 rounded grayscale brightness-[4]">
                <div class="text-7xl text-white pb-20 w-full text-center">
                    <span class="font-extralight">Hello,</span>&nbsp;<span class="font-bold">Welcome</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
