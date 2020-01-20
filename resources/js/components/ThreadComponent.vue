<script>

    import Replies from  './RepliesComponent';

    import ThreadSubscribeComponent from './ThreadSubscribeComponenet';

    import ShopAppearance from "../mixins/shopAppearance";

    export default {

        props: ['initialRepliesCount', 'thread'],

        mixins: [ ShopAppearance ],

        components: {Replies, ThreadSubscribeComponent},

        data(){

            return{

                repliesCount: this.initialRepliesCount,
                editing: false,
                thread: this.thread,
                form: {
                    title: this.thread.title,
                    body: this.thread.body
                }
            }
        },

        mounted(){

        },
        methods: {
            update() {

                var self = this

                self.$root.$emit('loading', true);

                axios.patch('/forum/threads/' + this.thread.channel.slug + '/' + this.thread.slug, {

                    body: this.form.body

                }).then(() => {

                    this.editing = false;

                    flash('Your thread has been updated.');
                })
                .catch(function (error) {

                })
                .finally(function () {
                    self.$root.$emit('loading', false);
                });
            },

            cancel() {

                this.editing = false;

                this.form.body = this.thread.body;

            }
        },

    }
</script>
