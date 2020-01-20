<template>

    <div class="col-sm-12">
        <div v-if="singedIn">


            <div class="form-group">
                <label for="body">Leave a reply</label>
                <vue-tribute :options="tributeOptions">
                    <!-- <textarea
                            required
                            width="100%"
                            class="form-control"
                            id="newReply"
                            rows="8"
                            placeholder="What's on your mind?"
                            name="body"
                            required
                            v-model="body"></textarea> -->

                             <wysiwyg-component v-model="body" name="body"></wysiwyg-component>

                </vue-tribute>
            </div>

            <button type="submit" class="btn btn-primary" @click="addReply">Submit</button>

        </div>

<!--        <div v-else>-->
<!--            <p class="text-center">Already a member? Please <a href="/login">Sign In</a> to participate in this conversation</p>-->
<!--            <p class="text-center">Not a member? Please <a href="/register">Sign Up</a> to join the powerful health care community</p>-->
<!--        </div>-->

    </div>

</template>

<script>

    import VueTribute from 'vue-tribute';

    export default {
        props: ['endpoint'],

        data(){
            return {
               body: '',
                tributeOptions: {
                   // symbol that starts the lookup
                    trigger: '@',

                    // // element to target for @mentions
                    // iframe: null,

                    // // class added in the flyout menu for active item
                    // selectClass: 'highlight',

                    // function called on select that returns the content to insert
                    // selectTemplate: function (item) {
                    //     return '@' + item.original.value;
                    // },

                    // // template for displaying item in menu
                    // menuItemTemplate: function (item) {
                    //     return item.string;
                    // },


                   values: []
               },
               newData: ''
            };
        },
        created(){


        },
        computed: {

                singedIn(){
                    return window.App.signedInCheck;
                }
        },

        methods: {

            addReply(){

                var self = this

                this.$root.$emit('loading', true);

                axios.post(this.endpoint, {

                    body: this.body

                })
                .then( response => {

                    this.body = '';

                    flash('Your reply has been posted', 'success');

                    this.$emit('ReplyPosted', response.data)

                    // var element = document.querySelector("trix-editor")
                    // console.log(element.editor)  // is a Trix.Editor instance
                    // element.editor.element.value= ""
                    // element.editor.setSelectedRange([0, 0])
                    // element.editor.insertHTML = ""
                    //
                    // var element2 = document.querySelector("trix-editor div")
                    // console.log(element2)
                    // element2.innerHTML = ""
                    // element2.value = ""
                    // element2 = ''
                    //
                    // element.value = ""
                    // element.editor.loadHTML("")
                    //
                    // console.log(element.editor)

                })
                .catch(error => {

                    flash(error.response.data, 'danger')

                    self.$root.$emit('loading', false);

                })
                .finally(function () {
                     self.$root.$emit('loading', false);
                });

            } ,

             getUsers(){

                axios.get('/api/users/list')
                .then( response => {

                    this.newData = response.data
                    //this.tributeOptions.values = response.data;

                    for (var i = 0; i < this.newData.length; i++) {
                        this.tributeOptions.values.push(this.newData[i]);
                    }

                })
                .catch(error => {

                });

            }

        },

        mounted() {

            this.getUsers();

        }
    }
</script>


<style>

.scroll {
  width: 100%;
  max-height: 300px;
  overflow-y: auto;
  position: relative;
}

.v-tribute {
  width: 100%;
}

.content-editable:empty:before {
  content: attr(placeholder);
  display: block;
  color: #666;
}
textarea {
  appearance: none;
  border: none;
  background: #eee;
  padding: 1rem;
  width: 100%;
  border-radius: 0.25rem;
  font-size: 16px;
  height: 100px;
  outline: none;
  &:focus {
    background: #fff;
  }
}



// Tribute-specific styles
.tribute-container {
  position: absolute;
  top: 0;
  left: 0;
  height: auto;
  max-height: 300px;
  max-width: 500px;
  overflow: auto;
  display: block;
  z-index: 999999;
  border-radius: 4px;
  box-shadow: 0 1px 4px rgba(#000, 0.13);
}
.tribute-container ul {
  margin: 0;
  margin-top: 2px;
  padding: 0;
  list-style: none;
  background: #fff;
  border-radius: 4px;
  border: 1px solid rgba(#000, 0.13);
  background-clip: padding-box;
  overflow: hidden;
}
.tribute-container li {
  color: #3f5efb;
  padding: 5px 10px;
  cursor: pointer;
  font-size: 14px;
}
.tribute-container li.highlight,
.tribute-container li:hover {
  background: #3f5efb;
  color: #fff;
}
.tribute-container li span {
  font-weight: bold;
}
.tribute-container li.no-match {
  cursor: default;
}
.tribute-container .menu-highlighted {
  font-weight: bold;
}
</style>
