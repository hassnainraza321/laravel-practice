<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            @if (Auth::user()->image != null)
                <img src="{{ asset('storage/'. Auth::user()->image) }}" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            @else
                <p style="width: 70px;" class="text-white bg-primary p-3 rounded-circle text-uppercase mx-auto d-block" >{{ substr(Auth::user()->username, 0, 1); }}</p>
            @endif
            
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-black dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-bs-toggle="dropdown">{{ Auth::user()->username }}</a>                   
                <div class="dropdown-menu user-pro-dropdown">

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user me-1"></i>
                        <span>My Account</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings me-1"></i>
                        <span>Settings</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out me-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">@if(Auth::user()->is_admin === 1){{ 'Administrator' }} @else {{ 'Member' }} @endif</p>
        </div>

        <div id="sidebar-menu">

            <ul id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i data-feather="airplay"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li class="menu-title mt-2">Apps</li>

                @if (Auth::user()->is_admin === 1)
                    <li>
                        <a href="{{ route('packages.add') }}">
                            <i data-feather="plus"></i>
                            <span> Add Packages </span>
                        </a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('packages') }}">
                        <i data-feather="package"></i>
                        <span> Packages </span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('articles') }}">
                        <i data-feather="calendar"></i>
                        <span> Articles </span>
                    </a>
                </li>

                @if (Auth::user()->is_admin === 1)
                    <li>
                        <a href="{{ route('magazines') }}">
                            <i data-feather="layers"></i>
                            <span> Magazines </span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->is_admin === 1)
                    <li>
                        <a href="{{ route('contact.list') }}">
                            <i data-feather="phone"></i>
                            <span> Contact Us </span>
                        </a>
                    </li>
                @endif


                <li>
                    <a href="{{ route('comment.list') }}">
                        <i data-feather="message-circle"></i>
                        @php if (Auth::user()->is_admin === 1) {
                                $comments = DB::table('comments')->where('status', 0)->get();
                            }else{
                                $articles = DB::table('articles')->where('user_id', Auth::id())->pluck('id')->toArray();
                                $comments = DB::table('comments')->whereIn('article_id', $articles)->where('status', 0)->get();
                            } @endphp
                        <span class="badge bg-danger rounded-pill float-end">{{ count($comments) }}</span>
                        <span> Comments </span>
                    </a>
                </li>

                @if (Auth::user()->is_admin === 1)
                    <li>
                        <a href="{{ route('users') }}">
                            <i data-feather="users"></i>
                            <span> Users </span>
                        </a>
                    </li>
                @endif

            </ul>

        </div>

        <div class="clearfix"></div>

    </div>

</div>