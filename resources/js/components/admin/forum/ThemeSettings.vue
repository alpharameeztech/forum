<template>
    <v-container>
        <v-card>
            <v-toolbar flat color="primary" dark>
                <v-toolbar-title>Themes Settings</v-toolbar-title>
            </v-toolbar>
            <v-tabs vertical>
                <v-tab>
                    <v-icon left>settings_brightness</v-icon>
                    Change Theme
                </v-tab>

                <v-tab>
                    <v-icon left>gradient</v-icon>
                    Appearance
                </v-tab>

                <v-tab>
                    <v-icon left>code</v-icon>
                    Custom CSS
                </v-tab>
                <v-tab>
                    <v-icon left>code</v-icon>
                    Custom JS
                </v-tab>



                <v-tab-item>
                    <Themes></Themes>
                </v-tab-item>

                <v-tab-item>
                    <Appearance></Appearance>
                </v-tab-item>

                <v-tab-item>
                    <CssCode></CssCode>
                </v-tab-item>
                <v-tab-item>
                   <JsCode></JsCode>
                </v-tab-item>


            </v-tabs>
        </v-card>
    </v-container>

</template>
<script>
    import Themes from "./Themes"
    import CssCode from "./CssCode";
    import JsCode from "./JsCode";
    import Appearance from "./Appearance";

    export default {
        components: { Themes, CssCode, JsCode, Appearance },
        data() {
            return {
                themes: []
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

                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/channels')
                    .then(function (response) {

                        self.desserts = response.data;

                        self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {



                    })
                    .finally(function () {
                        // always executed
                    });

            },


            close () {

            },

            save () {

                this.close()
            },
        },

    }
</script>
