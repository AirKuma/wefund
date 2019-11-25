@extends('layouts.master')

@section('content')
<div id="products">
    <ul id="items">
    @foreach($products as $item)
        <li class="item">

            <a href="" target="_blank"></a>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            {{ $item->name}} <br/>
            
        </li>
    @endforeach
    
    </ul>
</div>


@endsection