<modal name="import-csv-data" height="auto" :scrollable="true">
    <v-ajax-form action="{{ route('admin.generate-portfolios.store') }}" :use-form-data="true" method="POST"
        @success="flash('Import Successful', 'success'); $modal.hide('import-csv-data')" @failure="flash('Import Failed! Possibly Invalid CSV file.')">
        <form slot-scope="{ form, submit, updateFile, submitting }" @submit.prevent="submit" class="p-6">
            <h3 class="mb-8"> Import CSV (CAMS) </h3>
            <p class="mb-6"> 
                This option for importing data from <b>CAMS</b> only. File size should be less than 2MB.
            </p>
            <div class="text-center mb-6">
                <input type="file" name="csvFile" accept="text/csv" @input="updateFile">
                <div class="text-xs text-red my-2" ></div>
            </div>
            <div class="text-right">
                <button type="button" class="btn mr-2" @click.prevent="$modal.hide('import-csv-data')"> Cancel </button>
                <button type="submit" class="btn is-blue" :disabled="submitting" v-text="submitting ? 'Working...' : 'Import' "></button>
            </div>
        </form>
    </v-ajax-form>
</modal>