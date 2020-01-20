<template>
    <v-data-table
        :headers="headers"
        :items="desserts"
        sort-by="slug"
        class="elevation-1"
    >
        <template v-slot:top>
            <v-toolbar flat color="white">
                <v-toolbar-title>Channels</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <template v-slot:activator="{ on }">
                        <v-btn color="primary" dark class="mb-2" v-on="on">New Channel</v-btn>
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

                                </v-row>
                            </v-container>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                            <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>
        </template>
        <template v-slot:item.action="{ item }">
            <v-icon
                small
                class="mr-2"
                @click="editItem(item)"
            >
                edit
            </v-icon>
<!--            <v-icon-->
<!--                small-->
<!--                @click="deleteItem(item)"-->
<!--            >-->
<!--                delete-->
<!--            </v-icon>-->
        </template>
        <template v-slot:no-data>
            <v-btn color="primary" @click="initialize">Reset</v-btn>
        </template>
    </v-data-table>
</template>
<script>
    export default {
        data() {
            return {
                channels: '',
                dialog: false,
                channels: [],
                headers: [
                    // {
                    //     text: 'Id',
                    //     value: 'id',
                    // },
                    {
                        text: 'Name',
                        align: 'left',
                        sortable: false,
                        value: 'name',
                    },
                    {text: 'Slug', value: 'slug'},
                    {text: 'Updated At', value: 'updated_at'},
                    {text: 'Created At', value: 'created_at'},
                    {text: 'Actions', value: 'action', sortable: false},
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
                return this.editedIndex === -1 ? 'New Channel' : 'Edit Channel'
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

                axios.get('/api/channels')
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
                }, 300)
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.desserts[this.editedIndex], this.editedItem)
                    // console.log(this.editedIndex)
                    // console.log(this.editedItem)

                    axios.patch('/api/channels', {
                        name: this.editedItem
                    })
                    .then(function (response) {
                        flash('Changes Saved.', 'success');

                        this.initialize()

                    })
                    .catch(function (error) {
                        flash('Changes Not Saved.', 'error');
                    });


                } else {

                    var self = this

                    this.$root.$emit('loading', true);

                    this.desserts.push(this.editedItem)

                    axios.post('/api/channels', {
                        name: this.editedItem
                    })
                    .then(function (response) {
                        //self.initialize()
                        flash('Changes Saved.', 'success');
                    })
                    .catch(function (error) {
                        flash('Changes Saved.', 'error');
                    })
                    .finally( function() {
                        self.$root.$emit('loading', false);
                    });

                    this.initialize()
                }
                this.close()
            },
        },

    }
</script>
