<modal name="register" height="auto">
    <form method="POST" action="{{ route('register') }}" class="px-6 py-8">
        {{ csrf_field() }}

        <h3 class="text-lg uppercase tracking-wide font-semibold mb-6">Register New Client</h3>
        
        <div class="flex flex-wrap">
            <div class="field w-1/2 px-1">
                <label for="first_name" class="control">First Name</label>
                <input type="text" name="first_name" id="first_name" class="control" value="{{ old('first_name') }}" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('first_name') }}</span>
            </div>
            <div class="field w-1/2 px-1">
                <label for="last_name" class="control">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="control" value="{{ old('last_name') }}" required>
            </div>
            <div class="field w-1/2 px-1">
                <label for="email" class="control">Email</label>
                <input type="email" name="email" id="email" class="control" value="{{ old('email') }}" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('email') }}</span>
            </div>
            <div class="field w-1/2 px-1">
                <label for="pan" class="control">PAN Number</label>
                <input type="text" name="pan" id="pan" class="control" value="{{ old('pan') }}" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('pan') }}</span>
            </div>
            <div class="field w-1/2 px-1">
                <label for="mobile" class="control">Phone</label>
                <div class="flex">
                    <span class="bg-grey-lighter border-l border-t border-b px-2 text-sm flex items-center">+91</span>
                    <input type="text" name="mobile" id="mobile" class="control flex-grow" value="{{ old('mobile') }}" required>
                </div>
                <span class="text-red text-xs mt-1">{{ $errors->first('mobile') }}</span>                
            </div>
            <div class="field w-full px-1">
                <label for="password" class="control">Password</label>
                <input type="password" name="password" id="password" class="control" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('password') }}</span>
            </div>
            <div class="field w-full px-1">
                <label for="password_confirmation" class="control">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="control" required>
            </div>
            <div class="field w-1/2 px-1">
                <label for="dob" class="control">Birthdate</label>
                <input type="date" name="dob" id="dob" class="control" value="{{ old('dob') }}" required>
                <span class="text-red text-xs mt-1">{{ $errors->first('dob') }}</span>
            </div>
            <div class="field w-1/2 px-1">
                <label for="gender" class="control">Gender</label>
                <select name="gender" id="gender" class="control" required>
                    <option disabled {{ old('gender') == null ? 'selected' : '' }}> Gender </option>
                    <option {{ old('gender') == 'm' ? 'selected' : '' }} value="m"> Male </option>
                    <option {{ old('gender') == 'f' ? 'selected' : '' }} value="f"> Female </option>
                </select>
                <span class="text-red text-xs mt-1">{{ $errors->first('gender') }}</span>
            </div>
        </div>
        <div class="flex flex-row-reverse mb-2">
            <button type="submit" class="btn text-sm uppercase is-blue mx-1">Submit</button>
            <button type="button" class="btn text-sm uppercase mx-1" @click="$modal.hide('register')">Cancel</button>
        </div>

    </form>
</modal>