<template>
    <div>

        <input id="trix" type="hidden" :name="name" :value="value">

        <trix-editor ref="trix" input="trix"></trix-editor>

    </div>
</template>

<script>

import Trix from 'trix';

export default {

    props: ['name', 'value'],

    mounted() {
        
        this.$refs.trix.addEventListener('trix-change', e => {
            this.$emit('input', e.target.innerHTML);
        })
    },

}
</script>

<style>
@import '../../sass/trix.scss';

trix-editor{
    background-color: #fff;
    min-height: 14em;
}
.trix-button--icon-code, .trix-button--icon-link {
    display: none;
}
</style>