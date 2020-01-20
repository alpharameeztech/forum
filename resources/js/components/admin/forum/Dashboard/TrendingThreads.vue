<template>





            <v-card
                width="auto"
                class="mx-auto"
            >
                <v-toolbar
                    color="teal"
                    dark
                >

                    <v-toolbar-title>Trending Threads</v-toolbar-title>

                    <v-spacer></v-spacer>

                </v-toolbar>

                <v-list subheader>
                    <v-subheader>Today</v-subheader>

                    <v-list-item
                        v-for="item in items"
                        :key="item.title"
                        @click=""
                    >

                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-icon>
                            {{ item.visits }}
                            <v-icon :color="true ? 'teal' : 'grey'">visibility</v-icon>
                        </v-list-item-icon>

                        <v-list-item-icon>
                            {{ item.totalReplies }}
                            <v-icon :color="true ? 'teal' : 'grey'">chat</v-icon>
                        </v-list-item-icon>

                    </v-list-item>
                </v-list>

                <v-divider></v-divider>


            </v-card>





</template>
<script>

    export default {


        data() {
            return {

                items: [],

            }
        },

        methods: {
            initialize () {

                var self= this

                axios.get('/api/trending/threads')
                    .then(function (response) {

                        self.items = response.data

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        // always executed
                    });

            },

        },
        created() {

            this.initialize()

        }
    }
</script>

<style>

</style>
