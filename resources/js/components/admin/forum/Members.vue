<template>
    <div>
        <v-card>
            <v-card-title>
                Members
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
                sort-by="slug"
                class="elevation-1"
                :search="search"
            >
                <template v-slot:item.forum_info="{ item }">

                    <v-row  class="d-flex justify-start">

                        <v-chip :color="getColor(item.forum_info)"
                                dark
                                v-if="item.forum_info != null">
                            {{ item.forum_info.experience }}
                        </v-chip>
                        <v-chip :color="getColor(item.forum_info)"
                                dark
                                v-else>
                            0
                        </v-chip>
                    </v-row>

                </template>

                <template v-slot:item.ago="{ item }">

                    <v-row  class="d-flex justify-start">
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


                <!--            <template v-slot:item.action="{ item }">-->
                <!--                <v-icon-->
                <!--                    small-->
                <!--                    class="mr-2"-->
                <!--                    @click="editItem(item)"-->
                <!--                >-->
                <!--                    edit-->
                <!--                </v-icon>-->
                <!--                            <v-icon-->
                <!--                                small-->
                <!--                                @click="deleteItem(item)"-->
                <!--                            >-->
                <!--                                delete-->
                <!--                            </v-icon>-->
                <!--            </template>-->
                <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                </template>
            </v-data-table>
        </v-card>

        <v-card>
            <v-card-title>
                Inactive/Banned Members
                <v-spacer></v-spacer>

            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="banMembers"
                sort-by="slug"
                class="elevation-1"
                :search="search"
            >

                <template v-slot:item.ago="{ item }">

                    <v-row  class="d-flex justify-start">
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
        data() {
            return {
                search: '',
                editingPassword: false,
                ban: '',
                banMembers: [],
                show1: false,
                password: '',
                rules: {
                    required: value => !!value || 'Required.',
                    min: v => v.length >= 8 || 'Min 8 characters',
                },
                dialog: false,
                publishers: [],
                headers: [
                    {
                        text: 'Id',
                        value: 'id',
                    },
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: false,
                        value: 'name',
                    },
                    {text: 'Email', value: 'email'},
                    {text: 'Social Profile', value: 'provider'},
                    {text: 'Experience', value: 'forum_info'},
                    {text: 'Country', value: 'country'},
                    {text: 'Active', value: 'is_ban'},
                    {text: 'Creation', value: 'ago'},
                    {text: 'Updated At', value: 'updated_at'},
                    {text: 'Created At', value: 'created_at'},
                    // {text: 'Actions', value: 'action', sortable: false},
                ],
                desserts: [],
                editedIndex: -1,
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
                }
            }

        },

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New User' : 'Edit Member'
            },
        },

        watch: {
            dialog (val) {
                val || this.close()
            },
        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/members')

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

            editItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.desserts.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
            },

            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                    this.ban= ''
                }, 300)
            },

            ago(date){

                moment.locale();

                return moment.utc(date).fromNow();

            },
            toggleBan(item){

                var self = this

                this.$root.$emit('loading', true);

                axios.patch('/api/ban/member', {
                    memberId: item.id,
                    ban: !item.is_ban
                })
                    .then(function (response) {

                        self.initialize()
                        self.getBanMembers()

                        flash('Changes Saved.', 'success');
                    })
                    .catch(function (error) {
                        flash('Changes Saved.', 'error');
                    })
                    .finally( function() {
                        self.$root.$emit('loading', false);
                    });

            },

            getBanMembers () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/members?ban=1')
                    .then(function (response) {

                        self.banMembers = response.data;

                        self.$root.$emit('loading', false);
                    })
                    .catch(function (error) {

                        console.info('error');

                    })
                    .finally(function () {
                        // always executed
                    });

            },
            getColor (forum_info) {
                if(forum_info != null)
                {

                    if (forum_info < 1) return 'red'
                    else if (forum_info < 100 ) return 'orange'
                    else return 'green'

                }
            },

        },

        mounted() {

            this.initialize()
            this.getBanMembers()
        },



    }
</script>
