<template>
    <div class="col-sm-12">

        <div v-for="(reply, index) in items" :key="reply.id">

            <Reply :attributes="reply" @deleted="remove(index)" v-show =" !reply.is_ban"></Reply>

        </div>

        <paginator-component :dataSet="dataSet" @updated="fetch"></paginator-component>

        <div class="col-sm-12">
            <NewReply :endpoint="endpoint" @ReplyPosted="add"></NewReply>
        </div>

    </div>
</template>

<script>

     import Reply from './ForumReplyComponent';

     import NewReply from './NewReplyComponent';

     import collection from '../mixins/collection';

    export default {
        // props: ['data'],

        components: { Reply, NewReply },

        mixins: [collection],

        data(){
            return {

            //    items: this.data,

            // items: [], // will get the items from the mixin collection

            dataSet: false,

            endpoint: location.pathname + '/replies'

            };
        },
        created(){
            this.fetch();

        },

        methods: {

            fetch(page){
                axios.get(this.url(page))
                     .then(this.refresh);

            },

            url(page){ // default page number is 1

                // by default the page variable will be one but when requested with page number from the query string then
                // it will be passed to fetch the data of that query string
                if(! page){
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1; // if there is no query string then default page 1 will be used
                }
                return location.pathname + '/replies?page=' + page;

            },

            refresh({data}){

                this.dataSet = data;

                this.items = data.data
            },

        },

        mounted() {

        }
    }
</script>
