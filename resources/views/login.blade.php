<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Menu Creator</title>
    <script>
        window.onload = function(){
            const password = document.getElementById('password');
            const toggler = document.getElementById('toggler');
            showHidePassword = () => {
                if (password.type !== 'password') {
                    password.setAttribute('type', 'password');
                    password.style.letterSpacing = "1px";
                    toggler.classList.add('fa-eye-slash');
                } else {
                    password.style.letterSpacing = "0px";
                    toggler.classList.remove('fa-eye-slash');
                    password.setAttribute('type', 'text');
                }
            };
            toggler.addEventListener('click', showHidePassword);
        }
    </script>
</head>
<body class="flex w-full h-full items-center justify-center" id="loginPageBody">
    <form id="loginBox" class="gap-1" method="POST" action="{{ route('loginAuth') }}">
        @csrf
        <p class="w-full text-center text-5xl font-sans">Menu Creator</p>
        <p class="text-orange-500 text-2xl py-5">Vitajte, prihláste sa</p>

        <label>Užívateľské meno <span class="text-red-500">*</span></label>
        <input type="text" class="loginInput" required>

        <label>Heslo <span class="text-red-500">*</span></label>
        <div id="pass-wrapper" class="relative">
            <input type="password" id="password" class="loginInput" required>
            <div class="icon-container">
                <span><i id="toggler" class="far fa-eye fa-eye-slash"></i></span>
            </div>
        </div>

        <button class="h-10 bg-orange-500 text-white mt-7 px-4 rounded">Prihláste sa</button>
    </form>
</body>
</html>
