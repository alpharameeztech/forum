<template>
    <div>

        <input :id="id" type="hidden" :value="value" :name="name" >
        <trix-editor ref="trix" :input="id" placeholder="What's on your mind?"></trix-editor>

    </div>
</template>

<script>

import Trix from 'trix';

export default {

    props: ['name', 'value', 'id'],

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