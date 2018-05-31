<template>
    <div class="fixed flex flex-col pin-b pin-r mb-4 mr-6 items-end z-50 w-1/3">
        <transition name="fade"
        enter-active-class="fadeIn"
        leave-active-class="fadeOut"
        mode="in-out">
            <div class="border rounded mb-2 p-3" :class="classes[message.level]" v-for="message in messages" :key="message.id">
                {{ message.message }}
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        'data-messages':{
            default: []
        }
    },
    data() {
        return {
            messages : [],
            classes: {
                success: 'bg-green-lighter border-green-light text-green-darker',
                danger: 'bg-red-lighter border-red-light text-red-darker',
                info: 'bg-blue-lighter border-blue-light text-blue-darker',
                warning: 'bg-yellow-lighter border-yellow-light text-yellow-darker',
            }
        }
    },
    methods: {
        clear() {
            for(let i=0; i< this.messages.length; i++){
                setTimeout(() => {
                    this.messages.splice(0, 1);
                }, i*200);
            }
        },
        flash(message) {
            this.messages.push(message);
            setTimeout(() => this.clear(), 3000);
        },
        schema() {
            return ['id', 'message', 'level', 'important'];
        }
    },
    mounted() {
        this.dataMessages.forEach(message => this.messages.push(message));
        setTimeout(() => this.clear(), 2000);

        window.Events.$on('flash', message => {
            console.log(message)
            this.flash(message)
        });
    }
}
</script>
<style scoped>
    .fadeIn, .fadeOut {
        animation: fade;
        animation-duration: 1000ms;
    }
    .fadeOut {
        animation-direction: reverse;
    }
    @keyframes fade {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
</style>
>

</style>


