@extends('admin.layouts.master')
@section('breadcrumbs')
    <li> Schemes </li>
@endsection
@section('content')
    <section>
        <header class="page-header">
            <h2>Manage Schemes</h2>
        </header>
    </section>
    <schemes-view inline-template>
        <section class="mb-8">
            <div class="flex py-2 mb-4">
                <div class="flex-1 flex">
                    <input type="text" placeholder="Search Schemes..." v-model="query" @input="search" class="control">
                    <select class="control">
                        <option value="placeholder" disabled selected>
                            -- Filter By Type--
                        </option>
                        <option value="DEBT">DEBT</option>
                        <option value="ELSS">ELSS</option>
                        <option value="EQUITY">EQUITY</option>
                        <option value="HYBRID">HYBRID</option>
                    </select>
                    <select class="control">
                        <option value="placeholder" disabled selected>
                            -- Filter by RTA Agent--
                        </option>
                        <option value="CAMS">CAMS</option>
                        <option value="FRANKLIN">FRANKLIN</option>
                        <option value="KARVY">KARVY</option>
                        <option value="SUNDARAM">SUNDARAM</option>
                    </select>
                </div>
                <button @click="$modal.show('upload-schemes')" class="ml-auto btn is-blue">
                    <i class="fa fa-upload"></i> Upload Schemes
                </button>
            </div>
            <v-data-table
                :data="schemes"
                :names="['scheme_code', 'isin', 'scheme_type', 'scheme_name', 'scheme_plan', 'rta_agent_code', 'nav']"
                :labels="['Scheme Code', 'isin', 'Type', 'Name', 'Plan', 'RTA Agent', 'Net Asset Value']"
                >
            </v-data-table>
            <modal name="upload-schemes">
                <v-ajax-form action="{{ route('admin.schemes.store') }}" method="post" 
                    :use-form-data="true"
                    @success="onSuccess" @failure="onFailure">
                    <form slot-scope="{ form, updateFile, submit }" class="flex h-full flex-col" @submit.prevent="submit">
                        <div class="absolute pin-t pin-r mt-4 mr-4">
                            <button class="font-bold text-2xl leading-none p-1 hover:text-red text-grey-dark" >&times;</button>
                        </div>
                        <div class="flex-1 flex flex-col justify-center items-center p-6">
                            <input type="file" name="schemesFile" @input="updateFile">
                        </div>
                        <div class="flex mx-6 mb-6">
                            <button type="button" class="flex-1 btn mx-1" > Cancel </button>
                            <button type="submit" class="flex-1 btn mx-1 is-blue"> Submit </button>
                        </div>
                    </form>
                </v-ajax-form>
            </modal>
        </section>
    </schemes-view>
@endsection