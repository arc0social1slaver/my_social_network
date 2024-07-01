<script setup>
import { onMounted, ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import SideBar from '@/Components/SideBar.vue';
import Notifications from '@/Components/Notifications.vue';

defineProps({
    title: String,
});
const page = usePage();
const showingNavigationDropdown = ref(false);
const unreadNotifications = page.props.my_auth.unreadNotifications;
const readNotifications = page.props.my_auth.readNotifications;
const notifications = page.props.my_auth.notifications;

// const switchToTeam = (team) => {
//     router.put(route('current-team.update'), {
//         team_id: team.id,
//     }, {
//         preserveState: false,
//     });
// };

const logout = () => {
    router.post(route('logout'));
};
const listen = () => {
    Echo.private(`App.Models.User.${page.props.auth.user.id}`)
        .notification((notification) => {
            let newUnreadNotifications = {
                data: {
                    info: {
                        avatar: notification.info.avatar,
                        message: notification.info.message,
                        link: notification.info.link,
                        sent: notification.info.sent,
                    }
                }, 
                id: notification.id
            };
            unreadNotifications.push(newUnreadNotifications);
            notification.push(newUnreadNotifications);
        })
};
onMounted(() => {
    listen()
});
</script>

<template>
    <!-- <div> -->
        <Head :title="title" />

        <!-- <Banner /> -->

        <div class="flex flex-col min-h-screen">
            <nav class="bg-white border-b border-gray-100 w-full fixed z-20">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard.index')" class="h-3/4 w-16">
                                    <!-- <ApplicationMark class="block h-9 w-auto" /> -->
                                    <img :src="'/images/C-001.jpg'" alt="Logo">
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink :href="route('dashboard.index')" :active="route().current('dashboard.index')">
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:justify-end sm:ml-6 w-1/2">
                            

                            <!-- Settings Dropdown -->
                            <div class="flex items-center ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex items-center text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-transparent transition duration-150 ease-in-out">
                                            <template v-if="$page.props.auth.user">
                                                <span class="capitalize">Welcome, </span>
                                                <span class="ml-1">{{ $page.props.auth.user.username }}</span>
                                            </template>
                                            <span class="capitalize" v-else>Welcome, User!</span>
                                            <img class="h-8 w-8 rounded-full object-cover ml-3" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <!-- <span v-else class="inline-flex rounded-md"> -->
                                            <button type="button" v-else class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                <div>
                                                {{ $page.props.auth.user.username }}
                                                </div>
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        <!-- </span> -->
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Profile
                                        </DropdownLink>

                                        <!-- <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink> -->

                                        <div class="border-t border-gray-200" ></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                                <Dropdown align="right" width="96">
                                    <template #trigger>
                                        <button class="mt-1 focus:outline-none">
                                            <div class="flex ml-2 relative">
                                                <Icon name="bell" class="w-6 h-6"></Icon>
                                                <template v-if="unreadNotifications.length > 0">
                                                    <span class="text-white text-xs bg-red-600 rounded-full px-2 py-1 absolute bottom-2 left-3">
                                                        {{ unreadNotifications.length }}
                                                    </span>
                                                </template>
                                            </div>
                                        </button>
                                    </template>

                                    <template #content v-if="notifications.length > 0">
                                        <Link :href="route('notifications.update')" class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-300 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                            Mark all as read
                                        </Link>
                                        <div class="border-t border-gray-100">
                                        </div>
                                        <Notifications :unreads="unreadNotifications" :reads="readNotifications">
                                        </Notifications>
                                    </template>
                                    <template #content v-else>
                                        <div class="block text-center px-4 py-2 text-xs text-gray-400">
                                            You have no notifications!
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard.index')" :active="route().current('dashboard.index')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <!-- <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink> -->

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <!-- <template v-if="$page.props.jetstream.hasTeamFeatures"> -->
                                <!-- <div class="border-t border-gray-200" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div> -->

                                <!-- Team Settings -->
                                <!-- <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </ResponsiveNavLink>

                                <div class="border-t border-gray-200" /> -->

                                <!-- Team Switcher -->
                                <!-- <div class="block px-4 py-2 text-xs text-gray-400">
                                    Switch Teams
                                </div>

                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ResponsiveNavLink as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </ResponsiveNavLink>
                                    </form>
                                </template> -->
                            <!-- </template> -->
                        </div>
                    </div>
                </div>
            </nav>
            
            <!-- Page Heading -->
            <!-- <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->
            <div class="flex pt-16 sm:flex-nonwrap">
            <!-- Page Sidebar -->
                <SideBar>

                </SideBar>
            <!-- Page Content -->
                <main class="bg-gray-200 shadow rounded my-5 py-6 px-4 w-full sm:w-2/3 sm:mx-2 sm:my-3 md:mx-4 md:px-10 lg:mx-auto">
                <slot />
                 
                </main>
            </div>
            
        </div>
    <!-- </div> -->
</template>
<style>
body.swal-2-toast-shown .swal2-container.swal2-top-end, body.swal2-toast-shown .swal2-container.swal2-top-right{
    top: 60px; 
}
</style>