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
import OrgProfile from '@/pages/student/OrgProfile.vue';

/* ORG */
import OrgDashboard from '@/pages/org/OrgDashboard.vue';
import ManageMembers from '@/pages/org/ManageMembers.vue';
import ReviewApplications from '@/pages/org/ReviewApplications.vue';
import UpcomingEvents from '@/pages/org/UpcomingEvents.vue';
import NewEvent from '@/pages/org/NewEvent.vue';
import EventEditor from '@/pages/org/EventEditor.vue';

/* OSA */
import OSAAdminDashboard from '@/pages/osa/OSAAdminDashboard.vue';
import CollegeProgramManager from '@/pages/osa/CollegeProgramManager.vue';
import GlobalOrgManager from '@/pages/osa/GlobalOrgManager.vue';
import OrgRegister from '@/pages/osa/OrgRegister.vue';
import StudentManager from '@/pages/osa/StudentManager.vue';

const routes = [
    /* AUTH (PUBLIC) */
    { path: '/', component: Landing, meta: { public: true } },
    { path: '/student-login', component: StudentLogin, meta: { public: true } },
    { path: '/admin-login', component: AdminLogin, meta: { public: true } },

    /* APP LAYOUT */
    {
        path: '/',
        component: MainLayout,
        children: [
            /* DEFAULT REDIRECT */
            {
                path: '',
                redirect: '/dashboard',
            },

            /* ===================== STUDENT ===================== */

            {
                path: 'dashboard',
                component: StudentDashboard,
                meta: { role: 'student' },
            },

            {
                path: 'discover',
                component: Discover,
                meta: { role: 'student' },
            },

            {
                path: 'memberships',
                component: CurrentMemberships,
                meta: { role: 'student' },
            },

            /* Org Profile (FIXED PARAM) */
            {
                path: 'org/:id',
                component: OrgProfile,
                meta: {
                    role: 'student',
                },
            },

            // /* Application Form */
            // {
            //     path: 'apply/:org_id',
            //     component: ApplicationForm,
            //     meta: {
            //         role: 'student',
            //     },
            // },

            /* ===================== ORG ===================== */

            {
                path: 'org/dashboard',
                component: OrgDashboard,
                meta: { role: 'org' },
            },

            {
                path: 'org/members',
                component: ManageMembers,
                meta: { role: 'org' },
            },

            {
                path: 'org/applications',
                component: ReviewApplications,
                meta: { role: 'org' },
            },

            {
                path: 'org/events',
                component: UpcomingEvents,
                meta: { role: 'org' },
            },

            {
                path: 'org/events/new',
                component: NewEvent,
                meta: { role: 'org' },
            },

            {
                path: 'org/events/edit/:id',
                component: EventEditor,
                meta: { role: 'org' },
            },

            /* ===================== OSA ===================== */

            {
                path: 'osa/dashboard',
                component: OSAAdminDashboard,
                meta: { role: 'osa' },
            },

            {
                path: 'osa/colleges',
                component: CollegeProgramManager,
                meta: { role: 'osa' },
            },

            {
                path: 'osa/orgs',
                component: GlobalOrgManager,
                meta: { role: 'osa' },
            },

            {
                path: 'osa/registration',
                component: OrgRegister,
                meta: { role: 'osa' },
            },

            {
                path: 'osa/students',
                component: StudentManager,
                meta: { role: 'osa' },
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

/* ===================== AUTH GUARD ===================== */
router.beforeEach((to, from, next) => {
    const auth = useAuthStore();

    auth.loadUser();

    /* PUBLIC ROUTES */
    if (to.meta.public) {
        if (auth.isAuthenticated) {
            if (auth.role === 'student') return next('/dashboard');
            if (auth.role === 'org') return next('/org/dashboard');
            if (auth.role === 'osa') return next('/osa/dashboard');
        }
        return next();
    }

    /* NOT LOGGED IN */
    if (!auth.isAuthenticated) {
        return next('/student-login');
    }

    /* ROLE CHECK */
    if (to.meta.role && to.meta.role !== auth.role) {
        if (auth.role === 'student') return next('/dashboard');
        if (auth.role === 'org') return next('/org/dashboard');
        if (auth.role === 'osa') return next('/osa/dashboard');
    }

    next();
});

export default router;
