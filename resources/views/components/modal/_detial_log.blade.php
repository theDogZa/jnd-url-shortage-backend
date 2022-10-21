
<a href="#" class="btn btn-sm btn-info js-tooltip-enabled " role="button" data-toggle="modal" data-target="#log_{{$id}}">
    <i class="fa fa-search-plus"></i>
</a>
<div class="modal fade" id="log_{{$id}}" tabindex="-1" aria-labelledby="{{$id}}" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-slideleft" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">{{$action}}</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="si si-close"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    {{-- @if($data && $request)
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>OLD</th>
                                <th>NEW</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $k => $v)
                            <tr>
                            @if($k != 'id')
                                <td>{!! $k !!}</td>
                                <td>{!! $v !!}</td>
                                <td>{!! $request->$k !!}</td>
                            @endif    
                            </tr>
                        @endforeach
                    </table>

                    @elseif(!$data && $request)
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>NEW</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach (@$request as $k => $v)
                            <tr>
                            @if($k != '_token')
                            <td>{!! $k !!}</td>
                            @if(is_array($v))
                            <td>{!! implode(",",$v) !!}</td>
                            @else
                            <td>{!! $v !!}</td>
                            @endif  
                            @endif    
                            </tr>
                        @endforeach
                    </table>
                    @endif --}}

                    @if(!empty(@$request))
                    <h4>Request</h4>
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th>#</th> 
                                <th>data</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($request as $k => $v)
                            <tr>
                            @if($k != '_token' && $k !='_method' && $k !='slug')
                                <td>{!! ucfirst($k) !!}</td>
                            @if(is_array($v))
                                <td>{!! implode(", ",$v) !!}</td>
                            @else
                                <td>{!! $v !!}</td>
                            @endif  
                            @endif    
                            </tr>
                        @endforeach
                    </table>
                    @endif

                    @if(!empty(@$data))
                    <h4>Response</h4>
                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th>#</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody> 
                        @foreach ($data as $k => $v)
                            <tr>
                            @if($k != 'id' && $k != 'slug')                         
                                @if(is_array($k))
                                <td>{!! implode(", ",$k) !!}</td>
                                @else
                                <td>{!! $k !!}</td>
                                @endif  

                                @if(is_array($v))
                                <td>{!! implode(", ",$v) !!}</td>
                                @else
                                <td>{!! $v !!}</td>
                                @endif  
                            @endif    
                            </tr>
                        @endforeach
                    </table>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Close</button> 
            </div>
        </div>
    </div>
</div>