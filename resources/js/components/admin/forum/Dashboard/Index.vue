<template>

    <v-container>
        <v-row dense>
            <v-col cols="3">
                <v-card
                    color="#385F73"
                    dark
                >
                    <v-card-title class="headline justify-center">
                        <v-icon>people</v-icon>
                        Total Members
                    </v-card-title>

                    <v-card-subtitle class="text-center display-1">
                        {{ countMembers }}</v-card-subtitle>

                    <v-card-actions class="justify-center">
                        <v-btn  to="/admin/members" text>Show All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card
                    color="#1F7087"
                    dark
                >
                    <v-card-title class="headline justify-center">
                        <v-icon>chat_bubble</v-icon>
                        Total Threads
                    </v-card-title>

                    <v-card-subtitle class="text-center display-1">
                        {{ countThreads }}
                    </v-card-subtitle>

                    <v-card-actions class="justify-center">
                        <v-btn  to="/admin/threads" text>Show All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card
                    color="#952175"
                    dark
                >
                    <v-card-title class="headline justify-center">
                        <v-icon>chat</v-icon>
                        Total Replies
                    </v-card-title>

                    <v-card-subtitle class="text-center display-1">
                        {{ countReplies }}
                    </v-card-subtitle>

                    <v-card-actions class="justify-center">
                        <v-btn  to="/admin/replies" text>Show All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

            <v-col cols="3">
                <v-card
                    color="#852185"
                    dark
                >
                    <v-card-title class="headline justify-center">
                        <v-icon>category</v-icon>
                        Total Channels
                    </v-card-title>

                    <v-card-subtitle class="text-center display-1">
                        {{ countChannels }}
                    </v-card-subtitle>

                    <v-card-actions class="justify-center">
                        <v-btn  to="/admin/channels" text>Show All</v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>

        </v-row>

        <ChannelsWithThreadsAnalysis></ChannelsWithThreadsAnalysis>

              <v-row>
                  <v-col cols="6">
                      <TopMembers></TopMembers>
                  </v-col>

                  <v-col cols="6">
                      <RecentActivity></RecentActivity>
                  </v-col>
              </v-row>

        <v-row>
            <v-col cols="6">
                <TrendingThreads></TrendingThreads>
            </v-col>

            <v-col cols="6">
                <ThreadsWithNoComments></ThreadsWithNoComments>
            </v-col>

        </v-row>



        </v-row>

    </v-container>

</template>
<script>

    import statsCount from "../../../../mixins/statsCount";

    import ChannelsWithThreadsAnalysis from "./ChannelsWithThreadsAnalysis";

    import TopMembers from "./TopMembers";

    import TrendingThreads from "./TrendingThreads";

    import RecentActivity from "./RecentActivity";

    import ThreadsWithNoComments from "./ThreadsWithNoComments";

    export default {
        components: { ChannelsWithThreadsAnalysis, TopMembers, TrendingThreads, RecentActivity, ThreadsWithNoComments },

        mixins: [statsCount],

        data() {
            return {

            }
        },

        methods: {
            initialize () {

                var self= this

                //load countStats mixins
                this.Load()

            },

        },
        created() {

            this.initialize()
            //console.log(this.$data)

        }
    }
</script>

<style>
    img {
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }
</style>
