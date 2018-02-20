<nav class="bg-teal-dark text-white">
    <div class="container mx-auto flex">
        <div class="flex flex-1 items-center">
            
            <h1 class="text-xl font-normal text-white ml-2 py-3">SIP Wealth</h1>
            
        </div>
        <ul class="flex list-reset flex-1 flex-row-reverse items-center">

            @if(Auth::check())
            <li>
                <dropdown heading="Hello! {{ Auth::user()->first_name }}">
                    <template slot="button">
                        <span class="relative z-10 p-1 bg-pink-darkest text-white flex items-center justify-center rounded-full w-8 h-8">
                            <i class="fa fa-user"></i>
                        </span>
                    </template>
                    <template slot="menu">
                        <li class="py-1"><a href="#">Profile</a></li>
                        <li class="py-1"><a href="#">Change Password</a></li>
                        <li class="py-1">
                            <logout path="{{ route('logout') }}"></logout>
                        </li>
                    </template>
                </dropdown>
            </li>
            @else
                <li class="flex">
                    <a href="#" class="py-3 px-3 text-white hover:bg-teal-darker" @click="$modal.show('register')">Register</a>
                </li>
                <li class="flex">
                    <a href="#" class="py-3 px-3 text-white hover:bg-teal-darker" @click="$modal.show('login')">Login</a>
                </li>
            @endif
            <li>
                <clock class="mx-2 px-4"></clock>
            </li>

        </ul>
    </div>
</nav>