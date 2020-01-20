<template>
     <div class="subscribeBtnContainer">

       <button :class="classes" @click="subscribe">Subscribe</button>

    </div>
</template>

<script>

    export default {

        props: ['active'],

        data() {
            return {

            }
        },
        computed: {

            classes(){

                return ['btn', this.active ? 'btn-primary' : 'btn-outline-primary']
            }
        },
        methods: {

            subscribe(){

                let requestType = this.active ? 'delete' : 'post';

                axios[requestType](location.pathname + '/subscription');

                this.active = ! this.active;

                if(requestType == 'post'){
                     flash('You have subscribed');
                }else{
                    flash('You have unsubscribed', 'info');
                }

            }
        },
        created() {

        },

    }
</script>

<style>

.subscribeBtnContainer button{
    float:right;
}

</style>
