@extends('layouts.forum')
@section('title', 'Threads')

@section('page-title')
  @yield('title')
@endsection

@section('page-content')

    <threads inline-template>

            <div class="row">

                <div class="col-sm-12">
                    @if (request('search'))
                        {{ count($threads) }} results found
                    @endif
                </div>

              @forelse ($threads as $thread)


                <div class="col-sm-12">
                  <div class="card">

                    <div class="card-body">

                      <div class="channelType">

                          <a v-bind:style="[primaryButtonOverrideStyles]" href="/forum/threads/{{$thread->channel->slug}}" class="badge badge-primary"  >{{$thread->channel->slug}}</a>

                      </div>

                      <div class=" row">
                        <div class="col-sm-2">

                            @if( $thread->creator->avatar != '' && strpos($thread->creator->avatar , 'http') !== false)
                                <img src="{{ $thread->creator->avatar }}" class="rounded-circle img-fluid" width="100px"/>
                            @elseif( $thread->creator->avatar != '')

                                <img src="{{Storage::disk("s3")->url($thread->creator->avatar)}}" class="rounded-circle img-fluid" width="100px"/>
                            @else
                                <img src="/img/user-placeholder.jpg" class="rounded-circle img-fluid"  width="100px"/>
                            @endif

                        </div>

                        <div class="col-sm-10">
                          <a href="/forum/threads/{{$thread->channel->slug}}/{{$thread->slug}}">
                            <h5 class="card-title" v-bind:style="[headingOverrideStyles]">

                              @auth

                                @if( $thread->hasUpdatesFor() )

                                  <b> {{$thread->title}}  </b>

                                @else

                                {{$thread->title}}

                              @endif

                            @endauth

                            @guest

                              {{$thread->title}}

                            @endguest

                            </h5>
                          </a>
                            <div class="card-text" v-bind:style="[paragraphOverrideStyles]">{{ strip_tags( str_limit($thread->body, 100) ) }} </div>
                          <br />


                          <span class="commentSpan"> <i class="material-icons outline-comment icon-image-preview">
                            comment
                          </i>{{$thread->replyCount}}</span>


                          <span class="threadAuthor"> Posted By:
                              @if( !empty($thread->creator->alias) )
                                  <a href="/forum/profiles/{{$thread->creator->name}}" v-bind:style="[linkOverrideStyles]">
                                      {{$thread->creator->alias}}
                                  </a>
                                  <span> {{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }} </span>
                              @else
                                  <a href="/forum/profiles/{{$thread->creator->name}}" v-bind:style="[linkOverrideStyles]">
                                      {{$thread->creator->name}}
                                  </a>
                                  <span> {{ \Carbon\Carbon::parse($thread->created_at)->diffForHumans() }} </span>
                              @endif

                          </span>

                          <span class="visits"> <i class="material-icons outline-comment icon-image-preview">
                            visibility
                            </i> {{ $thread->visits()->count() }}
                          </span>

                          @if(! empty( $thread->recentReply() ))
                          <div class="col-sm-12 recentReplyInfo">

                            <a href="/forum/profiles/{{$thread->recentReply()['owner']['name']}}"> {{$thread->recentReply()['owner']['name']}} </a>

                              replied

                            <span> {{ \Carbon\Carbon::parse($thread->recentReply()['created_at'])->diffForHumans() }} </span>

                          </div>
                        @endif

                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                @empty
                   <p>No threads created yet for the relevant channel.</p>

              @endforelse

            {{--  =========================== paginate threads ============================= --}}
                @include("forum.threads.paignate")
            {{--                =========================== paginate threads ============================= --}}

            </div>

</threads>
          <!-- =============== training-body ended=============== -->
@endsection
