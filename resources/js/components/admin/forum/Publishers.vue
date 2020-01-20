<template>
    <div>

        <v-card>
        <v-card-title>
            User Accounts
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
            <template v-slot:top>
                <v-toolbar flat color="white">

                    <v-divider
                        class="mx-4"
                        inset
                        vertical
                    ></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="500px">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">New User</v-btn>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>

                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field v-model="editedItem.alias" label="Alias"></v-text-field>
                                        </v-col>

                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                                        </v-col>


                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                <v-btn color="blue darken-1" text @click="save">Send</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
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

        <v-card>
            <v-card-title>
                Inactive/Blocked Accounts
                <v-spacer></v-spacer>

            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="banPublishers"
                sort-by="slug"
                class="elevation-1"
                :search="search"
            >

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
    export default {
        data() {
            return {
                search: '',
                editingPassword: false,
                ban:'',
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
                        sortable: true,
                        value: 'name',
                    },
                    {
                        text: 'Alias',
                        align: 'left',
                        sortable: true,
                        value: 'alias',
                    },
                    {text: 'Email', value: 'email'},
                    {text: 'Active', value: 'is_ban'},
                    {text: 'Updated At', value: 'updated_at'},
                    {text: 'Created At', value: 'created_at'},
                    // {text: 'Actions', value: 'action', sortable: false},
                ],
                desserts: [],
                banPublishers:[],
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
                return this.editedIndex === -1 ? 'Send invitaion to a new User' : 'Edit User'
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

                axios.get('/api/publishers')
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

            save () {
                var self = this;

                    //this.desserts.push(this.editedItem)

                    this.$root.$emit('loading', true);

                    axios.post('/api/invitation', {
                        name: this.editedItem.name,
                        alias: this.editedItem.alias,
                        email: this.editedItem.email,
                    })
                    .then(function (response) {

                        flash('Invitation Sent.', 'success');
                    })
                    .catch(function (error) {

                        flash('Invitation Not Saved.', 'error');
                    })
                    .finally(function () {
                        self.$root.$emit('loading', false);
                    });

                this.close()

            },

            toggleEditingPassword(){

                this.editingPassword = !this.editingPassword

                if(this.editingPassword == false){

                    this.password = ''
                }
            },
            toggleBan(item){

                var self = this

                this.$root.$emit('loading', true);

                axios.patch('/api/publisher', {
                    id: item.id,
                    ban: !item.is_ban
                })
                    .then(function (response) {

                        self.initialize()
                        self.getBanPublishers()
                        //self.banPublishers = []

                        flash('Changes Saved.', 'success');
                    })
                    .catch(function (error) {
                        flash('Changes Saved.', 'error');
                    })
                    .finally( function() {
                        self.$root.$emit('loading', false);
                    });

            },

            getBanPublishers () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/publishers?ban=1')
                    .then(function (response) {

                        self.banPublishers = response.data;

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

            this.getBanPublishers()
        }

    }
</script>
