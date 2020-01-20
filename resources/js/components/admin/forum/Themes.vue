<template>

    <v-card
        :class="`d-flex justify-center mb-6`"
        flat>
        <v-card-text>
            <v-radio-group v-model="selectedTheme" row>
                <v-radio v-for="theme in themes"
                         :key="theme.id"
                         :label="theme.title" :value="theme.title"
                         @change="themeChange(theme)">
                </v-radio>
            </v-radio-group>

            <p class="subtitle-2">Preview</p>

            <v-img
                :src="preview"
                lazy-src="https://appforum.s3.amazonaws.com/placeholder.jpeg"
                aspect-ratio="1"
                class="grey lighten-2"
                width="100%"
                height="500"
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





                <v-btn color="blue darken-1" class="update" outlined  @click="save">Save</v-btn>

        </v-card-text>

    </v-card>
</template>
<script>

    export default {
        data() {
            return {
                selectedTheme: '',
                selectedThemeId:'',
                themes: [],
                themeSettings: '',
                preview: '',
            }

        },

        computed: {

        },

        watch: {

        },

        created () {
            this.initialize()
        },

        methods: {
            initialize () {

                this.getThemeSettings()

                this.getThemes()

            },

            getThemeSettings () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/theme/settings')
                    .then(function (response) {

                        if(response.data != '')
                        {
                            self.themeSettings = response.data
                        }

                        self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        self.$root.$emit('loading', false);
                    });
            },

            setTheme()
            {
                var self = this;

                if(self.themeSettings == '') // if no settings have beens saved yet
                {
                    // initialze with default light
                    self.preview  = self.themes[0].preview_img
                    self.selectedTheme = self.themes[0].title
                    self.selectedThemeId = self.themes[0].id


                }else{ // if settings have been saved

                    self.preview = self.themeSettings.theme.preview_img
                    self.selectedTheme = self.themeSettings.theme.title
                    self.selectedThemeId = self.themeSettings.theme.id
                }
            },

            getThemes () {

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/themes')
                    .then(function (response) {

                        self.themes = response.data

                        self.$root.$emit('loading', false);

                        self.setTheme()

                    })
                    .catch(function (error) {

                    })
                    .finally(function () {
                        // always executed
                    });
            },

            themeChange(theme){

                var self = this

                self.preview = theme.preview_img
                self.selectedThemeId = theme.id

            },

            close () {

            },

            save () {
               // alert(this.selectedThemeId)

                var self = this;

                this.$root.$emit('loading', true);

                axios.patch('/api/theme/settings', {
                    themeId: this.selectedThemeId,
                })
                    .then(function (response) {

                        self.$root.$emit('loading', false)

                        flash('Changes Saved')

                    })
                    .catch(function (error) {

                    });



                this.close()
            },
        },

    }
</script>

<style>
    .update{
        margin-top: 22px;
    }
</style>
