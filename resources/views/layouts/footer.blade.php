<footer class="bg-grey-lightest border-solid border-grey-light border-t py-1 text-sm">
    <div class="container mx-auto">
        <div class="flex">
            <div class="flex-1">
                Last Update (NAVs): 
                <span class="{{ $lastUpdatedNav ? 'text-green' : 'text-red' }}">
                    {{ $lastUpdatedNav ? $lastUpdatedNav->toDateString() : 'Not Updated' }}
                </span>
            </div>
            <clock class="px-2"></clock>
        </div>
    </div>
</footer>