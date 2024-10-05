<header class="flex w-full items-center" style="height: 50px">
    <span id="title"><img src="{{ asset('assets/foodLogo.png') }}" alt="foodIcon" class="text-center items-center mr-2" style="width: 25px">Menu Creator</span>
    <div class="verticalLine"></div>
    <nav class="navPanel">
        <a href="{{ route('menuPage') }}" class="navItem">Menu</a>
        <a href="{{ route('editPage') }}" class="navItem">Editor</a>
        <a href="{{ route('aboutPage') }}" class="navItem">About</a>
    </nav>
</header>
<hr class="border-t-1 border-gray-300" />
