<template>
    <div class="w-full">
        <div v-if="isEditing" class="md:flex items-center w-full">
            <input ref="Input__first_name" type="text" v-model="form.first_name" class="text-2xl p-1 border">
            <input type="text" v-model="form.last_name" class="text-2xl p-1 border mr-3">
            <div>
                <button class="btn p-1 text-sm rounded mr-1" @click="update">
                    <i class="fa fa-check leading-none"></i> 
                </button>
                <button class="btn p-1 text-sm rounded hover:bg-red " @click="cancel">
                    <i class="fa fa-times leading-none"></i> 
                </button>
            </div>
        </div>
        <h1 v-else class="flex items-center font-normal">
            <span class="mr-4" v-text="name"></span>
            <button class="btn p-1 text-sm rounded" @click="edit">
                <i class="fa fa-pencil leading-none"></i>
            </button>
        </h1>
    </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
    props: ["data-name"],
    data() {
        return {
            form: { first_name: "", last_name: "" },
            isEditing: false,
        };
    },
    methods: {
        edit() {
            this.isEditing = true;
            this.$refs["Input__first_name"];
            return false;
        },
        cancel() {
            this.isEditing = false;
        },
        update() {
            axios.patch('/profile', this.form)
                .then(({data}) => {
                flash('Name Updated!');
                this.updateName(data.data);
                this.isEditing = false;
                });
        },
        updateName(patchData) {
            this.$store.state.client = {...this.$store.state.client, ...patchData}
        }
    },
    computed: {
        ...mapGetters(["name"])
    },
    created() {
        this.form = this.dataName;
    }
};
</script>
