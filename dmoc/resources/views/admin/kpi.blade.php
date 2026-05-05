@extends('layouts.admin')
@section('title','KPI')
@section('content')
<h1 class='text-3xl font-bold mb-4'>Dashboard Admin</h1>
<x-card class="mb-6">
    <form method="GET" class="grid gap-3 md:grid-cols-4">
        <select name="period" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
            <option value="today" @selected($period === 'today')>Aujourd hui</option>
            <option value="7d" @selected($period === '7d')>7 derniers jours</option>
            <option value="30d" @selected($period === '30d')>30 derniers jours</option>
            <option value="month" @selected($period === 'month')>Ce mois</option>
            <option value="custom" @selected($period === 'custom')>Personnalisee</option>
        </select>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded border border-[#5a2080] bg-[#120024] px-3 py-2 text-white">
        <button class="rounded bg-[#d304f4] px-4 py-2">Appliquer</button>
    </form>
</x-card>

<div class='grid md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6'>
    <x-card><p class="text-[#c4b5d6]">Ventes totales periode</p><p class="text-2xl font-bold">{{ number_format((int)$data['totalSales'], 0, ',', ' ') }} XOF</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Total commandes</p><p class="text-2xl font-bold">{{ $data['total'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Commandes livrees</p><p class="text-2xl font-bold">{{ $data['delivered'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Commandes echouees</p><p class="text-2xl font-bold">{{ $data['failed'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Taux de succes</p><p class="text-2xl font-bold">{{ $data['total'] > 0 ? number_format(($data['delivered'] / $data['total']) * 100, 1) : 0 }}%</p></x-card>
    <x-card><p class="text-[#c4b5d6]">CA COD attendu</p><p class="text-2xl font-bold">{{ number_format((int)$data['expectedCod'], 0, ',', ' ') }} XOF</p></x-card>
    <x-card><p class="text-[#c4b5d6]">CA COD encaisse</p><p class="text-2xl font-bold">{{ number_format((int)$data['collectedCod'], 0, ',', ' ') }} XOF</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Commandes en attente</p><p class="text-2xl font-bold">{{ $data['pendingCount'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Commandes en cours</p><p class="text-2xl font-bold">{{ $data['activeCount'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Nombre utilisateurs</p><p class="text-2xl font-bold">{{ $data['usersCount'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Nombre livreurs</p><p class="text-2xl font-bold">{{ $data['couriersCount'] }}</p></x-card>
    <x-card><p class="text-[#c4b5d6]">Livraisons periode</p><p class="text-2xl font-bold">{{ $data['deliveriesCount'] }}</p></x-card>
</div>

<div class="grid gap-4 lg:grid-cols-2 mb-6">
    <x-card><h2 class="mb-3 text-xl font-semibold">Evolution des commandes</h2><canvas id="ordersTrendChart"></canvas></x-card>
    <x-card><h2 class="mb-3 text-xl font-semibold">Repartition par statut</h2><canvas id="statusChart"></canvas></x-card>
    <x-card><h2 class="mb-3 text-xl font-semibold">Top livreurs</h2><canvas id="courierChart"></canvas></x-card>
    <x-card><h2 class="mb-3 text-xl font-semibold">Top produits</h2><canvas id="productChart"></canvas></x-card>
</div>

<x-card class="mb-6">
    <h2 class="mb-3 text-xl font-semibold">Activite recente</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead><tr class="border-b border-[#5a2080] text-sm uppercase text-[#c4b5d6]"><th class="py-3">N°</th><th class="py-3">Client</th><th class="py-3">Montant</th><th class="py-3">Statut</th><th class="py-3">Livreur</th><th class="py-3">Date</th></tr></thead>
            <tbody>@foreach($data['recentOrders'] as $order)<tr class="border-b border-[#2a0550]"><td class="py-3"><a class="text-[#d304f4]" href="{{ route('admin.orders.show', $order->id) }}">#{{ $order->id }}</a></td><td class="py-3">{{ $order->user?->name ?? '-' }}</td><td class="py-3">{{ number_format((int)$order->total_xof, 0, ',', ' ') }} XOF</td><td class="py-3">{{ $order->status }}</td><td class="py-3">{{ $order->delivery?->courier?->user?->name ?? '-' }}</td><td class="py-3">{{ $order->created_at?->format('d/m/Y H:i') }}</td></tr>@endforeach</tbody>
        </table>
    </div>
</x-card>

<x-card>
    <h2 class="mb-3 text-xl font-semibold">Alertes admin</h2>
    <ul class="space-y-2 text-sm">
        <li>{{ $data['pending24h'] }} commande(s) pending > 24h (<a class="text-[#d304f4]" href="{{ route('admin.orders', ['status' => 'pending']) }}">voir</a>)</li>
        <li>{{ $data['idleCouriers'] }} livreur(s) actifs sans mission</li>
        <li>{{ $data['failedUnprocessed'] }} commande(s) failed a retraiter</li>
    </ul>
</x-card>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const trend=@json($data['ordersTrend']);const status=@json($data['statusDistribution']);const couriers=@json($data['courierPerformance']);const products=@json($data['topProducts']);
new Chart(document.getElementById('ordersTrendChart'),{type:'line',data:{labels:trend.map(i=>i.day),datasets:[{label:'Commandes',data:trend.map(i=>i.total),borderColor:'#d304f4'}]}});
new Chart(document.getElementById('statusChart'),{type:'doughnut',data:{labels:Object.keys(status),datasets:[{data:Object.values(status)}]}});
new Chart(document.getElementById('courierChart'),{type:'bar',data:{labels:couriers.map(i=>i.courier_name),datasets:[{label:'Livraisons reussies',data:couriers.map(i=>i.success_count)}]}});
new Chart(document.getElementById('productChart'),{type:'bar',data:{labels:products.map(i=>i.name),datasets:[{label:'Quantite',data:products.map(i=>i.total_qty)}]}});
</script>
@endsection