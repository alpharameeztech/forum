<template>
    <div>
        <v-container>
            <p>Appearance</p>

<!--            <div class="d-flex justify-center">-->
            <div class="d-flex justify-space-around">

                <div>
                    <ColorPicker v-if="primaryButtonColor"  @SetprimaryButtonColor="SetprimaryButtonColor" type="primaryButtonColor" label="Primary Button Color" :color="primaryButtonColor"></ColorPicker>
                </div>

                <div>
                    <ColorPicker v-if="linkColor" @SetlinkColor="SetlinkColor" type="linkColor" label="Link Color" :color="linkColor"></ColorPicker>
                </div>


            </div>

            <div class="d-flex justify-space-around">

                <div>

                    <ColorPicker v-if="headingColor" @SetheadingColor="SetheadingColor" type="headingColor" label="Heading Color" :color="headingColor"></ColorPicker>

                </div>

                <div>
                    <ColorPicker v-if="paragraphColor" @SetparagraphColor="SetparagraphColor" type="paragraphColor" label="Paragraph Color" :color="paragraphColor"></ColorPicker>
                </div>


            </div>

            <v-btn color="blue darken-1" class="update" outlined  @click="save">Save</v-btn>

        </v-container>


    </div>
</template>

<script>

    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email } from 'vuelidate/lib/validators'
    import ColorPicker from "./ColorPicker";
    export default {
        components: { ColorPicker },

        data() {
            return {
                primaryButtonColor: '',
                linkColor:'',
                headingColor:'',
                paragraphColor:'',
                colors: {
                    hex: '#ffffff',
                },
                defaultColor: '#FF0000',
                colorValue: '#ffffff',
                displayPicker: false,
                hasSet: false,

            }
        },
        methods: {
            initialize ()
            {
                var self = this;

                this.$root.$emit('loading', true);

                axios.get('/api/shop/appearance')

                    .then(function (response) {

                        if(response.data != '')
                        {

                            self.primaryButtonColor  = response.data.primary_button_color
                            self.linkColor  = response.data.link_color
                            self.headingColor  = response.data.heading_color
                            self.paragraphColor  = response.data.paragraph_color

                            self.hasSet = true
                        }

                        self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {
                    })
                    .finally(function () {
                    });
            },

            SetprimaryButtonColor(val){
               this.primaryButtonColor = val
            },

            SetlinkColor(val){
                this.linkColor = val
            },

            SetheadingColor(val){
                this.headingColor = val
            },

            SetparagraphColor(val){
                this.paragraphColor = val
            },

            save(){
                /*
                only patch request
                post has been made when the app istalled
                 */
                var self = this;

                this.$root.$emit('loading', true);

                // if(self.hasSet == false)
                // {
                //     axios.post('/api/shop/appearance', {
                //         primary_button_color: this.primaryButtonColor,
                //         link_color: this.linkColor,
                //         heading_color: this.headingColor,
                //         paragraph_color: this.paragraphColor
                //     })
                //         .then(function (response) {
                //             self.$root.$emit('loading', false);
                //
                //             self.hasSet = true;
                //
                //             flash('Changes Saved.', 'success');
                //         })
                //         .catch(function (error) {
                //             flash('Changes Not Saved.', 'error');
                //         })
                //         .finally(function () {
                //         });
                // }
                //
                // else
                // {
                    axios.patch('/api/shop/appearance', {
                        primary_button_color: this.primaryButtonColor,
                        link_color: this.linkColor,
                        heading_color: this.headingColor,
                        paragraph_color: this.paragraphColor
                    })
                        .then(function (response) {
                            self.$root.$emit('loading', false);

                            flash('Changes Saved.', 'success');

                        })
                        .catch(function (error) {
                            flash('Changes Not Saved.', 'error');
                        })
                        .finally(function () {
                        });
                // }



            },
        },

        mounted() {

            this.initialize()
        },
    }
</script>

<style>
    h1 {
        height: 120px;
        line-height: 120px;
        text-align: center;
    }

    .current-color {
        display: inline-block;
        width: 100%;
        height: 16px;
        background-color: #000;
        cursor: pointer;
    }
    .footer {
        margin-top: 20px;
        text-align: center;
    }
</style>
