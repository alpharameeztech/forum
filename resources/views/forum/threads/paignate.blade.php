
        <div class="row indexThreadsPagination">
            <div class="col-sm-12">
                @if(!empty($threads))
                    @if( app('request')->input('by') )
                        {{ $threads->appends(['by' => app('request')->input('by')] )->links() }}
                    @elseif(app('request')->input('fresh'))
                        {{ $threads->appends(['fresh' => 1] )->links() }}
                    @elseif(app('request')->input('search'))
                        {{ $threads->appends(['search' => app('request')->input('search') ] )->links() }}
                    @else
                        {{ $threads->links() }}
                    @endif
                @endif
            </div>
        </div>


