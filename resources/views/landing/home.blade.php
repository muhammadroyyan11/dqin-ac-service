@extends('layouts.landing')

@section('title', 'DQIN AC - Premium Air Conditioning Services in Malaysia')
@section('meta_description', 'DQIN AC provides premium AC service, repair, cleaning, installation, and maintenance for homes and businesses across Malaysia.')

@section('content')

<section class="relative overflow-hidden bg-slate-950 pt-28 pb-20 lg:pt-36 lg:pb-28">
    <div class="absolute inset-0 opacity-30">
        <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-blue-500 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-cyan-400 blur-3xl"></div>
    </div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(59,130,246,0.18),transparent_35%),linear-gradient(135deg,rgba(15,23,42,0.92),rgba(2,6,23,0.98))]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-medium text-blue-100 mb-6">
                    Premium AC Care for Malaysian Homes & Businesses
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight tracking-tight mb-6">
                    Reliable Cooling Solutions, Delivered with Professional Precision.
                </h1>
                <p class="text-lg text-slate-300 leading-relaxed max-w-xl mb-8">
                    From preventive maintenance to urgent repairs and new installations, DQIN AC keeps your spaces cooler, cleaner, and more energy efficient.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 mb-10">
                    <a href="https://wa.me/6281234567890?text=Hello%20DQIN%20AC%2C%20I%20want%20to%20book%20an%20AC%20service" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-xl bg-blue-500 px-6 py-3 font-semibold text-white shadow-lg shadow-blue-500/25 hover:bg-blue-400 transition-colors">
                        Book a Service
                    </a>
                    <a href="{{ route('services') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/5 px-6 py-3 font-semibold text-white hover:bg-white/10 transition-colors">
                        View Packages
                    </a>
                </div>
                <div class="grid grid-cols-3 gap-4 max-w-xl">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">500+</div>
                        <div class="text-xs text-slate-400 mt-1">Happy Customers</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">24h</div>
                        <div class="text-xs text-slate-400 mt-1">Emergency Support</div>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <div class="text-2xl font-bold text-white">10+</div>
                        <div class="text-xs text-slate-400 mt-1">Years Experience</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -inset-4 rounded-[2rem] bg-blue-500/20 blur-2xl"></div>
                <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/10 shadow-2xl">
                    <img src="/asset/1.png" alt="Professional AC service" class="h-[460px] w-full object-cover">
                    <div class="absolute inset-x-6 bottom-6 rounded-2xl bg-slate-950/85 p-5 backdrop-blur border border-white/10">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-blue-200 mb-1">Next available slot</p>
                                <h2 class="text-xl font-bold text-white">Same-day inspection</h2>
                            </div>
                            <div class="rounded-xl bg-emerald-500/15 px-3 py-2 text-sm font-semibold text-emerald-300">Available</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-10 items-end mb-12">
            <div class="lg:col-span-2">
                <p class="text-sm font-semibold text-blue-600 mb-3 uppercase tracking-wider">Our Expertise</p>
                <h2 class="section-title">Complete AC services with clear standards.</h2>
            </div>
            <p class="text-gray-500 leading-relaxed">Professional workmanship, transparent pricing, and dependable after-service support for residential and commercial clients.</p>
        </div>

        @php
            $services = [
                ['title' => 'AC Cleaning', 'desc' => 'Deep cleaning for better airflow, cleaner air, and reduced power consumption.', 'price' => 'From RM 75'],
                ['title' => 'AC Repair', 'desc' => 'Diagnosis and repair for leaking, noisy, warm, or faulty AC units.', 'price' => 'From RM 100'],
                ['title' => 'Freon Refill', 'desc' => 'Pressure checks and refrigerant refill using compatible gas types.', 'price' => 'RM 150 - RM 400'],
                ['title' => 'New Installation', 'desc' => 'Neat installation with bracket, piping, drainage, and performance testing.', 'price' => 'From RM 400'],
                ['title' => 'AC Relocation', 'desc' => 'Safe dismantling, relocation, reinstallation, and complete function test.', 'price' => 'From RM 250'],
                ['title' => 'Commercial Maintenance', 'desc' => 'Scheduled service plans for offices, retail units, and business premises.', 'price' => 'Custom Quote'],
            ];
        @endphp

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($services as $service)
            <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $service['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-5">{{ $service['desc'] }}</p>
                <div class="flex items-center justify-between border-t border-slate-100 pt-4">
                    <span class="text-sm text-slate-400">Starting price</span>
                    <span class="font-bold text-slate-900">{{ $service['price'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="rounded-[2rem] bg-slate-900 p-8 text-white shadow-xl">
                <p class="text-blue-300 text-sm font-semibold uppercase tracking-wider mb-4">Why DQIN AC</p>
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Built for comfort, safety, and long-term performance.</h2>
                <p class="text-slate-300 leading-relaxed mb-8">We combine experienced technicians, modern tools, and structured service reports so every job is handled with confidence.</p>
                <a href="{{ route('contact') }}" class="inline-flex rounded-xl bg-white px-6 py-3 font-semibold text-slate-900 hover:bg-blue-50 transition-colors">Get Consultation</a>
            </div>
            <div class="grid sm:grid-cols-2 gap-5">
                @foreach([
                    ['Certified Technicians', 'Trained team with practical AC field experience.'],
                    ['Transparent Pricing', 'Clear quotations before work starts.'],
                    ['Service Warranty', 'Reliable after-service support for peace of mind.'],
                    ['Fast Response', 'Quick scheduling for homes and businesses.'],
                ] as $item)
                <div class="rounded-2xl bg-white p-6 border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-900 mb-2">{{ $item[0] }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $item[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <p class="text-sm font-semibold text-blue-600 mb-3 uppercase tracking-wider">Ready when you are</p>
        <h2 class="text-3xl md:text-5xl font-bold text-slate-900 mb-6">Keep your AC performing at its best.</h2>
        <p class="text-lg text-slate-500 mb-8 max-w-2xl mx-auto">Speak with our team and get a professional recommendation for your AC issue today.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-3">
            <a href="tel:+6281234567890" class="btn-primary">Call Now</a>
            <a href="https://wa.me/6281234567890?text=Hello%20DQIN%20AC%2C%20I%20need%20help%20with%20my%20AC" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">WhatsApp</a>
        </div>
    </div>
</section>

@endsection
