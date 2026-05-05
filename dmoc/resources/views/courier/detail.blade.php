@extends('layouts.courier')
@section('title','Detail')
@section('content')
<livewire:courier.delivery-detail :delivery-id="$delivery->id" />
@endsection