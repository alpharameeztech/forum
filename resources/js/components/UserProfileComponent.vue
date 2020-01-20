<script>

    export default {

        props: [],

        // components: {Replies, ThreadSubscribeComponent},

        data(){

            return{
                file: null,
                imageUrl: null,
                logo: '',
                isEditing: false
            }
        },

        mounted(){

        },
        methods: {

            // onFileChange() {
            //     let reader = new FileReader()
            //     reader.onload = () => {
            //         this.imageUrl = reader.result
            //     }
            //     reader.readAsDataURL(this.file)
            //     this.logo = this.file
            // },
            uploadingImage()
            {

                this.$root.$emit('loading', true);

            },

            save(){


            }

        },

    }
</script>
