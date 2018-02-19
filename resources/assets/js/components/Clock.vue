<template>
    <span>{{ time }}</span>
</template>
<script>
export default {
    data() {
        return {
            date: new Date(),
            months: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ]
        }
    },
    computed: {
        time() {
            return this.date.getDate() + ' ' 
                + this.months[this.date.getMonth()] + ', '
                + this.date.toLocaleTimeString('en-IN', {timezone: 'UTC'});
        }
    },
    mounted() {
        setInterval(() => this.date = new Date(), 1000);
    }
}
</script>

