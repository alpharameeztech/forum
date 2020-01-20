<template>
    <div>
        <v-container>
            <h3>Branding</h3>

            <form action="">
                <v-row>

                    <v-col cols="12" sm="12">
                        <v-text-field
                            v-model="title"
                            :error-messages="titleErrors"
                            :counter="10"
                            required
                            label="Forum Title"
                            single-line
                            @input="$v.title.$touch()"
                            @blur="$v.title.$touch()"
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="12" md="12">

<!--                        <v-file-input-->
<!--                            required-->
<!--                            label="Upload Logo"-->
<!--                            filled-->
<!--                            prepend-icon="mdi-camera"-->
<!--                            @change="onFileChange"-->
<!--                        ></v-file-input>-->

                        <div v-if="!imagePreview">
                            <p class="title">Select a Logo</p>
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

                        <div v-else>
                            <img :src="imagePreview" />

                            <v-card-actions class="justify-center">
                                <button  @click="removeImage"><v-icon>cancel</v-icon>Remove image</button>
                            </v-card-actions>
                        </div>

                    </v-col>

                    <v-col cols="12" sm="12">
                        <v-text-field
                            v-model="google_analytics_code"
                            label="Google Analytics Code"
                            single-line
                        ></v-text-field>
                    </v-col>

                    <v-col cols="12" sm="12">
                        <v-textarea
                            v-model="copyright"
                            clearable
                            clear-icon="cancel"
                            label="Copyright Text"
                        ></v-textarea>
                    </v-col>

                    <v-col cols="12" sm="12">
                        <div class="text-center">

                            <v-btn  color="primary" @click="submit">Save</v-btn>

                            <v-btn @click="clear">Clear</v-btn>

                        </div>
                    </v-col>

                </v-row>
            </form>
        </v-container>


    </div>
</template>

<script>

    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email } from 'vuelidate/lib/validators'

    export default {
        mixins: [validationMixin],
        validations: {
            title: { required, maxLength: maxLength(10) },
            logo: { required },

        },

        data: () => ({
            file: null,
            imageUrl: null,
            id: '',
            title: '',
            logo: '',
            google_analytics_code: '',
            copyright: '',
            imagePreview: '',
            method: 'post'

        }),

        computed: {
            checkboxErrors () {
                const errors = []
                if (!this.$v.checkbox.$dirty) return errors
                !this.$v.checkbox.checked && errors.push('You must agree to continue!')
                return errors
            },
            selectErrors () {
                const errors = []
                if (!this.$v.select.$dirty) return errors
                !this.$v.select.required && errors.push('Item is required')
                return errors
            },
            titleErrors () {
                const errors = []
                if (!this.$v.title.$dirty) return errors
                !this.$v.title.maxLength && errors.push('Title must be at most 10 characters long')
                !this.$v.title.required && errors.push('Title is required.')
                return errors
            },
            emailErrors () {
                const errors = []
                if (!this.$v.email.$dirty) return errors
                !this.$v.email.email && errors.push('Must be valid e-mail')
                !this.$v.email.required && errors.push('E-mail is required')
                return errors
            },
        },

        methods: {
            initialize () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/branding')
                    .then(function (response) {

                        if(response.data != ''){

                            self.id = response.data.id
                            self.title = response.data.title
                            self.copyright = response.data.copyright
                            self.google_analytics_code = response.data.google_analytics_code
                            self.imagePreview = 'https://s3.us-east-1.amazonaws.com/appforum/' + response.data.logo
                            self.method = 'patch'

                        }

                        self.$root.$emit('loading', false);
                    })
                    .catch(function (error) {

                        console.info('error');

                    })
                    .finally(function () {
                        // always executed
                    });

            },
            onFileChange() {
                let reader = new FileReader()
                reader.onload = () => {
                    this.imageUrl = reader.result
                }
                reader.readAsDataURL(this.file)
                this.logo = this.file
            },

            removeImage: function (e) {
                this.image = ''
                this.imagePreview = ''
                this.logo = ''
            },

            submit () {

                var self = this;

                this.$root.$emit('loading', true);

                let formData = new FormData();

                /*
                    Add the form data we need to submit
                */
                formData.append('logo', this.logo);

                formData.append('title', this.title);
                formData.append('google_analytics_code', this.google_analytics_code);
                formData.append('copyright', this.copyright);

                this.$v.$touch()

                if(this.method == 'post'){
                    axios.post('/api/branding',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function (response) {
                            flash('Changes Saved.', 'success');

                            self.$root.$emit('loading', false);
                        })
                        .catch(function (error) {

                        });
                }else{

                    formData.append("_method", 'PATCH');
                    formData.append("id", this.id);

                    const headers = {
                        'Content-Type': 'multipart/form-data',
                        'enctype' : 'multipart/form-data',

                    }
                    axios({
                        method : "POST",
                        url    : '/api/branding',
                        data   : formData,
                        headers: headers,
                    }).then(response => {
                        flash('Changes Saved.', 'success')

                        self.$root.$emit('loading', false);

                    })

                }


            },

            clear () {
                this.$v.$reset()
                this.title = ''
                this.google_analytics_code = ''
                this.copyright = ''

                this.removeImage()

            },


        },
        mounted() {

            this.initialize()
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
