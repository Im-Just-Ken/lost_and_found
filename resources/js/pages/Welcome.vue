<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login, register } from '@/routes';
import AppearanceDropdown from '@/components/AppearanceDropdown.vue';

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
            <main class="w-full max-w-5xl overflow-hidden rounded-2xl">
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
                        <div class="flex flex-1 justify-center">
                            <div class="relative">
                                <div
                                    class="absolute -inset-4 rounded-2xl bg-gradient-to-tr from-zinc-200/50 to-transparent blur-xl dark:from-zinc-800/30"
                                ></div>

                                <img
                                    src="/assets/static/1.png"
                                    alt="Lost and Found Illustration"
                                    class="relative w-full max-w-sm object-contain drop-shadow-xl lg:max-w-md"
                                />
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>
