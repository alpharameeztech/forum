<template>
    <v-container>
        <form action="">

            <v-card
        :class="`d-flex justify-center mb-6`"
        flat>

        <v-card-text>

            <v-img
                :src="avatar"
                :lazy-src="avatar"
                aspect-ratio="1"
                class="grey lighten-2"
                width="200px"
                height="200px"
                v-if="avatar"
            >
                <template v-slot:placeholder>
                    <v-row
                        class="fill-height ma-0"
                        align="center"
                        justify="center"
                    >
                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-row>
                </template>
            </v-img>

            <div>

                <!--                            <input type="file" @change="onFileChange">-->

                <template>

                    <v-file-input
                        v-model="file"
                        label="Select Image File..."
                        accept="image/*"
                        @change="onFileChange"
                    ></v-file-input>


                </template>


            </div>

            <v-text-field
                v-model="name"
                :rules="nameRules"
                :counter="40"
                label="Name"
                required
            ></v-text-field>

            <v-text-field
                v-model="email"
                :rules="emailRules"
                label="E-mail"
                required
            ></v-text-field>

            <v-row>
                <v-col cols="10">

                    <v-text-field
                        v-show="isPasswordEditing"
                        v-model="password"
                        :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="show1 ? 'text' : 'password'"
                        name="input-10-1"
                        label="Set your new Password"
                        hint="At least 8 characters"
                        counter
                        @click:append="show1 = !show1"
                    ></v-text-field>



                </v-col>

                <v-col cols="2">
                    <v-btn v-show="isPasswordEditing" @click="isPasswordEditing = false" class="ma-2" tile outlined color="success">
                        Done
                    </v-btn>
                    <v-btn v-show="isPasswordEditing" @click="cancelPasswordChange" class="ma-2" tile outlined color="error">
                        Cancel
                    </v-btn>
                </v-col>

                <v-col cols="12">
                    <v-btn v-show="!isPasswordEditing" @click="isPasswordEditing = true" class="ma-2" tile outlined color="success">
                        <v-icon left>vpn_key</v-icon> Change Password
                    </v-btn>
                </v-col>

            </v-row>


            <v-btn color="blue darken-1" class="update" outlined  @click="save">Save</v-btn>

        </v-card-text>

    </v-card>
        </form>
    </v-container>
</template>
<script>

    export default {
        data: () => ({
            show1: false,
            isPasswordEditing: false,
            valid: false,
            name:'',
            email:'',
            password: '',
            nameRules: [
                v => !!v || 'Name is required',
                v => v.length <= 50 || 'Name must be less than 50 characters',
            ],
            emailRules: [
                v => !!v || 'E-mail is required',
                v => /.+@.+/.test(v) || 'E-mail must be valid',
            ],
            settings:[],
            avatar: false,
            file: null,
            imageUrl: null,
            profilePicture: ''


        }),

        computed: {

        },

        watch: {

        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {
                this.getAccountDetails()
            },

            cancelPasswordChange()
            {

                var self = this

                self.isPasswordEditing = false
                self.password = ''
            },
            getAccountDetails () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/account/details')
                    .then(function (response) {

                        if(response.data != '')
                        {
                            self.settings = response.data
                            self.name = self.settings.name
                            self.email = self.settings.email
                            self.avatar = response.data.avatar

                            if(self.avatar)
                            {
                                self.avatar = 'https://appforum.s3.amazonaws.com/' + self.avatar
                            }
                            else
                            {
                                self.avatar = '/img/user-placeholder.jpg'
                            }
                        }

                        self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {
                        flash('Something went wrong', 'error')
                    })
                    .finally(function () {
                        self.$root.$emit('loading', false);
                    });
            },


            close () {

            },

            save () {
                // alert(this.selectedThemeId)

                var self = this;

                this.$root.$emit('loading', true);

                let formData = new FormData();

                /*
                    Add the form data we need to submit
                */
                formData.append('profilePicture', this.profilePicture);
                formData.append('name', this.name);
                formData.append('email', this.email);
                formData.append('password', this.password);


                axios.post('/api/account/details', formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                            'enctype' : 'multipart/form-data'
                        }
                    }).then(function (response) {

                        self.$root.$emit('loading', false)

                        flash('Changes Saved')

                        self.initialize()

                        self.removeImage()

                    })
                    .catch(function (error) {

                        self.$root.$emit('loading', false)

                        flash('Changes Not Saved', 'error')
                    })
                    .finally(function () {
                        self.$root.$emit('loading', false)

                    });;



                this.close()
            },

            onFileChange() {
                let reader = new FileReader()
                reader.onload = () => {
                    this.imageUrl = reader.result
                }
                reader.readAsDataURL(this.file)
                this.profilePicture = this.file
            },

            removeImage: function (e) {
                this.profilePicture = ''
                this.file = ''
            },
        },

    }
</script>

<style>
    .update{
        margin-top: 22px;
    }
</style>
