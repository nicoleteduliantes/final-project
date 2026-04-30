import { createRouter, createWebHistory } from 'vue-router';

/* LAYOUT */
import MainLayout from '@/layouts/MainLayout.vue';

/* AUTH */
import Landing from '@/pages/auth/Landing.vue';
import StudentLogin from '@/pages/auth/StudentLogin.vue';
import StudentRegister from '@/pages/auth/StudentRegister.vue';
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
    {
        path: '/student-register',
        component: StudentRegister,
        meta: { public: true },
    },
    { path: '/admin-login', component: AdminLogin, meta: { public: true } },

    /* APP */
    {
        path: '/',
        component: MainLayout,
        children: [
            {
                path: '',
                redirect: '/dashboard',
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
                meta: { role: 'student', breadcrumb: 'Discover' },
            },
            {
                path: 'memberships',
                component: CurrentMemberships,
                meta: { role: 'student', breadcrumb: 'Memberships' },
            },
            {
                path: 'participation',
                component: ParticipationRecord,
                meta: { role: 'student', breadcrumb: 'Participation' },
            },
            {
                path: 'org/:id',
                component: OrgProfile,
                meta: { role: 'student', breadcrumb: 'Organization' },
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
                meta: { role: 'org', breadcrumb: 'Members' },
            },
            {
                path: 'org/applications',
                component: ReviewApplications,
                meta: { role: 'org', breadcrumb: 'Applications' },
            },
            {
                path: 'org/events',
                component: UpcomingEvents,
                meta: { role: 'org', breadcrumb: 'Events' },
            },
            {
                path: 'org/events/new',
                component: NewEvent,
                meta: { role: 'org', breadcrumb: 'New Event' },
            },
            {
                path: 'org/events/edit/:id',
                component: EventEditor,
                meta: { role: 'org', breadcrumb: 'Edit Event' },
            },
            {
                path: 'org/attendance',
                component: AttendanceTracker,
                meta: { role: 'org', breadcrumb: 'Attendance' },
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
                meta: { role: 'osa', breadcrumb: 'Colleges' },
            },
            {
                path: 'osa/orgs',
                component: GlobalOrgManager,
                meta: { role: 'osa', breadcrumb: 'Organizations' },
            },
            {
                path: 'osa/registration',
                component: OrgRegister,
                meta: { role: 'osa', breadcrumb: 'Registration' },
            },
            {
                path: 'osa/students',
                component: StudentManager,
                meta: { role: 'osa', breadcrumb: 'Students' },
            },
        ],
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
