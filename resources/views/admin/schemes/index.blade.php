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
                    <select class="control" v-model="type" @change="fetchSchemes">
                        <option value="" disabled selected>
                            -- Filter By Type--
                        </option>
                        <option v-for="scheme_type in types" :key="scheme_type" :value="scheme_type" v-text="scheme_type"></option>
                    </select>
                    <select class="control" v-model="agent" @change="fetchSchemes">
                        <option value="" disabled selected>
                            -- Filter by RTA Agent--
                        </option>
                        <option v-for="rta_agent in agents" :key="rta_agent" :value="rta_agent" v-text="rta_agent"></option>
                    </select>
                </div>
                <button class="btn text-xs" @click="clear"><i class="text-red fa fa-ban mr-2"></i>Clear</button>
                <button @click="$modal.show('upload-schemes')" class="ml-auto btn is-blue">
                    <i class="fa fa-upload"></i> Upload Schemes
                </button>
            </div>
            <v-data-table
                :data="schemes"
                :names="['scheme_code', 'isin', 'scheme_type', 'scheme_name', 'scheme_plan', 'rta_agent_code', 'nav']"
                :labels="['Scheme Code', 'isin', 'Type', 'Name', 'Plan', 'RTA Agent', 'Net Asset Value']"
                >
                <template slot-scope="{ item }">
                    <tr :key="item.scheme_code">
                        <td v-text="item.scheme_code"></td>
                        <td v-text="item.isin"></td>
                        <td v-text="item.scheme_type"></td>
                        <td v-text="item.scheme_name"></td>
                        <td v-text="item.scheme_plan"></td>
                        <td v-text="item.rta_agent_code ? item.rta_agent_code : 'Unknown'"></td>
                        <td class="text-right" v-if="item.nav">@{{ item.nav | currency }} &#x20B9;</td>
                        <td class="text-right" v-else>Not updated yet</td>
                    </tr>
                </template>
            </v-data-table>
            <modal name="upload-schemes">
                <v-ajax-form action="{{ route('admin.schemes.store') }}" method="post" 
                    :use-form-data="true"
                    @success="onSuccess" @failure="onFailure">
                    <form slot-scope="{ form, updateFile, submit }" class="flex h-full flex-col p-8" @submit.prevent="submit">
                        <div class="absolute pin-t pin-r mt-4 mr-4">
                            <button type="button" 
                            @click.prevent="$modal.hide('upload-schemes')"
                            class="font-bold text-2xl leading-none p-1 hover:text-red text-grey-dark" >&times;</button>
                        </div>
                        <h2 class="mb-2">Add Schemes</h2>
                        <p class="p-2">
                            To update the schemes available here, please download the schemes master from
                            <a class="underline text-blue-darker" href="https://bsestarmf.in/RptSchemeMaster.aspx">BSEStar MF</a>
                            and upload that file here, it may take some time to update the schemes after you upload file.
                        </p>
                        <div class="flex-1 flex flex-col justify-center items-center">
                            <input type="file" name="schemesFile" @input="updateFile" class="">
                            <span class="text-red mt-2"> <i class="fa fa-warning"></i><b> Careful! </b> Existing Schemes will truncate. </span>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" class="btn mx-1"
                                @click.prevent="$modal.hide('upload-schemes')"
                                > Cancel </button>
                            <button type="submit" class="btn mx-1 is-blue"> Submit </button>
                        </div>
                    </form>
                </v-ajax-form>
            </modal>
        </section>
    </schemes-view>
@endsection