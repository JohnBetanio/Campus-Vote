<template>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <Link :href="dashboardUrl" class="sidebar-logo">
                    <span class="sidebar-logo-icon">C</span>
                    <span class="sidebar-logo-text">CampusVote</span>
                </Link>
            </div>

            <!-- Voter sidebar menu -->
            <nav class="sidebar-nav" v-if="isVoterArea">
                <Link
                    :href="urls.voter?.dashboard || '/voter/dashboard'"
                    class="nav-item"
                    :class="{ active: isActive('/voter/dashboard') }"
                    >Home</Link
                >
                <Link
                    :href="urls.voter?.vote || '/voter/vote'"
                    class="nav-item"
                    :class="{ active: isActive('/voter/vote') }"
                    >Vote Now</Link
                >
                <Link
                    :href="urls.voter?.votes || '/voter/votes'"
                    class="nav-item"
                    :class="{ active: isActive('/voter/votes') }"
                    >View Votes</Link
                >
                <Link
                    :href="urls.voter?.results || '/voter/results'"
                    class="nav-item"
                    :class="{ active: isActive('/voter/results') }"
                    >View Results</Link
                >
                <Link
                    :href="urls.voter?.profile || '/voter/profile'"
                    class="nav-item"
                    :class="{ active: isActive('/voter/profile') }"
                    >Profile</Link
                >
            </nav>

            <!-- Admin sidebar menu -->
            <nav class="sidebar-nav" v-if="isAdminArea">
                <Link
                    :href="urls.admin?.dashboard || '/admin/dashboard'"
                    class="nav-item"
                    :class="{ active: isActive('/admin/dashboard') }"
                    >Home</Link
                >
                <Link
                    :href="urls.admin?.elections_index || '/admin/elections'"
                    class="nav-item"
                    :class="{ active: isManageElectionsPage }"
                    >Manage Election</Link
                >
                <Link
                    :href="
                        urls.admin?.elections_create ||
                        '/admin/elections/create'
                    "
                    class="nav-item"
                    :class="{ active: isActive('/admin/elections/create') }"
                    >Create Election</Link
                >
                <Link
                    :href="urls.admin?.voters_index || '/admin/voters'"
                    class="nav-item"
                    :class="{ active: isVotersPage }"
                    >Voters</Link
                >
                <Link
                    :href="urls.admin?.results_index || '/admin/results'"
                    class="nav-item"
                    :class="{ active: isResultsPage }"
                    >Results</Link
                >
                <Link
                    :href="urls.admin?.announcements_index ||'/admin/announcements'"
                    class="nav-item"
                    :class="{ active: isAnnouncementsPage }"
                    >Announcements</Link
                >
            </nav>

            <div class="sidebar-footer">
                <button type="button" class="btn-logout" @click="submitLogout">
                    {{ isVoterArea ? "Log out" : "Logout" }}
                </button>
                <div class="copyright">
                    © 2025 CampusVote. All rights reserved.
                </div>
            </div>
        </aside>

        <main class="main-content">
            <header class="top-bar">
                <input
                    type="search"
                    placeholder="Search elections and results"
                    class="search-box"
                    aria-label="Search"
                />
                <div class="top-bar-right">
                    <div v-if="auth.voter" class="user-profile">
                        <div class="user-avatar">
                            {{ (auth.voter.name || "U").charAt(0) }}
                        </div>
                        <div class="user-info">
                            <span class="user-name">{{
                                auth.voter.name || "Voter"
                            }}</span>
                            <span class="user-email">{{
                                auth.voter.email || ""
                            }}</span>
                        </div>
                    </div>
                    <div v-else-if="auth.admin" class="user-profile">
                        <div class="user-avatar user-avatar-admin">A</div>
                        <div class="user-info">
                            <span class="user-name">{{
                                auth.admin.name || "Admin"
                            }}</span>
                            <span class="user-email">{{
                                auth.admin.email || "CampusVote Administration"
                            }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-area">
                <div class="page-header">
                    <h1 class="page-title">{{ pageTitle || "Dashboard" }}</h1>
                    <p class="page-subtitle">
                        {{
                            pageSubtitle ||
                            "Plan, prioritize, and accomplish your tasks with ease."
                        }}
                    </p>
                    <div v-if="$slots.actions" class="page-actions">
                        <slot name="actions" />
                    </div>
                </div>

                <div
                    v-if="flash.success"
                    class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                >
                    <span
                        class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/10"
                        >✓</span
                    >
                    <p class="leading-relaxed">{{ flash.success }}</p>
                </div>
                <div
                    v-if="flash.error"
                    class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800"
                >
                    <span
                        class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500/10"
                        >!</span
                    >
                    <p class="leading-relaxed">{{ flash.error }}</p>
                </div>
                <div
                    v-if="flash.info"
                    class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-sky-500 bg-sky-50 px-4 py-3 text-sm text-sky-800"
                >
                    <span
                        class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-sky-500/10"
                        >i</span
                    >
                    <p class="leading-relaxed">{{ flash.info }}</p>
                </div>

                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";

defineProps({
    pageTitle: { type: String, default: "Dashboard" },
    pageSubtitle: { type: String, default: "" },
});

const page = usePage();
const auth = computed(() => page.props.auth || { voter: null, admin: null });
const urls = computed(() => page.props.urls || { voter: {}, admin: {} });
const flash = computed(() => ({
    success:
        typeof page.props.flash?.success === "function"
            ? page.props.flash.success()
            : page.props.flash?.success,
    error:
        typeof page.props.flash?.error === "function"
            ? page.props.flash.error()
            : page.props.flash?.error,
    info:
        typeof page.props.flash?.info === "function"
            ? page.props.flash.info()
            : page.props.flash?.info,
}));

const currentRoute = computed(() =>
    typeof window !== "undefined" ? window.location.pathname : "",
);
const isVoterArea = computed(
    () =>
        auth.value.voter ||
        (currentRoute.value.startsWith &&
            currentRoute.value.startsWith("/voter")),
);
const isAdminArea = computed(
    () =>
        auth.value.admin ||
        (currentRoute.value.startsWith &&
            currentRoute.value.startsWith("/admin")),
);
const dashboardUrl = computed(() =>
    isVoterArea.value
        ? urls.value.voter?.dashboard || "/voter/dashboard"
        : urls.value.admin?.dashboard || "/admin/dashboard",
);
const logoutUrl = computed(() =>
    isVoterArea.value
        ? urls.value.voter?.logout || "/voter/logout"
        : urls.value.admin?.logout || "/admin/logout",
);

function submitLogout() {
    // Logout is a GET route in this app; do a full redirect
    window.location.href = logoutUrl.value;
}

function isActive(path) {
    const p = currentRoute.value;
    if (p === path) return true;
    if (path === "/voter/dashboard" || path === "/admin/dashboard")
        return false;
    if (!(p.startsWith && p.startsWith(path))) return false;
    const next = p.charAt(path.length);
    return next === "" || next === "/" || next === "?";
}
const isManageElectionsPage = computed(() => {
    const p = currentRoute.value;
    if (!p.startsWith("/admin/elections")) return false;
    // Keep "Create Election" as the only active item on its page
    if (
        p === "/admin/elections/create" ||
        p.startsWith("/admin/elections/create")
    )
        return false;
    return true;
});
const isVotersPage = computed(() =>
    currentRoute.value.startsWith("/admin/voters"),
);
const isResultsPage = computed(() =>
    currentRoute.value.startsWith("/admin/results"),
);
const isAnnouncementsPage = computed(() =>
    currentRoute.value.startsWith("/admin/announcements"),
);
</script>
