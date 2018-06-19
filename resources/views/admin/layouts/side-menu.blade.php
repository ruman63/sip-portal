<aside class="sidebar h-full overflow-auto flex flex-col justify-between py-6">
    <div class="flex px-4">
        <img src="{{ asset('storage/avatars/default.png') }}" alt="User Avatar" class="rounded-full w-16 h-16">
        <div class="flex-1 flex flex-col justify-center pl-2">
            <div class="text-center text-lg">{{ auth()->guard('cpanel')->user()->name }}</div>
            <div class="text-grey-dark text-sm italic font-thin text-center">{{ auth()->guard('cpanel')->user()->username }}</div>
        </div>
    </div>
    <div class="py-8 px-4">
        <ul class="menu">
            <li><a href="{{ route('admin.dashboard') }}"><span> <i class="text-blue-darker fa fa-dashboard mr-1"></i> Dashboard</span></a></li>
            <li><a href="{{ route('clients.index') }}"><span> <i class="text-blue-darker fa fa-user mr-1"></i> Clients</span></a></li>
            <li><a href="{{ route('admin.transactions.index') }}"><span> <i class="text-blue-darker fa fa-exchange mr-1"></i> Transactions</span></a></li>
            <li><a href="{{ route('admin.sip.index') }}"><span> <i class="text-blue-darker fa fa-gears mr-1"></i> Manage SIP<span class="lowercase">(s)</span></span></a></li>
            <li><a href="{{ route('admin.schemes.index') }}"> <span><i class="text-blue-darker fa fa-list mr-1"></i> Schemes</span> </a></li>
            <expandable-list-item>
                <span> <i class="text-blue-darker fa fa-upload mr-1"></i> Import Portfolios</span>
                <template slot="items">
                    <li><a @click.prevent="$modal.show('import-csv-data')" class="cursor-pointer">CSV from CAMS</a></li>
                </template>
            </expandable-list-item>
            <li><a href=""><span> <i class="text-blue-darker fa fa-file-text user mr-1"></i> AUM Report</span></a></li>
            <li><a href=""><span> <i class="text-blue-darker fa fa-users mr-1"></i> Folio Query</span></a></li>
            <li><a href=""><span> <i class="text-blue-darker fa fa-money mr-1"></i> Portfolio Returns</span></a></li>
            <expandable-list-item>
                <span> <i class="text-blue-darker fa fa-star mr-1"></i> BSE Star MF</span>
                <template slot="items">
                    <li><a href="#">New Client</a></li>
                    <li><a href="#">New Order Entry</a></li>
                    <li><a href="#">Additional Purchase</a></li>
                </template>
            </expandable-list-item>
            <expandable-list-item>
                <span> <i class="text-blue-darker fa fa-file-text mr-1"></i> Reports Zone</span>
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