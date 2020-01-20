<template>

     <div class="col-sm-12">
    <div class="card thread">
    <div class="card-body">


    <div :id="'reply-'+id" :class="isBest ? 'row bestReply' : 'row' ">

               <div class="col-sm-2"><img :src="avatar" class="rounded-circle img-fluid" /></div>
                  <div class ="col-sm-10">

                      <v-app>

                          <v-card
                              class="d-flex justify-space-between mb-6"
                              color="grey lighten-2"
                              flat
                              tile
                          >
                             <div>
                                 <h4 class="threadCreatorHeading">{{attributes.owner.name}} . <span v-text="ago"></span></h4>
                             </div>

                              <div v-if="isBest">

                                      <v-chip
                                          class="ma-2"
                                          color="#92d048"
                                          text-color="white"
                                      >
                                          Best Reply
                                          <v-icon right>mdi-star</v-icon>
                                      </v-chip>

                              </div>

                          </v-card>

                      </v-app>

                  </div>
                </div>
                <!-- row ended -->

               <div class="row" :class="isBest ? 'bestReply' : '' ">

                   <div class="col-sm-2">
<!--                      <img :src="url" class="rounded-circle img-fluid" />-->
                  </div>

                  <div class="col-sm-10">
                    <p v-bind:style="[paragraphOverrideStyles]" class="card-text" v-if="editing == false" v-html="body">{{body}}</p>

                    <div class="flex" v-if="singedIn">

                      <FavoriteReplyComponent :reply="{attributes}" ></FavoriteReplyComponent>

                    </div>

                     <form v-show="editing" v-if="canUpdate">
                          <div class="form-group">
                              <vue-tribute :options="tributeOptions">
                                <!-- <textarea required class="form-control" id="newReply" rows="5" placeholder="What's on your mind?" name="body" v-model="body"></textarea> -->
                                <wysiwyg-multiple-component :id="id" name="body" v-model="body"></wysiwyg-multiple-component>
                            </vue-tribute>
                          </div>
                          <button type="submit" class="btn btn-primary" @click.prevent="update">Update</button>
                          <button type="submit" class="btn btn-outline-info" @click.prevent="editing = false">Cancel</button>
                      </form>

                    <div class="own" v-if="canUpdate">
                      <br />

                       <button type="submit" class="btn btn-outline-primary" @click="editing = true" v-if="editing == false">Edit</button>

                        <div class="flex">
                            <br />

                          <!--  <button type="submit" class="btn btn-danger" @click="destroy">Delete</button> -->

                        </div>
                    </div>

                    <button  v-if="canMarkBest" type="submit" class="btn btn-outline-primary" @click.prevent="markAsBest" v-show="! isBest">Best Reply</button>


                </div>

    </div>
    </div>

  </div>
  </div>

</template>

<script>

    import FavoriteReplyComponent from './FavoriteReplyComponent';

    import moment from 'moment';

    import VueTribute from 'vue-tribute';

    import ShopAppearance from "../mixins/shopAppearance";

    export default {
        props: ['attributes'],

        components: { FavoriteReplyComponent, VueTribute},

        mixins: [ ShopAppearance ],

        data(){
            return {
                editing: false,
                body: this.attributes.body,
                id: this.attributes.id,
                url: this.attributes.owner.avatar,
                isBest: this.attributes.isBest,
                tributeOptions: {

                   values: []
                },
                newData: ''
            };
        },

        computed: {

                singedIn(){
                    return window.App.signedInCheck;
                },

                canUpdate(){
                  if(window.App.user != null)
                  {
                      return this.attributes.user_id == window.App.user.id ;
                  }


                   //return this.authorize(user => this.attributes.user_id == user.id);
                },

                ago(){

                    moment.locale();

                    return moment.utc(this.attributes.created_at).fromNow();

                },

                canMarkBest(){
                    if(window.App.user != null)
                    {
                        return this.attributes.thread.user_id == window.App.user.id ;
                    }

                   //return this.authorize(user => this.attributes.thread.user_id == user.id);

                },

                avatar(){

                    var path = this.url
                    var httpKey = 'http'

                    if( path != null && path.includes(httpKey) )
                    {

                        return this.url
                    }else if(this.url != null){

                        return 'https://appforum.s3.amazonaws.com/' + this.url
                    }
                    else{

                        return this.url ? this.url : '/img/user-placeholder.jpg';
                    }

                }


        },
         created() {

            window.events.$on('best-reply-selected', id => {


                this.isBest = ( id == this.attributes.id);

            })

            this.getUsers();
        },

        methods: {

            update(){

                var self= this

                self.$root.$emit('loading', true);

                axios.patch('/forum/replies/'+ this.attributes.id, {

                    body: this.body
                })
                .then(() => {

                    this.editing = false

                    flash('Your reply has been updated')
                })
                .catch(function (error) {
                    flash(error.response.data, 'danger')
                })
                .finally(function () {

                    self.$root.$emit('loading', false)

                });

            },

            destroy(){

                // axios.delete('/forum/replies/'+ this.attributes.id);

                // this.$emit('deleted', this.attributes.id); // emit event to let Parent Notified

                // flash('Your reply has been deleted', 'danger');
            },

            markAsBest(){
                this.isBest = true;

                axios.post('/forum/replies/' + this.attributes.id + '/best');

                window.events.$emit('best-reply-selected', this.attributes.id);
            },

            getUsers(){

                axios.get('/api/users/list')
                .then( response => {

                    this.newData = response.data

                    for (var i = 0; i < this.newData.length; i++) {
                        this.tributeOptions.values.push(this.newData[i]);
                    }

                })
                .catch(error => {
                });

            }

        }

    }
</script>
