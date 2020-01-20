<template>

            <v-card
                width="auto"
                class="mx-auto"
            >
                <v-toolbar
                    color="#2b4a90"
                    dark
                >

                    <v-toolbar-title>Threads With No Activity</v-toolbar-title>

                    <v-spacer></v-spacer>

                </v-toolbar>

                <v-list subheader>
                    <v-subheader>Today's Total: {{items.length}}</v-subheader>

                    <v-list-item
                        v-if="items.length > 0"
                        v-for="item in items"
                        :key="item.title"
                        @click=""
                    >
                        <v-list-item-content>
                            <v-list-item-title v-text="item.title"></v-list-item-title>
                        </v-list-item-content>

<!--                        <v-list-item-icon>-->
<!--                            {{ item.visits }}-->
<!--                            <v-icon :color="true ? 'teal' : 'grey'">visibility</v-icon>-->
<!--                        </v-list-item-icon>-->

                        <v-list-item-icon>
                            0 {{ item.totalReplies }}
                            <v-icon :color="true ? 'teal' : 'grey'">chat</v-icon>
                        </v-list-item-icon>

                    </v-list-item>

                    <v-list-item  v-if="items.length == 0">
<!--                        <p class="text-capitalize">No such thread!</p>-->
                        <v-chip
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                        >
                            No Such Thread!
<!--                            <v-icon right>mdi-account-outline</v-icon>-->
                        </v-chip>
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

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/threads/with/no/replies')
                    .then(function (response) {

                        self.items = response.data

                        self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        self.$root.$emit('loading', false);
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
