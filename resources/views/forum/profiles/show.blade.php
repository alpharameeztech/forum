@extends('layouts.forum')
@section('title', 'Profile')
@section('page-title') @yield('title')
@endsection

@section('page-content')
<!-- =============== show-body =============== -->

<user-profile inline-template>
    <div class="row">

      <div class="col-sm-12">
        <div class="card thread">


          <div class="card-body">


              <div class=" row">
                <div class="col-sm-4">

{{--                    <button @click="clickMe">hey</button>--}}
                    @if( $user->avatar != '' && strpos($user->avatar , 'http') !== false)
                        <img src="{{ $user->avatar }}" class="rounded-circle img-fluid" width="100px"/>
                    @elseif( $user->avatar != '')

                        <img src="{{Storage::disk("s3")->url($user->avatar)}}" class="rounded-circle img-fluid" width="100px"/>
                    @else
                        <img src="/img/user-placeholder.jpg" class="rounded-circle img-fluid"  width="100px"/>
                    @endif

                    @auth
                        <label for="profileImage">
                                <a style="cursor: pointer;" @click="isEditing = !isEditing"> <i class="material-icons outline-comment icon-image-preview">
                                        edit
                                    </i>
                                </a>
                        </label>


                        <form action="/upload/user/avatar" method="POST"  enctype="multipart/form-data">
                            @csrf

                            <input  type="file" name="profileImage" id="profileImage" style="display: none;"

                            >
                            <button v-if="isEditing" @click="uploadingImage" type="submit" class="btn btn-outline-primary">Save</button>

                        </form>
                    @endauth

{{--                    <img src="{{$user->photo_url}}" class="rounded-circle img-fluid" />--}}

                </div>
                <div class="col-sm-8">
                    <div class="row">
                        <h4 class="threadCreatorHeading">
                            <b>
                                @if( !empty($user->alias) )
                                    {{ ucfirst($user->alias) }}
                                @else
                                    {{ ucfirst($user->name) }}
                                @endif

                            </b> . <span>Member since: {{$user->created_at->diffForHumans()}}</span></h4>
                    </div>
                    <div class="row">
                            <h4 class="threadCreatorHeading">Experience Level:  {{$user_forum_info->experience ?? 0 }} </h4>
                    </div>
                </div>
                <div class="col-sm-12">
                    {{--  <img class="" width="100%" src="{{URL::asset('/images/badges.png')}}"  />  --}}
                </div>
            </div> <!-- row ended -->

              <div class="row">
                <div class="col-sm-1"></div>
              </div><!-- row ended -->

          </div> <!-- card-body ended -->


        </div> <!-- card thread ended -->


      </div>

      {{--
        =================== user threads ======================== --}}

        {{--  @foreach ($activities as $activity)

              @include("forum.activities.{$activity->type}")

          @endforeach  --}}


          @forelse($activities as $activity)

            @foreach ($activities as $date => $activity)

            <h3><span class="badge badge-pill badge-light">{{$date}}</span></h3>

                @foreach($activity as $singleActivty)
                  @if(view()->exists("forum.activities.{$singleActivty->type}"))
                    @include("forum.activities.{$singleActivty->type}", ['activity' => $singleActivty])
                  @endif
                @endforeach

            @endforeach

            @empty
            <p>There is no activity yet.</p>

          @endforelse

          {{--  {{$user_threads->links()}}  --}}





    </div> <!-- row ended -->

</user-profile>
<!-- =============== show-body ended=============== -->
@endsection
