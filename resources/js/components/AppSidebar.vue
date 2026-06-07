<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import type { NavItem } from '@/types';

import {
    BookOpen,
    FolderGit2,
    KeyRound,
    LayoutGrid,
    BriefcaseBusiness,
    UserRoundCog,
    Users,
    Bell,
    SearchCheck,
} from 'lucide-vue-next';

import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';

// import { dashboard } from '@/routes';

const page = usePage();

const roles = computed(() => page.props.auth?.roles ?? []);

const isAdmin = computed(
    () =>
        roles.value.includes('super-admin') ||
        roles.value.includes('moderator'),
);

const isMember = computed(() => roles.value.includes('member'));

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        // {
        //     title: 'Dashboard',
        //     href: dashboard(),
        //     icon: LayoutGrid,
        // },
    ];

    /* ---------------- ADMIN MENU ---------------- */
    if (isAdmin.value) {
        items.push(
            {
                title: 'Dashboard',
                href: '/admin/dashboard',
                icon: LayoutGrid,
            },
            {
                title: 'Reported Items',
                href: '#',
                icon: SearchCheck,

                children: [
                    {
                        title: 'Missing Items',
                        href: '/admin/reported-items/missing',
                    },
                    {
                        title: 'Pending Verification',
                        href: '/admin/reported-items/pending-verification',
                    },
                    {
                        title: 'Verified Found Items',
                        href: '/admin/reported-items/found',
                    },
                    {
                        title: 'Claimed Items',
                        href: '/admin/reported-items/claimed',
                    },
                ],
            },
            {
                title: 'Users',
                href: '/admin/users',
                icon: Users,
            },
            {
                title: 'Roles',
                href: '/admin/roles',
                icon: BriefcaseBusiness,
            },
            {
                title: 'Permissions',
                href: '/admin/permissions',
                icon: KeyRound,
            },
            {
                title: 'Access Groups',
                href: '/admin/access-groups',
                icon: UserRoundCog,
            },
        );
    }

    if (isMember.value) {
        items.push(
            {
                title: 'Dashboard',
                href: '/member/dashboard',
                icon: LayoutGrid,
            },

            {
                title: 'My Items',
                href: '#',
                icon: FolderGit2,

                children: [
                    {
                        title: 'Missing Items',
                        href: '/member/items',
                    },
                    {
                        title: 'Recovered Items',
                        href: '/member/recovered-items',
                    },
                ],
            },

            {
                title: 'Community',
                href: '#',
                icon: SearchCheck,

                children: [
                    {
                        title: 'Missing Reports',
                        href: '/member/community/missing-reports',
                    },
                    {
                        title: 'Found by Me',
                        href: '/member/community/found-by-me',
                    },
                ],
            },

            // {
            //     title: 'Notifications',
            //     href: '/member/notifications',
            //     icon: Bell,
            // },
        );
    }
    return items;
});

/* --------------------------------
   FOOTER LINKS (STATIC)
-------------------------------- */
const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/vue-starter-kit',
    //     icon: FolderGit2,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#vue',
    //     icon: BookOpen,
    // },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <!-- LOGO -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link>
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- MAIN MENU -->
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- FOOTER -->
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
</template>
