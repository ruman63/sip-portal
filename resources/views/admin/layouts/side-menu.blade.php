<aside class="sidebar">
    <div class="py-8 px-4">
        <ul class="menu">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('clients.index') }}">Clients</a></li>
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
</aside>