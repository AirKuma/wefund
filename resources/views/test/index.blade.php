@extends('layouts.master')
 
@section('content')
 
<script type="text/javascript">

    
window.onpopstate = function(event) {
    alert("location: " + document.location + ", state: " + JSON.stringify(event.state));
};

</script>


<section class="items endless-pagination" data-next-page="{{ $items->nextPageUrl() }}">
    @foreach($items as $item)
 
        <div class="article">
            <h2>{{ $item->id }}</h2>
            {{ $item->content }}
            <a href="http://www.google.com">Link</a>
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
            <br/ >
        </div>
 
    @endforeach
 
{{--{!! $items->render() !!}--}}
 
</section>
<script>
 
$(document).ready(function() {
 
 
 
/*    $('body').on('click', '.pagination a', function(e){
 
        e.preventDefault();
        var url = $(this).attr('href');
 
        $.get(url, function(data){
            $('.posts').html(data);
        });
 
    });*/
 
    $(window).scroll(fetchItems);
 
    function fetchItems() {
 
        var page = $('.endless-pagination').data('next-page');
 
        if(page !== null) {
 
            clearTimeout( $.data( this, "scrollCheck" ) );
 
            $.data( this, "scrollCheck", setTimeout(function() {
                var scroll_position_for_items_load = $(window).height() + $(window).scrollTop() + 100;
 
                if(scroll_position_for_items_load >= $(document).height()) {
                    $.get(page, function(data){
                        $('.items').append(data.items);
                        $('.endless-pagination').data('next-page', data.next_page);
                        //window.history.replaceState(null , null , 'https://www.loyaus.com/tests?page=3');
                        //window.history.pushState(null , null , 'https://www.loyaus.com/tests?page=3');



                        
                    });
                }
            }, 350))
 
        }
    }
 
 
})
 
</script>
@stop