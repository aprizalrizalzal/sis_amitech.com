<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Carousel from './Welcome/Carousel.vue';
import Product from './Welcome/Product.vue';
import Footer from './Setting/Footer/Footer.vue';
import Service from './Welcome/Service.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,

    carousels: Array,
    categorySpareParts: Array,
    spareParts: Array,
    service: Object,

    statusWarrantyServices: Array,
    statusServices: Array,

    message: String,
});

</script>

<template>

    <Head title="Welcome" />
    <nav class="bg-white border-b border-gray-100 shadow-inner">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-24 items-center">
                <!-- Left Section: Logo and Title -->
                <div v-for="header in $page.props.headers" :key="header.id" class="flex items-center">
                    <!-- Logo -->
                    <Link :href="route('show.welcome')">
                    <ApplicationLogo class="block h-16 w-16" />
                    </Link>

                    <Link :href="route('show.welcome')"
                        class="p-4 text-xl flex items-center font-bold text-green-900 hover:text-green-700 hover:border-green-900 focus:outline-none focus:text-green-900 focus:border-green-900 transition duration-150 ease-in-out">
                    <h1>SIService - {{ header.company }}</h1>
                    </Link>
                </div>

                <!-- Right Section: Navigation Links -->
                <div class="hidden space-x-8 sm:flex items-center">
                    <div v-if="canLogin" class="flex items-center font-medium font-bold">
                        <div v-if="$page.props.auth.user" class="flex items-center gap-2">
                            <Link :href="route('show.dashboard')"
                                class="rounded px-3 py-2 text-green-900 ring-1 ring-transparent transition hover:text-green-700 focus:outline-none focus-visible:ring-green-700">
                            Dashboard
                            </Link>
                        </div>
                        <template v-else>
                            <div class="flex items-center gap-2">
                                <Link :href="route('login')"
                                    class="rounded px-3 py-2 text-green-900 ring-1 ring-transparent transition hover:text-green-700 focus:outline-none focus-visible:ring-green-700">
                                Log in
                                </Link>
                                <Link v-if="canRegister" :href="route('register')"
                                    class="rounded px-3 py-2 text-green-900 ring-1 ring-transparent transition hover:text-green-700 focus:outline-none focus-visible:ring-green-700">
                                Register
                                </Link>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded text-green-500 hover:text-green-700 hover:bg-green-100 focus:outline-none focus:bg-green-100 focus:text-green-700 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="#256125" fill="none" viewBox="0 0 24 24">
                            <path
                                :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path
                                :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
            <div v-if="canLogin" class="pt-2 pb-3 space-y-1">
                <div v-if="$page.props.auth.user">
                    <ResponsiveNavLink v-if="$page.props.auth.user" :href="route('show.dashboard')"
                        :active="route().current('show.dashboard')">
                        Dashboard
                    </ResponsiveNavLink>
                </div>
                <template v-else>
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('login')">
                            Login
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="canRegister" :href="route('register')">
                            Register
                        </ResponsiveNavLink>
                    </div>
                </template>

            </div>

        </div>
    </nav>


    <div class="text-green-900/50 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center justify-center">
            <div class="w-full max-w-2xl px-6 lg:max-w-7xl">
                <main class="mt-4 mb-8">
                    <div>
                        <Carousel :carousels="props.carousels" />
                    </div>

                    <div>
                        <Service :service="props.service" :statusWarrantyServices="statusWarrantyServices"
                            :statusServices="statusServices" :message="props.message" />
                    </div>
                    
                    <div>
                        <Product :spareParts="props.spareParts" :categorySpareParts="categorySpareParts" />
                    </div>

                </main>
            </div>
        </div>
        <footer class="pb-4 text-center text-sm text-green-900 bg-white shadow-inner">
            <Footer />
        </footer>
    </div>
</template>
