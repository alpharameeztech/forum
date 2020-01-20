<template>

    <button type="submit" class="btn btn-outline-primary" @click.prevent="toggle">
          <i :class="classes">
                  {{iconName}}
              </i>
        <span v-text="favoriteCount"></span>
    </button>
   

</template>
<script>
    export default {
        props: ['reply'],

        data(){
            return {
               favoriteCount: this.reply.attributes.favorites_count,
                isFavorited: this.reply.attributes.isFavorited,
            };
        },
        created(){


        },

        computed: {

            classes(){
                    return ['material-icons icon-image-preview', this.isFavorited ? 'outline-favorite' : 'outline-favorite_border'];
            },
            
            iconName(){
                
                 if( this.isFavorited){
                     return 'favorite';
                 }else{

                     return 'favorite_border'
                 }
            }
        },
        
        methods: {

            toggle(){

                if(this.isFavorited){
                   
                      axios.delete('/forum/replies/'+ this.reply.attributes.id + '/favorites');

                    this.isFavorited = false;

                    this.favoriteCount--;

                }else{

                     axios.post('/forum/replies/'+ this.reply.attributes.id + '/favorites');

                     this.isFavorited = true;

                     this.favoriteCount++;

                }

            }

        },  

        mounted() {
            //console.log(this.reply.attributes.favorites_count);
        }
    }
</script>
