<template>
    <div>
        <v-container>

<!--            <div class="input-group color-picker" ref="colorpicker">-->

<!--                <v-icon>color_lens</v-icon>-->
<!--                <span class="input-group-addon color-picker-container">-->
<!--                     <input type="text" class="form-control" v-model="colorValue" @focus="showPicker()" @input="updateFromInput" />-->
<!--                    <label class="col-sm-4 control-label">{{ label }}: {{ colorValue }}</label>-->
<!--                    <span class="current-color" :style="'background-color: ' + colorValue" @click="togglePicker()"></span>-->
<!--                    <chrome-picker :value="colors" @input="updateFromPicker" v-if="displayPicker" />-->
<!--                </span>-->

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
            color: {
                default: '#000000',
            },
            label: {
                default: 'Font Color'
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
                defaultColor: '#FF0000',
                colorValue: '#ffffff',
                displayPicker: false,

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
            updateColors(color) {
                if(color.slice(0, 1) == '#') {
                    this.colors = {
                        hex: color
                    };
                }
                else if(color.slice(0, 4) == 'rgba') {
                    var rgba = color.replace(/^rgba?\(|\s+|\)$/g,'').split(','),
                        hex = '#' + ((1 << 24) + (parseInt(rgba[0]) << 16) + (parseInt(rgba[1]) << 8) + parseInt(rgba[2])).toString(16).slice(1);
                    this.colors = {
                        hex: hex,
                        a: rgba[3],
                    }
                }
            },
            showPicker() {
                document.addEventListener('click', this.documentClick);
                this.displayPicker = true;
            },

            hidePicker() {
                document.removeEventListener('click', this.documentClick);
                this.displayPicker = false;
            },
            togglePicker() {
                this.displayPicker ? this.hidePicker() : this.showPicker();
            },
            updateFromInput() {
                this.updateColors(this.colorValue);

            },
            updateFromPicker(color) {
                this.colors = color;
                if(color.rgba.a == 1) {
                    this.colorValue = color.hex;
                }
                else {
                    this.colorValue = 'rgba(' + color.rgba.r + ', ' + color.rgba.g + ', ' + color.rgba.b + ', ' + color.rgba.a + ')';
                }
            },
            documentClick(e) {
                var el = this.$refs.colorpicker,
                    target = e.target;
                if(el !== target && !el.contains(target)) {
                    this.hidePicker()
                }
            }
        },

        watch: {
            colorValue(val) {
                if(val) {
                    this.updateColors(val);
                    this.$emit('input', val);

                    if(this.type != '')
                    {
                        this.$emit('Set'+ this.type, val);
                    }
                    //document.body.style.background = val;
                }
            }
        },

    }
</script>

<style>
    .vc-chrome {

        width:100% !important;
    }
</style>
