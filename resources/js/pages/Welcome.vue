<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import AppearanceDropdown from '@/components/AppearanceDropdown.vue';
import {
    Megaphone,
    ClipboardList,
    BookOpen,
    Trophy,
    MapPin,
    Search,
    Folder,
    PackageCheck,
} from 'lucide-vue-next';

import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Navigation, Autoplay } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <header
            class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl"
        >
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="dashboard()"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="login()"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        v-if="canRegister"
                        :href="register()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                    <AppearanceDropdown />
                </template>
            </nav>
        </header>
        <div
            class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0"
        >
            <main class="w-full max-w-7xl overflow-hidden rounded-2xl">
                <section
                    id="home"
                    class="relative flex w-full items-center justify-center px-6 py-20 lg:py-28"
                >
                    <!-- soft background glow -->
                    <div class="pointer-events-none absolute inset-0">
                        <div
                            class="absolute -top-32 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-zinc-200 opacity-40 blur-3xl dark:bg-zinc-800"
                        ></div>
                    </div>

                    <div
                        class="relative flex w-full flex-col-reverse items-center gap-14 lg:flex-row lg:gap-20"
                    >
                        <!-- TEXT -->
                        <div
                            class="flex flex-1 flex-col items-center text-center lg:items-start lg:text-left"
                        >
                            <div
                                class="mb-4 inline-flex items-center rounded-full border border-zinc-200 px-4 py-1 text-xs text-zinc-600 dark:border-zinc-800 dark:text-zinc-400"
                            >
                                Lost & Found System
                            </div>

                            <h1
                                class="text-4xl font-semibold tracking-tight text-zinc-900 lg:text-6xl dark:text-zinc-100"
                            >
                                Reunite people with their lost belongings
                            </h1>

                            <p
                                class="mt-5 max-w-lg text-base leading-relaxed text-zinc-600 lg:text-lg dark:text-zinc-400"
                            >
                                A smart and simple system that helps track,
                                report, and recover lost items efficiently — all
                                in one place.
                            </p>

                            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link
                                    :href="login()"
                                    class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-6 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-zinc-800 dark:bg-zinc-100 dark:text-zinc-900 dark:hover:bg-zinc-200"
                                >
                                    Get Started
                                </Link>

                                <Link
                                    v-if="canRegister"
                                    :href="register()"
                                    class="inline-flex items-center justify-center rounded-md border border-zinc-300 px-6 py-3 text-sm font-medium text-zinc-900 transition hover:bg-zinc-100 dark:border-zinc-700 dark:text-zinc-100 dark:hover:bg-zinc-900"
                                >
                                    Create Account
                                </Link>
                            </div>
                        </div>

                        <!-- IMAGE -->
                        <div class="flex flex-[1.4] justify-center">
                            <div class="relative">
                                <div
                                    class="absolute -inset-4 rounded-2xl bg-gradient-to-tr from-zinc-200/50 to-transparent blur-xl dark:from-zinc-800/30"
                                ></div>

                                <img
                                    src="/assets/static/1.png"
                                    alt="Lost and Found Illustration"
                                    class="relative w-full max-w-md object-contain drop-shadow-xl lg:max-w-3xl"
                                />
                            </div>
                        </div>
                    </div>
                </section>
                <section id="features" class="w-full px-6 py-20 lg:py-28">
                    <div class="mx-auto max-w-7xl">
                        <!-- HEADER -->
                        <div class="mb-10 text-center">
                            <div
                                class="inline-flex items-center gap-2 rounded-full border border-zinc-200 px-4 py-1 text-xs text-zinc-600 dark:border-zinc-800 dark:text-zinc-400"
                            >
                                <Megaphone class="h-3.5 w-3.5" />
                                Company Announcements
                            </div>

                            <h2
                                class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 lg:text-4xl dark:text-zinc-100"
                            >
                                Latest Updates & System Notices
                            </h2>

                            <p class="mt-3 text-zinc-600 dark:text-zinc-400">
                                Stay informed with important system updates,
                                guides, and success reports.
                            </p>
                        </div>

                        <!-- GRID -->
                        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-2">
                            <!-- CARD 1 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <div
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-zinc-900 dark:text-zinc-100"
                                >
                                    <Megaphone class="h-4 w-4 text-zinc-600" />
                                    General Welcome
                                </div>

                                <div class="mb-3 text-xs text-zinc-500">
                                    📅 Posted on: 2026-03-24 08:00
                                </div>

                                <p
                                    class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                >
                                    Welcome to the Lost and Found Portal! Did
                                    you lose something or find an item? We are
                                    here to help you find or return it quickly
                                    and safely.
                                </p>
                            </div>

                            <!-- CARD 2 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <div
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-zinc-900 dark:text-zinc-100"
                                >
                                    <ClipboardList
                                        class="h-4 w-4 text-zinc-600"
                                    />
                                    Staff & Employee Guide
                                </div>

                                <div class="mb-3 text-xs text-zinc-500">
                                    📅 Posted on: 2026-03-24 09:15
                                </div>

                                <p
                                    class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                >
                                    Ensure every found item includes a photo and
                                    exact location. Update status to "In
                                    Custody" once stored properly.
                                </p>
                            </div>

                            <!-- CARD 3 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <div
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-zinc-900 dark:text-zinc-100"
                                >
                                    <BookOpen class="h-4 w-4 text-zinc-600" />
                                    System Guide
                                </div>

                                <div class="mb-3 text-xs text-zinc-500">
                                    📅 Posted on: 2026-03-24 08:30
                                </div>

                                <p
                                    class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                >
                                    Register, post lost/found items, and wait
                                    for system matches automatically.
                                </p>
                            </div>

                            <!-- CARD 4 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:from-emerald-950/30 dark:to-zinc-900"
                            >
                                <div
                                    class="mb-2 flex items-center gap-2 text-sm font-medium text-zinc-900 dark:text-zinc-100"
                                >
                                    <Trophy class="h-4 w-4 text-emerald-600" />
                                    Monthly Success Report
                                </div>

                                <div class="mb-3 text-xs text-zinc-500">
                                    📅 Posted on: 2026-03-24 13:00
                                </div>

                                <p
                                    class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                >
                                    We successfully returned
                                    <span
                                        class="font-semibold text-emerald-600 dark:text-emerald-400"
                                        >45 items</span
                                    >
                                    this month. Great teamwork!
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="services" class="w-full px-6 py-20 lg:py-28">
                    <div class="mx-auto max-w-7xl">
                        <!-- HEADER -->
                        <div class="mb-10 text-center">
                            <div
                                class="inline-flex items-center gap-2 rounded-full border border-zinc-200 px-4 py-1 text-xs text-zinc-600 dark:border-zinc-800 dark:text-zinc-400"
                            >
                                Services
                            </div>

                            <h2
                                class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 lg:text-4xl dark:text-zinc-100"
                            >
                                Everything you need to manage lost & found items
                            </h2>

                            <p class="mt-3 text-zinc-600 dark:text-zinc-400">
                                Smart tools to report, track, search, and
                                reclaim belongings efficiently.
                            </p>
                        </div>

                        <!-- GRID -->
                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                            <!-- CARD 1 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <MapPin
                                    class="mx-auto mb-4 h-6 w-6 text-zinc-700 dark:text-zinc-300"
                                />

                                <h3
                                    class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"
                                >
                                    Report Lost Item
                                </h3>

                                <p
                                    class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"
                                >
                                    Submit details of your lost item to help us
                                    locate it faster.
                                </p>
                            </div>

                            <!-- CARD 2 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <Search
                                    class="mx-auto mb-4 h-6 w-6 text-zinc-700 dark:text-zinc-300"
                                />

                                <h3
                                    class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"
                                >
                                    Report Found Item
                                </h3>

                                <p
                                    class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"
                                >
                                    Found something? Report it and help return
                                    it to its owner.
                                </p>
                            </div>

                            <!-- CARD 3 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <Folder
                                    class="mx-auto mb-4 h-6 w-6 text-zinc-700 dark:text-zinc-300"
                                />

                                <h3
                                    class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"
                                >
                                    Search Database
                                </h3>

                                <p
                                    class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"
                                >
                                    Browse lost and found items quickly and
                                    easily.
                                </p>
                            </div>

                            <!-- CARD 4 -->
                            <div
                                class="rounded-2xl border border-zinc-200 bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                            >
                                <PackageCheck
                                    class="mx-auto mb-4 h-6 w-6 text-zinc-700 dark:text-zinc-300"
                                />

                                <h3
                                    class="text-sm font-semibold text-zinc-900 dark:text-zinc-100"
                                >
                                    Claim Item
                                </h3>

                                <p
                                    class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"
                                >
                                    Securely claim your item once it’s found.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="about" class="w-full px-6 py-20 lg:py-28">
                    <div class="mx-auto max-w-7xl">
                        <!-- HEADER -->
                        <div class="mb-12 text-center">
                            <div
                                class="inline-flex items-center rounded-full border border-zinc-200 bg-white px-4 py-1 text-xs text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400"
                            >
                                About Us
                            </div>

                            <h2
                                class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 lg:text-4xl dark:text-zinc-100"
                            >
                                Built for a smarter lost & found system
                            </h2>
                        </div>

                        <!-- GRID -->
                        <div class="grid gap-10 lg:grid-cols-2">
                            <!-- LEFT: SHADCN CARD STACK -->
                            <div class="space-y-6">
                                <!-- IMAGE CARD -->
                                <div
                                    class="rounded-2xl border bg-white p-2 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <img
                                        src="assets/static/g.jpg"
                                        class="h-64 w-full rounded-xl object-cover"
                                        alt="About 1"
                                    />
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div
                                        class="rounded-2xl border bg-white p-2 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
                                    >
                                        <img
                                            src="assets/static/h.jpg"
                                            class="h-40 w-full rounded-xl object-cover"
                                            alt="About 2"
                                        />
                                    </div>

                                    <div
                                        class="rounded-2xl border bg-white p-2 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"
                                    >
                                        <img
                                            src="assets/static/j.jpg"
                                            class="h-40 w-full rounded-xl object-cover"
                                            alt="About 3"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- RIGHT: SHADCN CARDS -->
                            <div class="space-y-6">
                                <!-- Mission Card -->
                                <div
                                    class="rounded-2xl border bg-white p-6 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <h3
                                        class="text-lg font-semibold text-zinc-900 dark:text-zinc-100"
                                    >
                                        Mission
                                    </h3>

                                    <p
                                        class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                    >
                                        To provide an automated, secure, and
                                        centralized portal that streamlines the
                                        recovery of lost items while supporting
                                        employees through transparency and
                                        accountability.
                                    </p>
                                </div>

                                <!-- Vision Card -->
                                <div
                                    class="rounded-2xl border bg-white p-6 shadow-sm transition hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <h3
                                        class="text-lg font-semibold text-zinc-900 dark:text-zinc-100"
                                    >
                                        Vision
                                    </h3>

                                    <p
                                        class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                                    >
                                        To create a highly organized workplace
                                        where technology removes the stress of
                                        lost property, allowing employees to
                                        focus on success with peace of mind.
                                    </p>
                                </div>

                                <!-- Highlight Card -->
                                <div
                                    class="rounded-2xl border bg-zinc-50 p-4 text-center text-sm text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-950 dark:text-zinc-300"
                                >
                                    Secure • Fast • Transparent • Reliable
                                    System
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="dev" class="w-full px-6 py-20 lg:py-28">
                    <div class="mx-auto max-w-7xl">
                        <!-- HEADER -->
                        <div class="mb-12 text-center">
                            <div
                                class="inline-flex items-center rounded-full border border-zinc-200 bg-white px-4 py-1 text-xs text-zinc-600 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 dark:text-zinc-400"
                            >
                                Meet the Team
                            </div>

                            <h2
                                class="mt-4 text-3xl font-semibold tracking-tight text-zinc-900 lg:text-4xl dark:text-zinc-100"
                            >
                                The people behind the system
                            </h2>

                            <p class="mt-3 text-zinc-600 dark:text-zinc-400">
                                A dedicated group building a reliable Lost &
                                Found platform.
                            </p>
                        </div>

                        <!-- SWIPER -->
                        <Swiper
                            :modules="[Pagination, Navigation, Autoplay]"
                            :slides-per-view="1"
                            :space-between="30"
                            :centered-slides="true"
                            :loop="true"
                            :autoplay="{ delay: 3500 }"
                            :pagination="{ clickable: true }"
                            :navigation="true"
                            :breakpoints="{
                                640: { slidesPerView: 1 },
                                768: { slidesPerView: 2 },
                                1024: { slidesPerView: 3 },
                            }"
                            class="team-swiper !py-10"
                        >
                            <!-- CARD 1 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <img
                                        src="assets/static/image.png"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        James Gatlabayan
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Frontend Developer
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Creative Front-End Developer
                                        specializing in responsive UI systems.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 2 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <img
                                        src="assets/static/stanley.png"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Stanley Lominoque
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        UI/UX Designer
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Focused on intuitive and clean design
                                        systems.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 3 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm transition hover:-translate-y-1 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900"
                                >
                                    <img
                                        src="assets/static/saimond.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Saimond Caile Generaole
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Backend Developer
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Builds secure and scalable backend
                                        systems.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 4 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/daniela.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Daniela Felicity Mahinay
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Project Manager
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Ensures smooth coordination and
                                        delivery.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 5 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/john.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        John Edcel Basan
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        QA Engineer
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Ensures system quality and stability.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 6 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/krizhia.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Krizhia Crizelle Domingo
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Database Admin
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Manages secure database systems.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 7 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/neil.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Neil Delos Santos
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        DevOps Engineer
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Handles deployment pipelines and
                                        reliability.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 8 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/fidel.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Fidel Ramos
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Security Analyst
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Protects systems and security risks.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 9 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/elsa.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Elsa Cardinal
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        System Analyst
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Improves system workflows and
                                        efficiency.
                                    </p>
                                </div>
                            </SwiperSlide>

                            <!-- CARD 10 -->
                            <SwiperSlide>
                                <div
                                    class="rounded-2xl border bg-white p-6 text-center shadow-sm"
                                >
                                    <img
                                        src="assets/static/emirick.jpg"
                                        class="mx-auto mb-4 h-24 w-24 rounded-full border object-cover"
                                    />
                                    <h3 class="text-sm font-semibold">
                                        Emirick James Frisco
                                    </h3>
                                    <p class="text-xs text-zinc-500">
                                        Content Manager
                                    </p>
                                    <p
                                        class="mt-3 text-sm text-zinc-600 dark:text-zinc-400"
                                    >
                                        Manages content strategy and
                                        communication.
                                    </p>
                                </div>
                            </SwiperSlide>
                        </Swiper>
                    </div>
                </section>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
