@extends('laraCRUD::layout')
@section('content')
<div class="container" >
    <div class="row" >
        <div class="text-center col-md-4 col-md-offset-2 block " >
            <h3>Model Generator</h3>    
            <a href="{{url('laracrud/model')}}" class="btn btn-default" >Start</a>
        </div>
        
        <div class="text-center col-md-4 block ">
            <h3>Crud Generator</h3>    
            <a href="{{url('laracrud/crud')}}" class="btn btn-default" >Start</a>
        </div>
    </div>
</div>
<style>
    .block{
        border: 1px solid grey;
        margin-right: 20px;
        padding: 15px;
        border-radius: 4px;
        box-shadow: 0 0 1px;
    }
</style>
@stop()

