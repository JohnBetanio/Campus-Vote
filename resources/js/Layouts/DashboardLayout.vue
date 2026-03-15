<template>
  <div class="dashboard-container">
    <aside class="sidebar">
      <div class="sidebar-header">
        <Link
          :href="auth.voter ? urls.voter.dashboard : urls.admin.dashboard"
          class="sidebar-logo"
        >
          <span class="sidebar-logo-icon">C</span>
          <span class="sidebar-logo-text">CampusVote</span>
        </Link>
      </div>

      <nav class="sidebar-nav" v-if="auth.voter">
        <Link :href="urls.voter.dashboard" class="nav-item" :class="{ active: isActive('voter.dashboard') }">Home</Link>
        <Link :href="urls.voter.vote" class="nav-item" :class="{ active: isActive('voter.vote') }">Vote Now</Link>
        <Link :href="urls.voter.votes" class="nav-item" :class="{ active: isActive('voter.votes') }">View Votes</Link>
        <Link :href="urls.voter.results" class="nav-item" :class="{ active: isActive('voter.results') }">View Results</Link>
        <Link :href="urls.voter.profile" class="nav-item" :class="{ active: isActive('voter.profile') }">Profile</Link>
      </nav>

      <nav class="sidebar-nav" v-else-if="auth.admin">
        <Link :href="urls.admin.dashboard" class="nav-item" :class="{ active: isActive('admin.dashboard') }">Home</Link>
        <Link :href="urls.admin.elections_index" class="nav-item" :class="{ active: isElectionsPage }">Manage Election</Link>
        <Link :href="urls.admin.elections_create" class="nav-item" :class="{ active: isActive('admin.elections.create') }">Create Election</Link>
        <Link :href="urls.admin.voters_index" class="nav-item" :class="{ active: isVotersPage }">Voters</Link>
        <Link :href="urls.admin.results_index" class="nav-item" :class="{ active: isResultsPage }">Results</Link>
        <Link :href="urls.admin.announcements_index" class="nav-item" :class="{ active: isAnnouncementsPage }">Announcements</Link>
      </nav>

      <div class="sidebar-footer">
        <form :action="auth.voter ? urls.voter.logout : urls.admin.logout" method="POST">
          <input type="hidden" name="_token" :value="csrfToken" />
          <button type="submit" class="btn-logout">{{ auth.voter ? 'Log out' : 'Logout' }}</button>
        </form>
        <div class="copyright">© 2025 CampusVote. All rights reserved.</div>
      </div>
    </aside>

    <main class="main-content">
      <header class="top-bar">
        <input type="search" placeholder="Search elections and results" class="search-box" aria-label="Search" />
        <div class="top-bar-right">
          <div v-if="auth.voter" class="user-profile">
            <div class="user-avatar">{{ (auth.voter.name || 'U').charAt(0) }}</div>
            <div class="user-info">
              <span class="user-name">{{ auth.voter.name || 'Voter' }}</span>
              <span class="user-email">{{ auth.voter.email || '' }}</span>
            </div>
          </div>
          <div v-else-if="auth.admin" class="user-profile">
            <div class="user-avatar user-avatar-admin">A</div>
            <div class="user-info">
              <span class="user-name">{{ auth.admin.name || 'Admin' }}</span>
              <span class="user-email">{{ auth.admin.email || 'CampusVote Administration' }}</span>
            </div>
          </div>
        </div>
      </header>

      <div class="content-area">
        <div class="page-header">
          <h1 class="page-title">{{ pageTitle || 'Dashboard' }}</h1>
          <p class="page-subtitle">{{ pageSubtitle || 'Plan, prioritize, and accomplish your tasks with ease.' }}</p>
          <div v-if="$slots.actions" class="page-actions"><slot name="actions" /></div>
        </div>

        <div v-if="flash.success" class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-emerald-500 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
          <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/10">✓</span>
          <p class="leading-relaxed">{{ flash.success }}</p>
        </div>
        <div v-if="flash.error" class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800">
          <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-rose-500/10">!</span>
          <p class="leading-relaxed">{{ flash.error }}</p>
        </div>
        <div v-if="flash.info" class="mb-5 flex items-start gap-3 rounded-lg border-l-4 border-sky-500 bg-sky-50 px-4 py-3 text-sm text-sky-800">
          <span class="mt-0.5 inline-flex h-5 w-5 items-center justify-center rounded-full bg-sky-500/10">i</span>
          <p class="leading-relaxed">{{ flash.info }}</p>
        </div>

        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

defineProps({
  pageTitle: { type: String, default: 'Dashboard' },
  pageSubtitle: { type: String, default: '' },
});

const page = usePage();
const auth = computed(() => page.props.auth || { voter: null, admin: null });
const urls = computed(() => page.props.urls || { voter: {}, admin: {} });
const flash = computed(() => ({
  success: typeof page.props.flash?.success === 'function' ? page.props.flash.success() : page.props.flash?.success,
  error: typeof page.props.flash?.error === 'function' ? page.props.flash.error() : page.props.flash?.error,
  info: typeof page.props.flash?.info === 'function' ? page.props.flash.info() : page.props.flash?.info,
}));

const currentRoute = computed(() => window.location.pathname);
const isActive = (name) => {
  const map = {
    'voter.dashboard': '/voter/dashboard',
    'voter.vote': '/voter/vote',
    'voter.votes': '/voter/votes',
    'voter.results': '/voter/results',
    'voter.profile': '/voter/profile',
    'admin.dashboard': '/admin/dashboard',
    'admin.elections.create': '/admin/elections/create',
  };
  return currentRoute.value === (map[name] || '');
};
const isElectionsPage = computed(() => currentRoute.value.startsWith('/admin/elections'));
const isVotersPage = computed(() => currentRoute.value.startsWith('/admin/voters'));
const isResultsPage = computed(() => currentRoute.value.startsWith('/admin/results'));
const isAnnouncementsPage = computed(() => currentRoute.value.startsWith('/admin/announcements'));

const csrfToken = typeof document !== 'undefined' ? (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '') : '';
</script>
