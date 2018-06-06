<nav class="py-2 bg-blue-darker text-white flex px-8">
    <div class="flex flex-1 items-center">
        <h1 class="flex">
            <a href="/" class="text-xl font-normal text-white ml-2 no-underline">SIP Wealth</a>
        </h1>
    </div>
    <div class="flex flex-1 flex-row-reverse items-center">

        @if(Auth::guard('cpanel')->check())
            <dropdown heading="{{ Auth::guard('cpanel')->user()->name }}">
                <template slot="button">
                    <span class="relative z-10 p-1 bg-blue-darkest text-white flex items-center justify-center rounded-full w-8 h-8">
                        <i class="fa fa-user"></i>
                    </span>
                </template>
                <template slot="menu">
                    <li class="py-1"><a href="#">Profile</a></li>
                    <li class="py-1"><a href="#">Change Password</a></li>
                    <li class="py-1">
                        <logout path="{{ route('admin.logout') }}"></logout>
                    </li>
                </template>
            </dropdown>
        @endif
        <clock class="mx-2 px-4"></clock>

    </div>
</nav>