@extends('layouts.forum') 
@section('title', 'Thread Details') 
@section('page-title') @yield('title')
@endsection
 
@section('page-content')
<!-- =============== training-body =============== -->

<div class="row">



  <div class="col-sm-12">
    <div class="card thread">


      <div class="card-body">
        
          <div class="channelType">
            <a href="/forum/threads/{{$thread->channel->slug}}" class="badge badge-primary">{{$thread->channel->slug}}</a>
          </div>

          <div class=" row">
            <div class="col-sm-1"><img src="{{$thread->creator->photo_url}}" class="rounded-circle img-fluid" /></div>
            <div class ="col-sm-11"><h4 class="threadCreatorHeading">{{$thread->creator->name}} . <span>{{$thread->created_at->diffForHumans()}}</span></h4></div>
          </div> <!-- row ended -->

          <div class="row">
            <div class="col-sm-1"></div>
            
            <div class="col-sm-11">
              <h5 class="card-title alert alert-primary">{{$thread->title}}</h5>
          
              <p class="card-text">{{$thread->body}}</p>

              <i class="material-icons outline-comment icon-image-preview">
                  comment
              </i>{{$thread->replyCount}}
              
              @can('update', $thread)
              <div class="flex">
                  <form  action="/forum{{$thread->path()}}" method="POST">
                    @csrf
                    {{method_field('DELETE')}}
                      <button type="submit" class="btn btn-outline-danger">
                        Delete
                    
                      </button>
                  </form>
              </div>
            @endcan
            
            </div>

          </div><!-- row ended -->

      </div> <!-- card-body ended -->


    </div> <!-- card thread ended -->


  </div>

  {{-- 
    =================== thread replies ======================== --}}
    
    @foreach ($replies as $reply)

        @include("forum.threads.reply", $reply)

        @endforeach
        
        {{$replies->links()}}

</div> <!-- row ended -->

<div class="row">

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul>
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

<form class="col-lg-12" action="/forum/threads/{{$thread->channel->id}}/{{$thread->id}}/replies" method="POST">
      @csrf
      @method('post')

       
        <div class="form-group">
            <label for="body">Leave a reply</label>
            <textarea required class="form-control" id="exampleFormControlTextarea1" rows="8" placeholder="What's on your mind?" name="body"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

</div>

<!-- =============== training-body ended=============== -->
@endsection