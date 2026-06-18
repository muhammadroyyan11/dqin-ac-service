@extends('layouts.landing')

@section('title', 'Services - DQIN AC')
@section('meta_description', 'Premium AC services in Malaysia: cleaning, repair, freon refill, relocation, installation, and commercial maintenance.')

@section('content')

<section class="relative overflow-hidden bg-slate-950 pt-28 pb-20">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,0.25),transparent_30%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,0.18),transparent_30%)]"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <p class="mb-4 text-sm font-semibold uppercase tracking-[0.25em] text-blue-300">Our Services</p>
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-white mb-6">Premium AC care for every space.</h1>
            <p class="text-lg leading-8 text-slate-300">Professional air conditioning services for homes, offices, retail spaces, and commercial properties across Malaysia.</p>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $services = [
                [
                    'title' => 'AC Cleaning',
                    'tag' => 'Maintenance',
                    'desc' => 'Deep cleaning for indoor and outdoor units to restore cooling performance, improve air quality, and reduce energy usage.',
                    'price' => 'RM 75 - RM 150',
                    'items' => ['Filter and coil cleaning', 'Drainage flush', 'Performance check']
                ],
                [
                    'title' => 'AC Repair',
                    'tag' => 'Troubleshooting',
                    'desc' => 'Accurate diagnosis and repair for leaking, noisy, not cooling, electrical faults, and system errors.',
                    'price' => 'From RM 100',
                    'items' => ['Fault inspection', 'Repair recommendation', 'Service warranty']
                ],
                [
                    'title' => 'Freon Refill',
                    'tag' => 'Cooling Recovery',
                    'desc' => 'Refrigerant pressure inspection and refill using suitable gas types for stable and efficient cooling.',
                    'price' => 'RM 150 - RM 400',
                    'items' => ['Pressure test', 'Leak check', 'Cooling test']
                ],
                [
                    'title' => 'AC Relocation',
                    'tag' => 'Moving Service',
                    'desc' => 'Safe dismantling, transport preparation, reinstallation, and complete function testing.',
                    'price' => 'From RM 250',
                    'items' => ['Safe dismantling', 'Neat reinstall', 'System testing']
                ],
                [
                    'title' => 'New Installation',
                    'tag' => 'Setup',
                    'desc' => 'Professional installation with proper bracket placement, piping, drainage, wiring, and finishing.',
                    'price' => 'From RM 400',
                    'items' => ['Site assessment', 'Clean installation', 'Final commissioning']
                ],
                [
                    'title' => 'Commercial Maintenance',
                    'tag' => 'Business Plan',
                    'desc' => 'Scheduled AC servicing for offices, shops, buildings, and commercial premises with priority support.',
                    'price' => 'Custom Quote',
                    'items' => ['Maintenance schedule', 'Priority response', 'Service report']
                ],
            ];
        @endphp

        <div class="grid lg:grid-cols-3 gap-6">
            @foreach($services as $service)
            <article class="grid min-h-[500px] grid-rows-[auto_auto_1fr_auto] rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">{{ $service['tag'] }}</span>
                </div>

                <h2 class="text-2xl font-bold text-slate-950 mb-3">{{ $service['title'] }}</h2>
                <p class="text-sm leading-6 text-slate-500 mb-6">{{ $service['desc'] }}</p>

                <div class="mb-6 space-y-3 self-start">
                    @foreach($service['items'] as $item)
                    <div class="flex items-center gap-3 text-sm text-slate-700">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        {{ $item }}
                    </div>
                    @endforeach
                </div>

                <div class="rounded-2xl bg-slate-950 p-5 text-white self-end w-full">
                    <div class="text-xs font-semibold uppercase tracking-wider text-slate-400">Price</div>
                    <div class="mt-1 text-2xl font-bold">{{ $service['price'] }}</div>
                    <a href="https://wa.me/6281234567890?text=Hello%20DQIN%20AC%2C%20I%27m%20interested%20in%20{{ urlencode($service['title']) }}" target="_blank" rel="noopener noreferrer" class="mt-5 flex w-full items-center justify-center rounded-xl bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-400">
                        Book via WhatsApp
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-blue-600 mb-3">Service Standard</p>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-950 mb-5">Every visit is handled with a clear process.</h2>
                <p class="text-slate-500 leading-7">We inspect, explain the issue, quote transparently, complete the work neatly, and test the unit before handover.</p>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                @foreach(['Inspection', 'Transparent Quote', 'Professional Work', 'Final Testing'] as $step)
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                    <div class="text-lg font-bold text-slate-950">{{ $step }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-slate-950 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Need help choosing the right service?</h2>
        <p class="text-slate-300 mb-8">Tell us your AC problem and our team will recommend the best solution.</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="tel:+6281234567890" class="rounded-xl bg-white px-6 py-3 font-semibold text-slate-950 hover:bg-blue-50 transition-colors">Call Now</a>
            <a href="{{ route('contact') }}" class="rounded-xl border border-white/15 px-6 py-3 font-semibold text-white hover:bg-white/10 transition-colors">Contact Us</a>
        </div>
    </div>
</section>

@endsection
