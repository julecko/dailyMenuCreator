<header class="flex w-full" style="height: 50px">
    <div class="w-1/2 h-full">

    </div>
    <nav class="flex justify-end w-full items-center bg-gray-800">
        <a href="{{ route('menuPage') }}" class="navItem">Menu</a>
        <a href="{{ route('editPage') }}" class="navItem">Editor</a>
        <a href="{{ route('aboutPage') }}" class="navItem">About</a>
    </nav>
    <div class="verticalLine"></div>
    <img src="{{ asset('assets/default_user.png') }}" class="rounded-full mx-9" alt="default user">
</header>
<hr class="border-t-1 border-gray-300" />
