@extends('admin.layouts.master')
@section('content')

    <ul class="list-reset text-xs flex mb-6">
        <li class="px-2 py-1 text-grey-dark">
            <a href="{{ route('admin.dashboard') }}">Home</a>
        </li>
        <li class="px-1 py-1 text-grey-dark">
            <i class="fa fa-arrow-right"></i>
        </li>
        <li class="px-2 py-1 text-grey-dark"> Clients </li>
    </ul>
    <section class="px-2">
        <div class="flex border-b pb-1 mb-4">
            <h2 class="flex items-center flex-grow text-blue-darkest tracking-wide font-semibold uppercase">Manage Clients</h2>
            <button class="btn is-blue" @click="$modal.show('client-create')"> 
                <i class="fa fa-user-plus mr-1"></i> New Client
            </button>
        </div>
        <div class="flex flex-row-reverse px-2">
            <form action="" method="GET" class="text-sm mb-2">
                <div class="flex items-strech text-xs">
                    <input type="text" class="flex-grow border-t border-b border-l py-2 px-2 focus:border-blue-darker" placeholder="Search Clients">
                    <button type="submit" class="px-3 bg-blue-darker text-white">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="p-3 w-full overflow-x-scroll">
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Member Since</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->first_name }} {{ $client->last_name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->mobile }}</td>
                            <td>{{ $client->created_at->diffForHumans() }}</td>
                            <td>
                                <button class="btn is-blue" title="Login"><i class="fa fa-sign-in"></i></button>
                                <button class="btn is-blue" title="Fill Details"> <i class="fa fa-pencil-square-o"></i> </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center">No Clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
            {{--  <section class="mb-6">
                <h3 class="text-sm uppercase tracking-wide text-grey-darker font-semibold px-1 mb-4"> Personal Information </h3>
                <div class="flex flex-wrap mb-2">
                    <div class="field w-1/2 px-1">
                        <label for="name" class="control">Full Name</label>
                        <input type="text" name="name" class="control" placeholder="e.g. Amit Kumar" value="{{ old('name') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="pan" class="control">Pan no</label>
                        <input type="text" name="pan" class="control" placeholder="e.g. ABCDE1234F" value="{{ old('pan') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="mapin" class="control">MAPIN Number</label>
                        <input type="text" name="mapin" class="control" placeholder="e.g. 110019"  value="{{ old('mapin') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="dob" class="control">Date of Birth</label>
                        <input type="date" name="dob" class="control"  value="{{ old('dob') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="gender" class="control">Gender</label>
                        <select name="gender" class="control">
                            <option disabled {{ old('gender') == null ? 'selected' : ''}}>Select Gender</option>
                            <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="occupation" class="control">Occupation</label>
                        <select name="occupation" class="control">
                            <option disabled {{ old('occupation') == null ?  'selected' : ''}}>Select Occupation</option>
                            <option value="student" {{ old('occupation') == 'student' ?  'selected' : ''}}>Student</option>
                            <option value="others" {{ old('occupation') == 'others' ?  'selected' : ''}}>Others</option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="tax_status" class="control">Tax Status</label>
                        <select name="tax_status" class="control">
                            <option disabled {{ old('tax_status') == null ?  'selected' : ''}}>Select Tax Status</option>
                            <option value="payee" {{ old('tax_status') == 'payee' ?  'selected' : ''}}>Tax Payee</option>
                            <option value="nonpayee" {{ old('tax_status') == 'nonpayee' ?  'selected' : ''}}>Non Tax Payee</option>
                        </select>
                    </div>
                    <div class="field w-1/3 px-1">
                        <label for="father_name" class="control">Father Name</label>
                        <input type="text" name="father_name" class="control" placeholder="e.g. Saurabh Kumar" value="{{  old('father_name') }}">
                    </div>
                    <div class="field w-1/3 px-1">
                        <label for="guardian_name" class="control">Guardian Name</label>
                        <input type="text" name="guardian_name" class="control" placeholder="e.g. Saurabh Kumar" value="{{ old('guardian_name') }}">
                    </div>
                    <div class="field w-1/3 px-1">
                        <label for="guardian_pan" class="control">Guardian Pan no</label>
                        <input type="text" name="guardian_pan" class="control" placeholder="e.g. EDCBA4321A" value="{{ old('guardian_pan') }}">
                    </div>
                </div>
            </section>

            <section class="mb-6">
                <h3 class="text-sm uppercase tracking-wide text-grey-darker font-semibold px-1 mb-4"> Contact Information </h3>
                
                <div class="flex flex-wrap mb-2">
                    <div class="field w-1/4 px-1">
                        <label for="hno" class="control">House No / Street</label>
                        <input type="text" name="hno" class="control" placeholder="e.g. E-10/12" value="{{ old('hno') }}">
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="area" class="control">Area</label>
                        <input type="text" name="area" class="control" placeholder="e.g. E-Block, GK-I" value="{{ old('area') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="city" class="control">City</label>
                        <input type="text" name="city" class="control" placeholder="e.g. New Delhi" value="{{ old('city') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="state" class="control">State</label>
                        <select name="state" class="control">
                            <option disabled {{ old('state') == null ? 'selected' : '' }}> Select State </option>
                            <option value="MH" {{ old('state') == 'MH' ? 'selected' : '' }}>Maharashtra</option>
                            <option value="DL" {{ old('state') == 'DL' ? 'selected' : '' }}>Delhi</option>
                            <option value="UP" {{ old('state') == 'UP' ? 'selected' : '' }}>Uttar Pradesh</option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="pincode" class="control">Pincode</label>
                        <input type="text" name="pincode" class="control" placeholder="e.g. 110019" value="{{ old('pincode') }}">
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="country" class="control">Country</label>
                        <select name="country" disabled class="control">
                            <option selected disabled value="IN"> India </option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="mobile" class="control">Mobile Number</label>
                        <input type="text" name="mobile" class="control" placeholder="e.g. 9876543210" value="{{ old('mobile') }}">
    
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="email" class="control">Email</label>
                        <input type="email" name="email" class="control" placeholder="amit21@gmail.com" value="{{ old('email') }}">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="communication_mode" class="control">Communication Mode</label>
                        <select name="communication_mode" class="control">
                            <option disabled {{ old('communication_mode') == null ? 'selected' : '' }}> Mode of Commmunication </option>
                            <option value="physical" {{ old('communication_mode') == 'physical' ? 'selected' : '' }}>Physical Meeting</option>
                            <option value="phone" {{ old('communication_mode') == 'phone' ? 'selected' : '' }}>Mobile</option>
                            <option value="email" {{ old('communication_mode') == 'email' ? 'selected' : '' }}>Email</option>
                        </select>
                    </div>
                </div>
            </section>

            <section class="mb-6">
                <h3 class="text-sm uppercase tracking-wide text-grey-darker font-semibold px-1 mb-4"> Account Information </h3>
                
                <div class="flex flex-wrap">
                    <div class="field w-1/4 px-1">
                        <label for="account_type" class="control">Account Type</label>
                        <select name="account_type" class="control">
                            <option {{ old('account_type') == null ? 'selected' : '' }} disabled> Account Type </option>
                            <option value="AO">Anyone/Survivor</option>
                            <option value="JO">Joint</option>
                            <option value="SI">Single</option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="pay_mode" class="control">Pay Mode</label>
                        <select name="pay_mode" class="control">
                            <option selected disabled> Dividend Pay Mode </option>
                            <option value="AO">DEBT</option>
                            <option value="JO">CHEQUE</option>
                            <option value="SI">CASH</option>
                            <option value="SI">NEFT</option>
                        </select>
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="nominee_name" class="control">Nominee Name</label>
                        <input type="text" name="nominee_name" class="control" placeholder="e.g. Hari Seghal">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="nominee_relation" class="control">Nominee Relation</label>
                        <input type="text" name="nominee_relation" class="control" placeholder="e.g. Son">
                    </div>
                </div>
    
                <h5 class="text-xs uppercase tracking-wide text-grey-darker font-semibold px-1 mb-4"> Applicants </h3>
                <div class="flex flex-wrap mb-2">
                    <div class="field w-1/4 px-1">
                        <label for="applicant_name1" class="control"> First Applicant Name</label>
                        <input type="text" name="applicant_name1" class="control" placeholder="e.g. Amit Kumar">
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="applicant_email1" class="control"> First Applicant Email</label>
                        <input type="email" name="applicant_email1" class="control" placeholder="e.g. amitk22@gmail.com">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="applicant_pan1" class="control"> First Applicant PAN</label>
                        <input type="text" name="applicant_pan1" class="control" placeholder="e.g. ZXYVW9876A">
                    </div>
    
                    <div class="field w-1/4 px-1">
                        <label for="applicant_name2" class="control"> Second Applicant Name</label>
                        <input type="text" name="applicant_name2" class="control" placeholder="e.g. Amit Kumar">
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="applicant_email2" class="control"> Second Applicant Email</label>
                        <input type="email" name="applicant_email2" class="control" placeholder="e.g. amitk22@gmail.com">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="applicant_pan2" class="control"> Second Applicant PAN</label>
                        <input type="text" name="applicant_pan2" class="control" placeholder="e.g. ZXYVW9876A">
                    </div>
                </div>
            </section>


            <section class="mb-3">
                <h3 class="text-sm uppercase tracking-wide text-grey-darker font-semibold px-1 mb-4"> Bank Details </h3>
                <div class="flex flex-wrap">
                    <div class="field w-1/4 px-1">
                        <label class="control">Bank</label>
                        <input type="text" name="bank_account[bank]" class="control" placeholder="e.g. State Bank of India">
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="bank_account[branch]" class="control">Branch Name</label>
                        <input type="text" name="bank_account[branch]" class="control" placeholder="e.g. Nehru Place, New Delhi">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="bank_account[account_type]" class="control">Account Type</label>
                        <select name="bank_account[account_type]" class="control">
                            <option disabled selected >Account Type</option>
                            <option value="savings">Savings Account</option>
                            <option value="current">Current Account</option>
                        </select>
                    </div>
                    <div class="field w-1/2 px-1">
                        <label for="bank_account[account_number]" class="control">Account Number</label>
                        <input name="bank_account[account_number]" type="text" class="control" placeholder="e.g. 34567890124">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="bank_account[ifsc]" class="control">IFSC Code</label>
                        <input name="bank_account[ifsc]" type=`"text" class="control" placeholder="e.g. SBIN0XXXXXX">
                    </div>
                    <div class="field w-1/4 px-1">
                        <label for="bank_account[micr]" class="control">MICR Number</label>
                        <input name="bank_account[micr]" type="text" class="control" placeholder="Bank's MICR code">
                    </div>
                </div>
            </section>  --}}

            

@endsection