<template>
    <div>
        <v-container>

            <div class="input-group color-picker" ref="colorpicker">
                {{ label }}
                <v-text-field
                    label="Solo"
                    solo
                    v-model="colorValue"
                    @focus="showPicker()"
                    @input="updateFromInput"
                    :append-icon="'color_lens'"
                >

                           <span class="input-group-addon color-picker-container">
                                <span class="current-color" :style="'background-color: ' + colorValue" @click="togglePicker()"> </span>
                           </span>
                </v-text-field>
                <span class="input-group-addon color-picker-container">
                                <span class="current-color" :style="'background-color: ' + colorValue" @click="togglePicker()"></span>
                           </span>

                <chrome-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" />

            </div>

            <!--            </div>-->

        </v-container>


    </div>
</template>

<script>
    import VueColor from 'vue-color';

    var Chrome = VueColor.Chrome;;


    export default {
        components: {
            'chrome-picker': Chrome,
        },
        // props: ['color', 'label'],
        props: {
            fontsize: {
                default: '12',
            },
            label: {
                default: 'Font Size'
            },
            type:{
                default: ''
            }
        },

        data() {
            return {
                colors: {
                    hex: '#ffffff',
                },


            }
        },
        mounted() {
            this.setColor(this.color || '#000000');
        },
        methods: {
            setColor(color) {
                this.updateColors(color);
                this.colorValue = color;
            },

        },

        watch: {

        },

    }
</script>

<style>
    .vc-chrome {

        width:100% !important;
    }
</style>
