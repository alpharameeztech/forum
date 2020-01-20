<template>





            <v-card
                width="auto"
                class="mx-auto"
            >
                <v-toolbar
                    color="cyan"
                    dark
                >

                    <v-toolbar-title>Top Members</v-toolbar-title>

                    <v-spacer></v-spacer>

                </v-toolbar>

                <v-list subheader>
                    <v-subheader>Today</v-subheader>

                    <v-list-item
                        v-for="item in items"
                        :key="item.title"
                        @click=""
                    >
                        <v-list-item-avatar>
                            <v-img :src="item.avatar"></v-img>
                        </v-list-item-avatar>

                        <v-list-item-content>
                            <v-list-item-title v-text="item.name"></v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-icon>
                            {{ item.experience }} Points
                                <v-icon :color="true ? 'cyan' : 'grey'">assessment</v-icon>

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


                axios.get('/api/top/members')
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
            //console.log(this.$data)

        }
    }
</script>

<style>

</style>
