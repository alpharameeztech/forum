<template>
    <div class="container flashComponent" v-show="show">
        <div class="row justify-content-center">
            <div
                :class="'alert alert-' + level"
                class="success col-sm-12"
                role="alert"
                v-text="body">

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data(){
            return {
                body: '',
                show: false,
                level: 'success'
            }
        },
        created(){

            if(this.message){ // coming from php session

             this.flashMessage(this.message);

             this.hide();

            }


            //listen for the event flash-message along with  a props and then fire it
            window.events.$on('flash', data => {

                this.flash(data)

                this.hide();

            });

        },

        methods: {

            flash(data) {

                this.body = data.message
                this.show = true;
                this.level = data.level;

            },
            // if a thread is posted-comes from php session
            flashMessage(message){
                this.body = message;
                this.show = true;
            },
            hide() {

                setTimeout(() => {
                    this.show = false;
                }, 3000);

            }
        },

        mounted() {

        }
    }
</script>


<style>
.flashComponent{
   position: fixed;
    bottom: 5px;
    z-index: 0999;
    right: 1px;
    WIDTH: 40%;
}
</style>
