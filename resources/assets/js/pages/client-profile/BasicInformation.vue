<template>
    <section class="mb-4 py-8">
        <div class="flex items-center py-1 mb-4">
            <h2 class="mr-4">Personal Information</h2>
            <div v-if="isEditing">
                <button class="btn text-sm p-1 rounded mr-2" @click="update">
                    <i class="fa fa-check"></i>
                </button>
                <button class="btn text-sm p-1 rounded hover:bg-red" @click="cancel">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div v-else>
                <button class="btn text-sm p-1 rounded" @click="edit">
                    <i class="fa fa-pencil"></i>
                </button>
            </div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Pan Number</div>
            <input v-if="isEditing" type="text" class="border p-1 rounded" v-model="form.pan">
            <div v-else class="px-1">{{ client.pan }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Date of Birth</div>
            <input v-if="isEditing" type="date" class="border p-1 rounded" v-model="form.dob">
            <div v-else class="px-1">{{ client.dob }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Gender</div>
            <div v-if="isEditing" class="select-wrapper">
                <select class="select-wrapper bg-white border p-1 rounded" v-model="form.gender">
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                </select>
            </div>
            <div v-else class="px-1">{{ gender }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Father / Husband / Guardian</div>
            <input v-if="isEditing" type="text" v-model="form.guardian" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.guardian }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Guardian Pan</div>
            <input v-if="isEditing" type="text" v-model="form.guardian_pan" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.guardian_pan }}</div>
        </div>

        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Nominee</div>
            <input v-if="isEditing" type="text" v-model="form.nominee" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.nominee }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Nominee Relation</div>
            <input v-if="isEditing" type="text" v-model="form.nominee_relation" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.nominee_relation }}</div>
        </div>
        
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Email</div>
            <input v-if="isEditing" type="email" v-model="form.email" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.email }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Mobile</div>
            <input v-if="isEditing" type="number" v-model="form.mobile" class="border p-1 rounded">
            <div v-else class="px-1">{{ client.mobile }}</div>
        </div>
    </section>
</template>
<script>
import { mapState, mapGetters } from 'vuex';
export default {
    data() {
        return {
            form: {},
            isEditing: false,
        }
    },
    methods: {
        edit() {
            this.isEditing = true;
        },
        cancel() {
            this.reset();
        },
        update() {
            axios.patch('/profile', this.form)
            .then(({data}) => {
                this.updateProfile(data.data);
                flash('Information Updated!')
                this.reset();
            })
        },
        updateProfile(patch) {
            console.log(patch);
            this.$store.state.client = { ...this.client, ...patch };
        },
        reset() {
            this.form = filterObject(this.client, ['pan', 'dob', 'gender', 'guardian', 'guardian_pan', 'email', 'mobile']);
            this.isEditing = false;
        }
    },
    computed: {
        ...mapState(['client']),
        ...mapGetters(['name', 'gender'])
    },
    created() {
        this.reset();
    }
}
</script>
