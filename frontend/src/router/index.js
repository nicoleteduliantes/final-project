import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

/* LAYOUT */
import MainLayout from '@/layouts/MainLayout.vue';

/* AUTH */
import Landing from '@/pages/auth/Landing.vue';
import StudentLogin from '@/pages/auth/StudentLogin.vue';
import AdminLogin from '@/pages/auth/AdminLogin.vue';

/* STUDENT */
import StudentDashboard from '@/pages/student/StudentDashboard.vue';
import Discover from '@/pages/student/Discover.vue';
import CurrentMemberships from '@/pages/student/CurrentMemberships.vue';
import ParticipationRecord from '@/pages/student/ParticipationRecord.vue';
import ApplicationForm from '@/pages/student/ApplicationForm.vue';
import OrgProfile from '@/pages/student/OrgProfile.vue';

/* ORG */
import OrgDashboard from '@/pages/org/OrgDashboard.vue';
import ManageMembers from '@/pages/org/ManageMembers.vue';
import ReviewApplications from '@/pages/org/ReviewApplications.vue';
import UpcomingEvents from '@/pages/org/UpcomingEvents.vue';
import NewEvent from '@/pages/org/NewEvent.vue';
import EventEditor from '@/pages/org/EventEditor.vue';
import AttendanceTracker from '@/pages/org/AttendanceTracker.vue';

/* OSA */
import OSAAdminDashboard from '@/pages/osa/OSAAdminDashboard.vue';
import CollegeProgramManager from '@/pages/osa/CollegeProgramManager.vue';
import GlobalOrgManager from '@/pages/osa/GlobalOrgManager.vue';
import OrgRegister from '@/pages/osa/OrgRegister.vue';
import StudentManager from '@/pages/osa/StudentManager.vue';

const routes = [
    /* AUTH */
    { path: '/', component: Landing, meta: { public: true } },
    { path: '/student-login', component: StudentLogin, meta: { public: true } },
    { path: '/admin-login', component: AdminLogin, meta: { public: true } },

    /* APP */
    {
        path: '/',
        component: MainLayout,
        meta: { breadcrumb: false },
        children: [
            {
                path: '',
                redirect: { path: '/dashboard' },
                meta: { breadcrumb: false },
            },

            /* STUDENT */
            {
                path: 'dashboard',
                component: StudentDashboard,
                meta: { role: 'student', breadcrumb: 'Dashboard' },
            },
            {
                path: 'discover',
                component: Discover,
                meta: { role: 'student', breadcrumb: 'Discover Organizations' },
            },
            {
                path: 'memberships',
                component: CurrentMemberships,
                meta: { role: 'student', breadcrumb: 'Current Memberships' },
            },
            {
                path: 'participation',
                component: ParticipationRecord,
                meta: { role: 'student', breadcrumb: 'Participation Record' },
            },
            {
                path: 'org/:id',
                component: OrgProfile,
                meta: { role: 'student', breadcrumb: 'Organization Profile' },
            },
            {
                path: 'apply/:org_id',
                component: ApplicationForm,
                meta: { role: 'student', breadcrumb: 'Apply' },
            },

            /* ORG */
            {
                path: 'org/dashboard',
                component: OrgDashboard,
                meta: { role: 'org', breadcrumb: 'Dashboard' },
            },
            {
                path: 'org/members',
                component: ManageMembers,
                meta: { role: 'org', breadcrumb: 'Current Members' },
            },
            {
                path: 'org/applications',
                component: ReviewApplications,
                meta: { role: 'org', breadcrumb: 'Review Applications' },
            },
            {
                path: 'org/events',
                component: UpcomingEvents,
                meta: { role: 'org', breadcrumb: 'Upcoming Events' },
            },
            {
                path: 'org/events/new',
                component: NewEvent,
                meta: { role: 'org', breadcrumb: 'New Event' },
            },
            {
                path: 'org/events/edit/:id',
                component: EventEditor,
                meta: { role: 'org', breadcrumb: 'Event Editor' },
            },
            {
                path: 'org/attendance',
                component: AttendanceTracker,
                meta: { role: 'org', breadcrumb: 'Attendance Tracker' },
            },

            /* OSA */
            {
                path: 'osa/dashboard',
                component: OSAAdminDashboard,
                meta: { role: 'osa', breadcrumb: 'Dashboard' },
            },
            {
                path: 'osa/colleges',
                component: CollegeProgramManager,
                meta: {
                    role: 'osa',
                    breadcrumb: 'Colleges and Degree Programs Manager',
                },
            },
            {
                path: 'osa/orgs',
                component: GlobalOrgManager,
                meta: {
                    role: 'osa',
                    breadcrumb: 'Global Organizations Manager',
                },
            },
            {
                path: 'osa/registration',
                component: OrgRegister,
                meta: { role: 'osa', breadcrumb: 'Organization Registration' },
            },
            {
                path: 'osa/students',
                component: StudentManager,
                meta: { role: 'osa', breadcrumb: 'Students Manager' },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

/* 🔐 ROUTE GUARD */
router.beforeEach((to, from, next) => {
    const auth = useAuthStore();

    // restore session on refresh
    auth.loadUser();

    // allow public routes
    if (to.meta.public) {
        // prevent logged-in users from going back to login
        if (auth.isAuthenticated) {
            if (auth.role === 'student') return next('/dashboard');
            if (auth.role === 'org') return next('/org/dashboard');
            if (auth.role === 'osa') return next('/osa/dashboard');
        }
        return next();
    }

    // block if not logged in
    if (!auth.isAuthenticated) {
        return next('/student-login');
    }

    // role-based restriction
    if (to.meta.role && to.meta.role !== auth.role) {
        if (auth.role === 'student') return next('/dashboard');
        if (auth.role === 'org') return next('/org/dashboard');
        if (auth.role === 'osa') return next('/osa/dashboard');
    }

    next();
});

export default router;
