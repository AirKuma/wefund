@extends('layouts.master')

@section('content')







	<div class="container">

	<div class="row">




<!-- <div class="col-md-12 card-block" >
  {!! Form::open(['route' => ['get.auction.index'], 'method' => 'get','class' => 'form-inline']) !!}
  <div class="form-group">
        <label for="category">學校：</label>
        {!! Form::select('college_id',$colleges, null, ['placeholder' => '請選擇學校', 'class' => 'c-select']) !!}            
  </div>
   <div class="form-group">
         {!! Form::submit('搜尋', ['class' => 'btn btn-primary']) !!}                                                              
  </div>  
  {!! Form::close() !!}
</div>  -->
<div class="col-lg-3">
  <div class="list-group">
      <a href="all" class="list-group-item @if($type == 'all')active @endif">全部<span class="label label-pill label-warning pull-xs-right">{{ $itemall->count() }}<span></a>
      @foreach($category as $categories)
        <a href="{{ $categories->en_name }}" class="list-group-item @if($type == $categories->en_name )active @endif">{{ $categories->name }}<span class="label label-pill label-warning pull-xs-right">{{ $itemall->where('category_id',$categories->id)->count() }}<span></a>
      @endforeach
      @if($auction=='bid')
      <a href="free" class="list-group-item @if($type == 'free')active @endif">免費贈送<span class="label label-pill label-warning pull-xs-right">{{ $itemall->where('free',1)->count() }}<span></a>
      @endif
      @if(Auth::check())
      <a href="my" class="list-group-item @if($type == 'my')active @endif">出價項目<span class="label label-pill label-warning pull-xs-right">{{ Auth::user()->items()->where('type', $auction =='bid' ? 0 : 1)->groupBy('items.id')->where('disabled',0)->where('end_time','>',\Carbon\Carbon::now())->get()->count() }}<span></a>
      @endif
   </div>   
</div>

<div class="col-lg-9">
  <div class="col-md-12 search">  
    <a class="btn btn-secondary itemPlusIcon" href="{{ URL::route('get.auction.item',$auction) }}">@if($auction=='bid')新增拍賣@else新增競投@endif</a>
        <!-- <div class="dropdown pull-xs-right">  
             <button type="button" class="btn dropdown-toggle" id="rank" 
                data-toggle="dropdown">                 
               {{ $college->first()->name }}
                <span class="caret"></span>
             </button>
             <ul class="dropdown-menu" role="menu" aria-labelledby="rank">
              @foreach($colleges as $college)
                <li role="presentation">
                   <a href="{{ URL::route('get.auction.index',['college' =>  $college->acronym,'auction' => $auction,'type' =>  $type ]) }}" class="dropdown-item">{{ $college->name }}</a>
                </li>
              @endforeach
             </ul>
        </div> -->
      </div>

<div class="col-md-12 card-columns">
@if($items->count()==0)
<h4><center>暫無項目</center></h4>
@else
  @foreach($items as $item)

  <div class="itemcol">
    <div class="card card-small">
        <div>           
                @if($item->albums()->first()->images()->first()!=null)
                  <img class="img-fluid " style="width: 100%;" src="/images/auctions/thumbs/{{ $item->albums()->first()->images()->first()->file_name }}" alt="Photo of sunset">
                @endif  
                <a href="{{ URL::route('get.auction.item.show', ['auction' =>  $auction,'id' => $item->id]) }}">
                    <div class="thumb-cover"></div>
                </a>            
            <div class="details"> 
                <div class="pull-right">
                    @if($item->free==1)<span class="freeIcon"></span> @endif @if($item->new==1)<span class="newIcon"></span>@endif
                </div>
                <div class="clearfix"></div>
            </div>            
        </div>
        <div class="card-info">
            <div class="moving">
                    <h3>
                      <a href="{{ URL::route('get.auction.index',['college' =>  $item->user()->first()->college()->first()->acronym,'auction' => $auction,'type' => $item->category()->first()->en_name]) }}" class="label label-success">{{ $item->category()->first()->name }}</a>
                      <a href="{{ URL::route('get.auction.item.show', ['auction' =>  $auction,'id' => $item->id]) }}">{{ str_limit($item['name'], $limit = 14, $end = '...') }}
                    </h3>
                    @if($item->type==1)<p>{!! str_limit(nl2br(e($item->description)), $limit = 80, $end = '...') !!}</p>@endif</a>
                    <div><font size="4" color="red">@if($item->free==1)免費@elseif(count($item->users()->get()) == 0)NT${{ $item->price }} @elseif($auction=="bid") NT${{ $item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price }}@else NT${{ $item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price }} @endif</font>
                    @if($item->free==0)<span class="auctionNumIcon"></span> <span class="label label-pill label-warning">{{ $item->users()->count() }}</span>@endif</div>
                    <b><div class="clockIcon" data-countdown="{{ $item->end_time  }}"></div></b>
                    
                             
                <!-- <b class="actions">
                    <a href="#/product/awesome-landing-page">Details</a>
                    <b class="separator">|</b>
                    <a class="blue-text" href="#/live/awesome-landing-page" target="_blank">Live Preview</a>
                </b> -->
            </div>
        </div>
    </div>
</div>

<!--   <div class="card card-block">
    <div class="text-center">

      <a href="{{ URL::route('get.auction.item.show', ['auction' =>  $auction,'id' => $item->id]) }}">
        @if($item->free==1)[免費] @endif{{ $item->name }}<br>
        @if($auction=='bid')
        <img class="img-fluid " style="width: 100%;" src="/images/auctions/thumbs/{{ $item->albums()->first()->images()->first()->file_name }}" alt="Photo of sunset">

        @endif  
      </a>
      <font color="red">@if($item->free==1)免費@elseif(count($item->users()->get()) == 0)${{ $item->price }} @elseif($auction=="bid") ${{ $item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price }}@else ${{ $item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price }} @endif</font>
      <b><div data-countdown="{{ $item->end_time  }}"></div></b>
    </div>
  </div>  -->
  @endforeach
@endif



</div>
<div class="col-lg-12">
<center>{!! $items->render() !!}</center>
</div>

</div>

</div>


<script type="text/javascript">

  $('[data-countdown]').each(function() {
    var $this = $(this),
        finalDate = $(this).data('countdown');

    $this.countdown(finalDate, function(event) {
      $this.html(event.strftime('%D 天 %H 時 %M 分 %S 秒'));
    });
  });
</script>




	</div>
@endsection
