@extends('layouts.landing')

@section('title', 'About Us - DQIN AC')
@section('meta_description', 'Get to know DQIN AC, a professional AC service and installation company in Malaysia.')

@section('content')

{{-- PAGE HEADER --}}
<section class="bg-white border-b border-gray-100 pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-sm font-medium text-blue-600 mb-2">About Us</p>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Get to Know DQIN AC</h1>
        <p class="text-lg text-gray-500 max-w-2xl">Your trusted partner for professional AC solutions since 2015.</p>
    </div>
</section>

{{-- COMPANY PROFILE --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <p class="text-sm font-medium text-blue-600 mb-2">Company Profile</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                    The Best for <span class="text-blue-600">Your Comfort</span>
                </h2>
                <div class="space-y-4 text-gray-600 leading-relaxed">
                    <p>
                        DQIN AC is an AC service, repair, and installation company with over 
                        10 years of experience in Malaysia. We are committed to providing 
                        the best service with certified professional technicians.
                    </p>
                    <p>
                        Founded in 2015, DQIN AC has served thousands of customers from households, 
                        offices, to large-scale commercial projects. Customer satisfaction is 
                        our top priority.
                    </p>
                    <p>
                        Every technician undergoes regular training and certification, so 
                        you get quality and guaranteed service.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-10">
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                        <div class="text-3xl font-bold text-gray-900">500+</div>
                        <div class="text-sm text-gray-500 mt-1">Happy Customers</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                        <div class="text-3xl font-bold text-gray-900">10+</div>
                        <div class="text-sm text-gray-500 mt-1">Years Experience</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                        <div class="text-3xl font-bold text-gray-900">50+</div>
                        <div class="text-sm text-gray-500 mt-1">Professional Technicians</div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center border border-gray-100">
                        <div class="text-3xl font-bold text-gray-900">1000+</div>
                        <div class="text-sm text-gray-500 mt-1">Units Handled</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <div class="bg-gray-50 rounded-2xl p-10 text-center border border-gray-100">
                    <svg class="w-16 h-16 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900">DQIN AC</h3>
                    <p class="text-gray-500">Professional AC Service & Installation</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- VISION MISSION --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl p-8 border border-gray-100">
                <div class="card-icon mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Vision</h3>
                <p class="text-gray-600 leading-relaxed">
                    To become the leading AC service company in Malaysia known for quality, 
                    trust, and the best service to every customer.
                </p>
            </div>
            <div class="bg-white rounded-xl p-8 border border-gray-100">
                <div class="card-icon mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Mission</h3>
                <ul class="text-gray-600 space-y-2 leading-relaxed">
                    <li class="flex gap-2"><span class="text-blue-600 mt-1">•</span> Provide high-quality AC service at affordable prices</li>
                    <li class="flex gap-2"><span class="text-blue-600 mt-1">•</span> Develop competent and certified professional technicians</li>
                    <li class="flex gap-2"><span class="text-blue-600 mt-1">•</span> Use modern equipment and the best original spare parts</li>
                    <li class="flex gap-2"><span class="text-blue-600 mt-1">•</span> Provide fast and accurate solutions for every AC problem</li>
                    <li class="flex gap-2"><span class="text-blue-600 mt-1">•</span> Build long-term relationships with customers through excellent service</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gray-50 border-y border-gray-100 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">
            Ready to Work with Us?
        </h2>
        <p class="text-gray-500 mb-8">
            Our team is ready to help you with professional and trusted AC service.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="tel:+6281234567890" class="btn-primary">Contact Us</a>
            <a href="https://wa.me/6281234567890" target="_blank" class="btn-whatsapp">WhatsApp</a>
        </div>
    </div>
</section>

@endsection
