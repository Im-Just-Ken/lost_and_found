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
    <Head title="Welcome" />

    <div class="min-h-screen bg-background text-foreground">
        <!-- NAVBAR -->
        <header
            class="sticky top-0 z-50 border-b bg-background/80 backdrop-blur"
        >
            <nav
                class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4"
            >
                <div class="text-lg font-semibold">Lost & Found System</div>

                <div class="flex items-center gap-3">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="rounded-lg border px-4 py-2 text-sm transition hover:bg-accent"
                    >
                        Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="login()"
                            class="rounded-lg px-4 py-2 text-sm transition hover:bg-accent"
                        >
                            Log in
                        </Link>

                        <Link
                            v-if="canRegister"
                            :href="register()"
                            class="rounded-lg border px-4 py-2 text-sm transition hover:bg-accent"
                        >
                            Register
                        </Link>

                        <AppearanceDropdown />
                    </template>
                </div>
            </nav>
        </header>

        <!-- HERO -->
        <main
            class="mx-auto grid max-w-6xl items-center gap-12 px-6 py-20 md:grid-cols-2"
        >
            <!-- LEFT -->
            <div>
                <h1 class="text-4xl leading-tight font-bold md:text-5xl">
                    Smart Lost and Found <br />
                    Management System
                </h1>

                <p class="mt-4 text-muted-foreground">
                    A centralized platform that helps people recover lost items
                    quickly, securely, and efficiently.
                </p>

                <div class="mt-6 flex gap-3">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="login()"
                        class="rounded-xl bg-primary px-6 py-3 text-sm font-medium text-white hover:opacity-90"
                    >
                        Get Started
                    </Link>

                    <Link
                        v-else
                        :href="dashboard()"
                        class="rounded-xl bg-primary px-6 py-3 text-sm font-medium text-white hover:opacity-90"
                    >
                        Go to Dashboard
                    </Link>
                </div>
            </div>

            <!-- RIGHT (VISUAL CARD) -->
            <div class="relative">
                <div class="rounded-2xl border bg-card p-6 shadow-sm">
                    <div class="mb-2 text-sm text-muted-foreground">
                        System Preview
                    </div>

                    <div class="space-y-3">
                        <div class="h-3 w-3/4 rounded bg-muted"></div>
                        <div class="h-3 w-1/2 rounded bg-muted"></div>
                        <div class="h-3 w-2/3 rounded bg-muted"></div>
                    </div>

                    <div
                        class="mt-6 flex h-40 items-center justify-center rounded-xl bg-muted text-sm text-muted-foreground"
                    >
                        Dashboard Preview UI
                    </div>
                </div>

                <!-- floating decoration -->
                <div
                    class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-primary/10 blur-2xl"
                ></div>
                <div
                    class="absolute -bottom-6 -left-6 h-20 w-20 rounded-full bg-primary/10 blur-2xl"
                ></div>
            </div>
        </main>

        <!-- FOOTER SPACER -->
        <div class="h-16"></div>
    </div>
</template>
