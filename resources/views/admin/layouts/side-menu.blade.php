<aside class="sidebar h-full overflow-auto flex flex-col justify-between py-6">
    <div class="flex px-4">
        <img src="{{ asset('storage/avatars/default.png') }}" alt="User Avatar" class="rounded-full w-16 h-16">
        <div class="flex-1 flex flex-col justify-center pl-2">
            <div class="text-center text-lg">Super Admin</div>
            <div class="text-grey-dark text-sm italic font-thin text-center">Superadmin</div>
        </div>
    </div>
    <div class="py-8 px-4">
        <ul class="menu">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('clients.index') }}">Clients</a></li>
            <li><a href="{{ route('admin.transactions.index') }}">Transactions</a></li>
            <li><a href="{{ route('admin.sip.index') }}">Systematic Investment Plan</a></li>
            <li><a href="">AUM Report</a></li>
            <li><a href="">Folio Query</a></li>
            <li><a href="">Portfolio Returns</a></li>
            <expandable-list-item>
                BSE Star MF
                <template slot="items">
                    <li><a href="#">New Client</a></li>
                    <li><a href="#">New Order Entry</a></li>
                    <li><a href="#">Additional Purchase</a></li>
                </template>
            </expandable-list-item>
            <expandable-list-item>
                Reports Zone
                <template slot="items">
                    <li><a href="#">Cash Flow</a></li>
                    <li><a href="#">Business Reports</a></li>
                    <li><a href="#">Login Summary</a></li>
                </template>
            </expandable-list-item>
        </ul>
    </div>
    <div class="flex">
        
    </div>
</aside>