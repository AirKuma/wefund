@extends('layouts.master')

@section('content')


<div class="container" >

      <div class="row" >

<!--         <div class="col-sm-3">
        	<div class="card card-block" style="background-color: #FFFFFF;">

			</div>
        </div> -->
        <!-- /.blog-sidebar -->
<div class="col-lg-3">
  <div class="list-group">
      <a href="{{ URL::route('get.auction.admin','bid') }}" class="list-group-item {{ set_active(['auction/bid/admin','auction/bid/admin']) }}">我的拍賣<span class="label label-pill label-warning pull-xs-right">{{ $allitems->where('type',0)->count() }}</span></a>
      <a href="{{ URL::route('get.auction.admin','seek') }}" class="list-group-item {{ set_active(['auction/seek/admin','auction/seek/admin']) }}">我的競投<span class="label label-pill label-warning pull-xs-right">{{ $allitems->where('type',1)->count() }}</span></a>
    </div>
</div>

	        <div class="col-lg-9 search">
        	<div>
        @if($items->count()==0)
        <h4><center>暫無項目</center></h4>
    @else
<ul class="list-group">

@foreach ($items as $index => $item)
 <li class="list-group-item">
  <h5 class="list-group-item-heading">
        <a href="{{ URL::route('get.auction.item.show', ['auction' =>  $auction,'id' => $item->id]) }}">{{ str_limit($item['name'], $limit = 50, $end = '...') }}</a>
  </h5>
  @if($item->free==0)<span class="basePriceIcon"></span>${{$item['price']}}@endif 
    <span class="currentPriceIcon"></span><font color="red">@if($item->free==1)免費@elseif(count($item->users()->get()) == 0)${{ $item->price }} @elseif($auction=="bid") ${{ $item->users()->orderBy('item_user.price', 'desc')->first()->pivot->price }}@else ${{ $item->users()->orderBy('item_user.price', 'asc')->first()->pivot->price }} @endif</font>
    @if($item->free==0)<span class="auctionNumIcon"></span> <span class="label label-pill label-warning">{{ $item->users()->count() }}</span>@endif
    <span class="pull-right-780">@if($item->disabled==1)此項目已遭封鎖@elseif($item->repost<=2 && $item->end_time <= $now && $item->users()->first()==null)<button data-toggle="modal" data-target="#repostItemModal-{{ $item->id }}" data-whatever="@mdo" type="button" class="btn btn-secondary refreshIcon">重發項目</button>@elseif($item->end_time <= $now && $item->users()->first()!=null)項目成功@elseif($item->end_time <= $now && $item->users()->first()==null)已下架@else<span data-countdown="{{ $item->end_time  }}"></span>@endif</span>
</li>

 <!--Repost modal-->
  <div class="modal fade" id="repostItemModal-{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="repostItemModal" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
          </button>
          <h4 class="modal-title" id="repostItemModal">重PO{{ $item->name }}</h4>
        </div>
    {!! Form::open(['action' => ['Auction\ItemController@postRepost', 'id' => $item->id], 'method' => 'post', 'role' => 'form']) !!}
    <div class="modal-body">
      <h6>至多可以重發三次，你還可以重發 {{ 3-$item->repost }}次，確定要重發該項目？</h6>
           <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
           </div>

          {!! Form::close() !!}
       </div> 
      </div>
    </div>
  </div>
@endforeach
</ul>
@endif

<center>{!! $items->render() !!}</center>
			</div>

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

@endsection

