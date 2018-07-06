<template>
    <section class="mb-4 py-8">
        <div class="flex items-center py-1 mb-4">
            <h2 class="mr-4">Address</h2>
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
            <div class="md:w-1/6 md:w-1/6 font-bold px-1">Address</div>
            <div class="px-1">
                <div v-if="isEditing">
                    <input type="text" placeholder="Line 1" class="block border p-1 rounded" v-model="form.first_line">
                    <input type="text" placeholder="Line 2" class="block border p-1 rounded" v-model="form.second_line">
                    <input type="text" placeholder="Line 3" class="block border p-1 rounded" v-model="form.third_line">
                </div>
                <div v-else>
                    <p v-text="address.first_line"></p>
                    <p v-text="address.second_line"></p>
                    <p v-text="address.third_line"></p>
                </div>
            </div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">City</div>
            <div class="px-1">
                <input v-if="isEditing" type="text" class="border p-1 rounded" v-model="form.city">
                <span v-else v-text="address.city"></span>
            </div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">State</div>
            <input v-if="isEditing" type="text" class="border p-1 rounded" v-model="form.state">
            <div v-else class="px-1">{{ address.state }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Pincode</div>
            <input v-if="isEditing" type="text" class="border p-1 rounded" v-model="form.pincode">
            <div v-else class="px-1">{{ address.pincode }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Country</div>
            <div class="px-1">{{ address.country }}</div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Phone</div>
            <div v-if="isEditing">
                <input placeholder="Residence" type="text" class="border p-1 rounded" v-model="form.residence_phone">
                <input placeholder="Office" type="text" class="border p-1 rounded" v-model="form.office_phone">
            </div>
            <div v-else>
                <p v-text="address.residence_phone"></p>
                <p v-text="address.office_phone"></p>
            </div>
        </div>
        <div class="md:flex md:items-center py-1 border-b">
            <div class="md:w-1/6 font-bold px-1">Fax</div>
            <div v-if="isEditing">
                <input placeholder="Residence" type="text" class="border p-1 rounded" v-model="form.residence_fax">
                <input placeholder="Office" type="text" class="border p-1 rounded" v-model="form.office_fax">
            </div>
            <div v-else>
                <p v-text="address.residence_fax"></p>
                <p v-text="address.office_fax"></p>
            </div>
        </div>
    </section>
</template>
<script>
import { mapState } from 'vuex';
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
            this.isEditing = false;
        },
        update() {
            axios.patch('/address', this.form)
                .then(({data}) => {
                    flash(data.message)
                    this.address = data.data
                });
            this.isEditing = false;
        }
    },
    computed: {
        ...mapState({
            address: state => state.client.address
        })
    },
    created() {
        this.form =  this.address;
    }
}
</script>
