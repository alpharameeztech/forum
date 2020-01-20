<template>
    <div>
        <input type="submit" class="btn btn-outline-danger" value="Cancel Subscription" onclick="return confirm('Are you sure you want to do this?')" :disabled="isDisabled" @click="isDisabled = true; isLoading=true">
        <i v-if="isLoading" class="fa fa-btn fa-spinner fa-spin"></i>
   </div>
</template>

<script>
    export default {
        props: ['plan_id'],

        data: function () {
            return {
                value: this.plan_id,
                isDisabled: false,
                isLoading: false
            }
        },

        mounted() {
            console.log('Component mounted.')
            
        }
    }
</script>
