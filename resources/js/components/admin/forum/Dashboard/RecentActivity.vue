<template>

        <v-card
            width="auto"
            class="mx-auto recentActivity"
        >
            <v-toolbar
                color="deep-purple"
                dark
            >

                <v-toolbar-title>Recent Activity</v-toolbar-title>

                <v-spacer></v-spacer>

            </v-toolbar>

            <v-list subheader>


                <v-list-item
                v-if="member"
                >
                    <v-list-item-avatar>
                        <v-img :src="member.avatar"></v-img>
                    </v-list-item-avatar>

                    <v-list-item-content>
                        <v-list-item-title >Member: '{{ member.name}}' created

                        </v-list-item-title>

                    </v-list-item-content>

                    <v-list-item-icon>

                        <v-chip
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                        >
                            <v-avatar left>
                                <v-icon>av_timer</v-icon>
                            </v-avatar>

                            {{ ago(member.created_at) }}

                        </v-chip>

                    </v-list-item-icon>


                </v-list-item>

                <v-list-item
                    v-if="thread"
                >

                    <v-list-item-icon>
                        <v-icon :color="true ? 'deep-purple' : 'grey'">chat_bubble</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>


                        <v-list-item-title >Thread: '{{ thread.title}}' created

                        </v-list-item-title>

                    </v-list-item-content>

                    <v-list-item-icon>
                        <v-chip
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                        >
                            <v-avatar left>
                                <v-icon>av_timer</v-icon>
                            </v-avatar>

                            {{ ago(thread.created_at) }}

                        </v-chip>
                    </v-list-item-icon>


                </v-list-item>

                <v-list-item
                    v-if="reply"
                >

                    <v-list-item-icon>
                        <v-icon :color="true ? 'deep-purple' : 'grey'">chat</v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                        <v-list-item-title >Reply: '{{ reply.owner.name}}' posted a reply on a thread {{ reply.thread.title}}


                        </v-list-item-title>

                    </v-list-item-content>

                    <v-list-item-icon>
                        <v-chip
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                        >
                            <v-avatar left>
                                <v-icon>av_timer</v-icon>
                            </v-avatar>

                            {{ ago(reply.created_at) }}

                        </v-chip>
                    </v-list-item-icon>


                </v-list-item>

            </v-list>

            <v-divider></v-divider>


        </v-card>





</template>
<script>
    import moment from 'moment';

    export default {


        data() {
            return {

                items: [],
                member: null,
                thread: null,
                reply: null

            }
        },

        methods: {
            initialize () {

                this.recentMember()

                this.recentThread()

                this.recentReply()

            },

            recentMember(){

                var self= this

                axios.get('/api/recent/member')
                    .then(function (response) {

                        self.member = response.data

                    })
                    .catch(function (error) {


                    })
                    .finally(function () {
                        // always executed
                    });

            },

            recentThread(){

                var self= this

                axios.get('/api/recent/thread')
                    .then(function (response) {

                        self.thread = response.data

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        // always executed
                    });

            },

            recentReply(){

                var self= this

                axios.get('/api/recent/reply')
                    .then(function (response) {

                        self.reply = response.data

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        // always executed
                    });

            },

            ago(date){

                moment.locale();

                return moment.utc(date).fromNow();

            },

        },
        created() {

            this.initialize()
            //console.log(this.$data)

        }
    }
</script>

<style>
    .recentActivity .v-list-item__icon{
        margin-top: 31px;
    }
</style>
