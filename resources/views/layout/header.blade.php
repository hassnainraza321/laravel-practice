<header class="bg-dark d-flex justify-content-between">
     <div class="text-center text-white p-3">
         <h4>{{ env('APP_NAME') }}</h4>
     </div>
     <div class="d-flex align-items-center">
        @if (!auth()->check())
            <a class="text-decoration-none text-white fetch-dynamic-modal mx-1" data-url="{{ route('login') }}">Login</a>
            <a class="text-decoration-none text-white mx-2 fetch-dynamic-modal mx-3" data-url="{{ route('register') }}">Register</a>
        @else
            <a class="text-decoration-none text-white mx-3" href="{{ route('logout') }}">Logout</a>
        @endif
     </div>   
</header>
