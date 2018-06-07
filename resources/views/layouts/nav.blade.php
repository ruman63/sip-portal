@if(auth()->guard('web')->check() && auth()->guard('cpanel')->check())
    <nav class="bg-grey-darker text-white">
        <div class="container mx-auto">
            <div class="flex">
                <div class="flex-1 flex items-center">
                    You are logged in as {{ auth()->guard('web')->user()->first_name }} ( {{ auth()->guard('web')->user()->email }} )
                </div>
                <div class="flex flex-row-reverse items-center">
                    <form action="{{ route('clients.logout') }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="bg-red-dark py-2 px-3 text-white"> 
                            <i class="fa fa-times mr-1"></i> Exit Access 
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
@endif
<nav class="bg-blue-darker text-white">
    <div class="container mx-auto flex">
        <div class="flex flex-1 mr-6 items-center">
            
            <h1 class="flex">
                <a href="/" class="text-xl font-normal text-white ml-2 py-3 no-underline">SIP Wealth</a>
            </h1>
            
            <ul class="list-reset flex flex-1 items-center justify-end">
                @if(Auth::guard('web')->check())
                    <li class="flex has-menu">
                        <span class="px-2 py-2 text-xs tracking-wide uppercase text-white">
                            Portfolio <i class="ml-1 fa fa-caret-down"></i>
                        </span>
                        <ul class="sub-menu">
                            <li><a class="py-1 px-3 hover:bg-blue-dark hover:text-white block" href="{{ route('portfolios.index') }}">Summary</a></li>
                        </ul>
                    </li>
                    <li class="flex">
                        <a class="px-2 py-2 text-xs tracking-wide uppercase text-white" 
                            href="{{ route('allocations.index') }}">
                            Allocations
                        </a>
                    </li>
                    <li class="flex">
                        <a class="px-2 py-2 text-xs tracking-wide uppercase text-white" 
                            href="{{ route('transactions.index') }}">
                            Transactions          
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <ul class="flex list-reset justify-end items-center">
            @if(Auth::check())
            <li>
                <dropdown heading="Hello! {{ Auth::user()->first_name }}">
                    <template slot="button">
                        <span class="relative z-10 p-1 bg-teal-darkest text-white flex items-center justify-center rounded-full w-8 h-8">
                            <i class="fa fa-user"></i>
                        </span>
                    </template>
                    <template slot="menu">
                        <li class="py-1"><a href="#">Profile</a></li>
                        <li class="py-1"><a href="{{ route('password.edit') }}">Change Password</a></li>
                        @if(auth()->guard('cpanel')->guest())
                            <li class="py-1">
                                <logout path="{{ route('logout') }}"></logout>
                            </li>
                        @endif
                    </template>
                </dropdown>
            </li>
            @endif

        </ul>
    </div>
</nav>