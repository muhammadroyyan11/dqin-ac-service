@extends('layouts.landing')

@section('title', 'Contact - DQIN AC')
@section('meta_description', 'Contact DQIN AC via WhatsApp, phone, email, or visit our office. Free consultation about AC service.')

@section('content')

{{-- PAGE HEADER --}}
<section class="bg-white border-b border-gray-100 pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-sm font-medium text-blue-600 mb-2">Contact</p>
        <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Get in Touch</h1>
        <p class="text-lg text-gray-500 max-w-2xl">Free consultation! Contact our team for the best AC solutions.</p>
    </div>
</section>

{{-- CONTACT INFO --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <div class="bg-white rounded-xl p-6 text-center border border-gray-100 hover:border-gray-200 transition-colors">
                <div class="card-icon mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Phone</h3>
                <p class="text-sm text-gray-500 mb-2">Mon - Sat, 08:00 - 20:00</p>
                <a href="tel:+6281234567890" class="font-medium text-blue-600 hover:text-blue-700">0812-3456-7890</a>
            </div>
            <div class="bg-white rounded-xl p-6 text-center border border-gray-100 hover:border-gray-200 transition-colors">
                <div class="card-icon mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">WhatsApp</h3>
                <p class="text-sm text-gray-500 mb-2">Fast response via chat</p>
                <a href="https://wa.me/6281234567890" target="_blank" class="font-medium text-green-600 hover:text-green-700">0812-3456-7890</a>
            </div>
            <div class="bg-white rounded-xl p-6 text-center border border-gray-100 hover:border-gray-200 transition-colors">
                <div class="card-icon mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                <p class="text-sm text-gray-500 mb-2">24 hour response</p>
                <a href="mailto:info@dqin-ac.com" class="font-medium text-blue-600 hover:text-blue-700">info@dqin-ac.com</a>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-12 items-start">
            {{-- Contact Form --}}
            <div>                <p class="text-sm font-medium text-blue-600 mb-2">Send Message</p>
                <h2 class="text-3xl font-bold text-gray-900 mb-8">Have a Question?</h2>
                <form class="space-y-4" x-data="{ name: '', phone: '', email: '', service: '', message: '' }" @submit.prevent="window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(`Hello DQIN AC, I have a question.\n\nName: ${name}\nPhone: ${phone}\nEmail: ${email}\nService Needed: ${service}\nMessage: ${message}`), '_blank')">
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                            <input type="text" x-model="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm" placeholder="Enter your name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone Number</label>
                            <input type="tel" x-model="phone" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm" placeholder="Enter your phone number">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <input type="email" x-model="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm" placeholder="Enter your email">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Service Needed</label>
                        <select x-model="service" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white text-sm">
                            <option value="">Select a service...</option>
                            <option>AC Cleaning</option>
                            <option>AC Repair</option>
                            <option>Freon Refill</option>
                            <option>AC Relocation</option>
                            <option>New AC Installation</option>
                            <option>Office Maintenance</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Message</label>
                        <textarea rows="4" x-model="message" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm" placeholder="Write your message..."></textarea>
                    </div>
                    <button type="submit" class="w-full btn-primary">Send via WhatsApp</button>
                </form>
            </div>

            {{-- Google Maps & Info --}}
            <div class="space-y-6">
                <div class="bg-gray-100 rounded-2xl overflow-hidden h-80">
                    <iframe
                        src="https://www.google.com/maps?q=Kuala%20Lumpur%2C%20Malaysia&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="DQIN AC location in Kuala Lumpur, Malaysia">
                    </iframe>
                </div>

                <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                    <h3 class="font-semibold text-gray-900 mb-4">Additional Information</h3>
                    <div class="space-y-4 text-sm text-gray-600">
                        <div>
                            <p class="font-medium text-gray-900 mb-0.5">Address</p>
                            <p>Example Street, Kuala Lumpur, Malaysia</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 mb-0.5">Business Hours</p>
                            <p>Monday - Saturday: 08:00 - 20:00</p>
                            <p>Sunday: 09:00 - 16:00</p>
                            <p>Emergency Service: 24 Hours</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 mb-0.5">Payment</p>
                            <p>Cash, Bank Transfer, QRIS, E-Wallet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-gray-50 border-y border-gray-100 text-center">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Need Immediate Help?</h2>
        <p class="text-gray-500 mb-8">Our technician team is ready to help you right now.</p>
        <a href="tel:+6281234567890" class="btn-primary inline-block">Call Now</a>
    </div>
</section>

@endsection
