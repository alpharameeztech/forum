<script>

    export default {

        props: ['value'],

        // components: {Replies, ThreadSubscribeComponent},

        data(){

            return{
                searchBox: '',
                isSearching: false
            }
        },

        mounted(){

            var self = this
            this.searchBox = this.value

            if(self.searchBox.length > 2)
            {
                self.isSearching = true


            }
        },
        methods: {


            search()
            {
                var self = this

                this.$root.$emit('loading', true);

                if(self.searchBox.length >= 1){
                    window.location.href = '/forum/threads?search=' + self.searchBox
                }else{
                    window.location.href = '/forum/threads'
                }

            },
            change(){
                var self = this

                //alert(self.searchBox.length)
                if(self.searchBox.length > 2)
                {
                    self.isSearching = true
                }else{
                    self.isSearching = false
                }
            },

            clear(){

                this.$root.$emit('loading', true);

                window.location.href = '/forum/threads'
            }


        },

    }
</script>
