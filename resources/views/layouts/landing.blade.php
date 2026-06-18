<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DQIN AC - Professional AC Service & Installation')</title>
    <meta name="description" content="@yield('meta_description', 'DQIN AC provides professional AC service, repair, cleaning, and installation in Malaysia. Trusted since 2015.')">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Ctext y=%22.9em%22 font-size=%2290%22%3E%E2%9D%84%EF%B8%8F%3C/text%3E%3C/svg%3E'>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    
    <!-- NAVBAR -->
    <nav class="fixed w-full z-50 bg-white border-b border-gray-100"
         x-data="{ mobileOpen: false }"
         @scroll.window="window.scrollY > 50 ? $el.classList.add('shadow-sm') : $el.classList.remove('shadow-sm')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
                    </svg>
                    <div>
                        <span class="text-lg font-bold text-gray-900">DQIN</span>
                        <span class="text-lg font-bold text-blue-600"> AC</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'nav-link-active' : 'nav-link' }}">Home</a>
                    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'nav-link-active' : 'nav-link' }}">About</a>
                    <a href="{{ route('services') }}" class="{{ request()->routeIs('services') ? 'nav-link-active' : 'nav-link' }}">Services</a>
                    <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'nav-link-active' : 'nav-link' }}">Contact</a>
                    <a href="tel:+6281234567890" class="btn-whatsapp flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        0812-3456-7890
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-50" :aria-expanded="mobileOpen">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" 
             @click.outside="mobileOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="lg:hidden bg-white border-t border-gray-100">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" @click="mobileOpen = false" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Home</a>
                <a href="{{ route('about') }}" @click="mobileOpen = false" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">About</a>
                <a href="{{ route('services') }}" @click="mobileOpen = false" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('services') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Services</a>
                <a href="{{ route('contact') }}" @click="mobileOpen = false" class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('contact') ? 'text-blue-600 bg-blue-50 font-medium' : 'text-gray-600 hover:text-blue-600 hover:bg-gray-50' }}">Contact</a>
                <a href="tel:+6281234567890" @click="mobileOpen = false" class="block w-full bg-green-600 text-white px-4 py-2.5 rounded-lg text-center font-medium mt-2 hover:bg-green-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>0812-3456-7890
                </a>
            </div>
        </div>
    </nav>

    <!-- CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-3">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/>
                        </svg>
                        <div>
                            <span class="text-lg font-bold text-white">DQIN</span>
                            <span class="text-lg font-bold text-blue-400"> AC</span>
                        </div>
                    </div>
                    <p class="text-sm leading-relaxed max-w-md">
                        Professional AC service, repair, and installation. Trusted since 2015.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('about') }}" class="text-sm hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('services') }}" class="text-sm hover:text-white transition-colors">Our Services</a></li>
                        <li><a href="{{ route('contact') }}" class="text-sm hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Contact</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="tel:+6281234567890" class="hover:text-white transition-colors">0812-3456-7890</a></li>
                        <li><a href="mailto:info@dqin-ac.com" class="hover:text-white transition-colors">info@dqin-ac.com</a></li>
                        <li class="text-gray-400">Kuala Lumpur, Malaysia</li>
                        <li class="text-gray-400">Mon - Sat: 08:00 - 20:00</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} DQIN AC. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- WHATSAPP FLOATING BUTTON -->
    <a href="https://wa.me/6281234567890?text=Hello%20DQIN%20AC%2C%20I%20want%20to%20consult%20about%20my%20AC" 
       target="_blank" 
       rel="noopener noreferrer"
       class="whatsapp-float"
       aria-label="Contact via WhatsApp">
        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
        </svg>
    </a>

</body>
</html>
