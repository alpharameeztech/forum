{{--  <forum-reply-component :attributes="{{ $reply }}">  --}}
    <forum-reply-component  :attributes="{{ $reply }}" inline-template v-cloak>

        <div class="col-sm-12">
            <div class="card thread">
        
        
              <div class="card-body">
        
              
        
                <div class=" row" id="reply-{{ $reply->id}}">
                  <div class="col-sm-1"><img src="{{$reply->owner->photo_url}}" class="rounded-circle img-fluid" /></div>
                  <div class ="col-sm-11">
                    <h4 class="threadCreatorHeading">{{$reply->owner->name}} . <span>{{$reply->created_at->diffForHumans()}}</span></h4>
                  </div>
                </div>
                <!-- row ended -->
        
                <div class="row">
                  <div class="col-sm-1"></div>
        
                  <div class="col-sm-11">
                    <p class="card-text" v-if="editing == false">@{{body}}</p>
        
                    <div class="flex">
                      {{--  <form action="/forum/replies/{{$reply->id}}/favorites" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary">
                                    
                                    {{$reply->favorites_count}} {{ str_plural('Favorite', $reply->favorites_count )}}
                                  </button>
                      </form>  --}}

                      @auth
                        <favorite-reply-component :reply="{{ $reply }}"></favorite-reply-component>
                      @endauth
                      
                      <br />  <br />
                      
                      @can('update', $reply)
        
        
                      <form v-show="editing">
                          <div class="form-group">
                              <textarea required class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="What's on your mind?" name="body" v-model="body"></textarea>
                          </div>
                          <button type="submit" class="btn btn-primary" @click.prevent="update">Update</button>
                          <button type="submit" class="btn btn-outline-info" @click.prevent="editing = false">Cancel</button>
                      </form>
        
        
                      <button type="submit" class="btn btn-outline-primary" @click="editing = true" v-if="editing == false">Edit</button>
        
                      <div class="flex">
                        <br />
                        {{--  <form class="col-lg-12" action="/forum/replies/{{$reply->id}}" method="POST">
                          @csrf {{method_field('DELETE')}}
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>  --}}


                        <button type="submit" class="btn btn-danger" @click="destroy">Delete</button>

                      </div>

                      @endcan
        
        
                    </div>
                  </div>
        
                </div>
                <!-- row ended -->
        
              </div>
              <!-- card-body ended -->
        
        
            </div>
            <!-- card thread ended -->
        
        
          </div>
          <!--col 12 ende -->
    </forum-reply-component>

{{--  
</forum-reply-component>  --}}