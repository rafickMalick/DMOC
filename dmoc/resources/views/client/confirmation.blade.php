@extends('layouts.client')
@section('title','Confirmation')
@section('content')
<div class='relative min-h-[70vh] rounded-xl border border-[#5a2080] bg-[#1a0035] p-6 flex items-center justify-center text-center'>
  <div>
      <h1 class='mt-4 text-5xl font-black'>Order confirmed!</h1>
      @if($order)
          <p class='mt-2 text-[#c4b5d6]'>Order number <span class='text-[#d304f4] font-bold'>#{{ $order->id }}</span></p>
          <p class='mt-1 text-[#c4b5d6]'>Total: {{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</p>
      @else
          <p class='mt-2 text-[#c4b5d6]'>Your order has been successfully recorded.</p>
      @endif
      <div class='mt-5 flex gap-2 justify-center'>
          <x-btn-primary onclick="window.location.href='{{ route('client.orders') }}'">View my orders</x-btn-primary>
          <x-btn-outline onclick="window.location.href='{{ route('client.home') }}'">Back to home</x-btn-outline>
      </div>
  </div>
</div>
@endsection