<template>
    <div>
        <v-card>
            <v-card-title>
                Threads
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="search"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="desserts"
                :search="search"
            >

                <template v-slot:item.creator="{ item }">
                    <v-icon>person</v-icon>
                    <v-text>{{ item.creator.name }} </v-text>
                </template>

                <template v-slot:item.body="{ item }">
                    <read-more more-str="read more" :text="item.body" link="#" less-str="read less" :max-chars="500"></read-more>

                    <v-row  class="d-flex justify-end">
                        <v-chip
                            class="ma-2"
                            color="primary"
                            outlined
                            pill
                        >
                            <v-avatar left>
                                <v-icon>av_timer</v-icon>
                            </v-avatar>

                            {{ ago(item.created_at) }}

                        </v-chip>
                    </v-row>

                </template>

                <template v-slot:item.channel="{ item }">
                    <v-chip color="primary" dark>{{ item.channel.name }} </v-chip>
                </template>

                <template v-slot:item.replies_count="{ item }">
                    <v-chip :color="getColor(item.replies_count)" dark>{{ item.replies_count }} </v-chip>
                </template>

                <template v-slot:item.is_ban="{ item }">

                    <v-row  class="d-flex justify-start">
                        <v-col cols="12" sm="4" md="4">

                            <v-switch
                                v-model=" !item.is_ban "
                                color="success"
                                @change="toggleBan(item)"
                            ></v-switch>

                        </v-col>

                    </v-row>

                </template>

            </v-data-table>
        </v-card>

        <v-card>
            <v-card-title>
                Inactive/Banned Threads
                <v-spacer></v-spacer>

            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="banThreads"
                sort-by="slug"
                class="elevation-1"
                :search="search"
            >

                <template v-slot:item.creator="{ item }">
                    <v-icon>person</v-icon>
                    <v-text>{{ item.creator.name }} </v-text>
                </template>

                <template v-slot:item.channel="{ item }">
                    <v-chip color="primary" dark>{{ item.channel.name }} </v-chip>
                </template>

                <template v-slot:item.replies_count="{ item }">
                    <v-chip :color="getColor(item.replies_count)" dark>{{ item.replies_count }} </v-chip>
                </template>

                <template v-slot:item.is_ban="{ item }">

                    <v-row  class="d-flex justify-start">
                        <v-col cols="12" sm="4" md="4">

                            <v-switch
                                v-model=" !item.is_ban "
                                color="success"
                                @change="toggleBan(item)"
                            ></v-switch>

                        </v-col>

                    </v-row>

                </template>

                <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                </template>
            </v-data-table>
        </v-card>
    </div>



</template>

<script>
    import moment from 'moment';

    export default {
        data () {
            return {
                search: '',
                dialog: false,
                editedIndex: -1,
                ban:'',
                banThreads: [],
                editedItem: {
                    name: '',
                    calories: 0,
                    fat: 0,
                    carbs: 0,
                },
                defaultItem: {
                    name: '',
                    calories: 0,
                    fat: 0,
                    carbs: 0,
                },
                formTitle: 'Edit Thread',
                headers: [
                    // {
                    //     text: 'Id',
                    //     value: 'id',
                    // },
                    { text: 'Author', value: 'creator', width: 250 },
                    {
                        text: 'Title',
                        align: 'left',
                        sortable: false,
                        value: 'title',
                    },
                    { text: 'Body', value: 'body' },
                    { text: 'Channel', value: 'channel' },
                    { text: 'Replies_count', value: 'replies_count' },
                    { text: 'Active', value: 'is_ban' },
                    {text: 'Updated At', value: 'updated_at'},
                    {text: 'Created At', value: 'created_at'},
                ],
                desserts:[],
            }
        },
        methods: {
            initialize () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/threads')
                    .then(function (response) {

                        self.desserts = response.data;

                        self.$root.$emit('loading', false);
                    })
                    .catch(function (error) {

                        console.info('error');

                    })
                    .finally(function () {
                        // always executed
                    });

            },
            ago(date){

                moment.locale();

                return moment.utc(date).fromNow();

            },
            getColor (replies_count) {
                if (replies_count < 1) return 'red'
                else if (replies_count < 5 ) return 'orange'
                else return 'green'
            },

            editItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)

                this.dialog = true
            },

            toggleBan(item){
                var self = this

                this.$root.$emit('loading', true);

                axios.patch('/api/ban/thread', {
                    threadId: item.id,
                    ban: !item.is_ban
                })
                    .then(function (response) {

                        self.initialize()
                        self.getBanThreads()

                        flash('Changes Saved.', 'success');
                    })
                    .catch(function (error) {
                        flash('Changes Saved.', 'error');
                    })
                    .finally( function() {
                        self.$root.$emit('loading', false);
                    });

            },

            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                    this.ban= ''
                }, 300)
            },

            getBanThreads () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/threads?ban=1')
                    .then(function (response) {

                        self.banThreads = response.data;

                        self.$root.$emit('loading', false);
                    })
                    .catch(function (error) {

                        console.info('error');

                    })
                    .finally(function () {
                        // always executed
                    });

            },
        },
        mounted() {

            this.initialize()
            this.getBanThreads()
        }
    }
</script>
