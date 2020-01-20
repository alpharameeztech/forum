<template>
    <div v-show="show">
        <v-alert dense :type="type">
           {{ body }}
        </v-alert>
    </div>
</template>

<script>
    export default {
        props: ['message'],

        data(){
            return {
                body: '',
                show: false,
                type: 'success'
            }
        },
        created(){

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
                this.type = data.type;

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
        width: 40%;
    }
</style>
