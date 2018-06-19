<modal name="login" height="auto">
    <form method="POST" action="{{ route('login') }}" class="px-6 py-8">
        {{ csrf_field() }}

        <h3 class="text-lg uppercase tracking-wide font-semibold mb-6">Client's Login</h3>
        
        <div class="flex flex-wrap mb-4">
            <div class="field w-full px-1">
                <label for="user" class="control">Email Address / Pan no.</label>
                <input type="text" name="user" id="user" class="control" value="{{ old('user') }}" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('user') }}</span>
            </div>
            <div class="field w-full px-1">
                <label for="password" class="control">Password</label>
                <input type="password" name="password" id="password" class="control" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('password') }}</span>
            </div>
            <div class="field">
                <label for="remmember" class="control flex items-center cursor-pointer"> 
                    Remmember Me? <input type="checkbox" name="remmember" id="remmember" class="ml-2">
                </label>
            </div>
        </div>
        <div class="flex flex-row-reverse">
            <button type="submit" class="btn text-sm uppercase is-blue mx-1">Login</button>
            <button type="button" class="btn text-sm uppercase mx-1" @click="$modal.hide('login')">Cancel</button>
        </div>

    </form>
</modal>