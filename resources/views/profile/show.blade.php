@extends('layouts.master') 
@section('content')
<ul class="breadcrumbs">
    <li><a href="{{ route('dashboard') }}"> Home </a></li>
    <li> Profile </li>
</ul>
<section class="py-8">
    <header class="page-header">
        <h1>{{ $client->name }}</h1>
    </header>
    <section class="mb-4 py-8">
        <div class="flex items-center py-1 mb-4">
            <h2 class="mr-4">Personal Information</h2>
            <button class="btn text-xs py-1 px-2 rounded">
                <i class="fa fa-edit"></i>
            </button>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">First Name</div>
            <div class="w-5/6 px-1">{{ $client->first_name }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Last Name</div>
            <div class="w-5/6 px-1">{{ $client->last_name }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Date of Birth</div>
            <div class="w-5/6 px-1">{{ $client->dob }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Pan Number</div>
            <div class="w-5/6 px-1">{{ $client->pan }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Father / Husband / Guardian</div>
            <div class="w-5/6 px-1">{{ $client->guardian }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Guardian Pan</div>
            <div class="w-5/6 px-1">{{ $client->guardian_pan }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Gender</div>
            <div class="w-5/6 px-1">{{ $client->gender == 'M' ? 'Male' : 'Female' }}</div>
        </div>
    </section>

    <section class="mb-4 py-8">
        <div class="flex items-center py-1 mb-4">
            <h2 class="mr-4">Contact Information</h2>
            <button class="btn text-xs py-1 px-2 rounded">
                <i class="fa fa-edit"></i>
            </button>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Email</div>
            <div class="w-5/6 px-1">{{ $client->email }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Mobile</div>
            <div class="w-5/6 px-1">{{ $client->mobile }}</div>
        </div>
        @isset($client->address)
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Address</div>
            <div class="w-5/6 px-1">
                <p>{{ $client->address->first_line }}</p>
                <p>{{ $client->address->second_line }}</p>
                <p>{{ $client->address->third_line }}</p>
            </div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">City</div>
            <div class="w-5/6 px-1">{{ $client->address->city }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">State</div>
            <div class="w-5/6 px-1">{{ $client->address->state }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Postal Code</div>
            <div class="w-5/6 px-1">{{ $client->address->pincode }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Country</div>
            <div class="w-5/6 px-1">{{ $client->address->country }}</div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Phone</div>
            <div class="w-5/6 px-1">
                <p>
                    {{ $client->address->residence_phone }}
                </p>
                <p>
                    {{ $client->address->office_phone }}
                </p>
            </div>
        </div>
        <div class="flex py-1 border-b -mx-1">
            <div class="font-bold w-1/6 px-1">Fax</div>
            <div class="w-5/6 px-1">
                <p>
                    {{ $client->address->residence_fax }}
                </p>
                <p>
                    {{ $client->address->office_fax }}
                </p>
            </div>
        </div>
        @endisset
    </section>

    
    <div class="mb-4 py-8">
        <div class="flex items-center mt-2 mb-6">
            <h2 class="mr-4">Bank Account Details</h2>
        </div>
        @foreach($client->bankAccounts as $bank)
            <div class="border rounded shadow mb-2 p-4">
                <p>Account Number: {{ $bank->account_number }}</p>
                <p>Account Type: {{ $bank->account_type_code }}</p>
                <p>Bank IFSC Code: {{ $bank->ifsc_code }}</p>
                <p>Micr No: {{ $bank->micr }}</p>
                <p class="mt-2">
                    <button class="btn is-blue text-xs py-1 px-2 rounded">
                        <i class="fa fa-edit"></i>
                    </button>
                    @if($bank->isDefault)
                        <span class="bg-blue-darker text-white p-1 rounded text-xs uppercase tracking-wide leading-none font-bold">Default</span>
                    @endif
                </p>
            </div>
        @endforeach
    </div>
</section>
@endsection