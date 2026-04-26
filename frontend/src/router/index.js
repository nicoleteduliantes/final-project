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
    // AUTH ROUTES (NO SIDEBAR)
    {
        path: '/',
        component: Landing,
    },
    {
        path: '/student-login',
        component: StudentLogin,
    },
    {
        path: '/student-register',
        component: StudentRegister,
    },
    {
        path: '/admin-login',
        component: AdminLogin,
    },

    // APP ROUTES (WITH SIDEBAR)
    {
        path: '/',
        component: MainLayout,
        children: [
            /* STUDENT */
            { path: 'dashboard', component: StudentDashboard },
            { path: 'discover', component: Discover },
            { path: 'memberships', component: CurrentMemberships },
            { path: 'participation', component: ParticipationRecord },
            { path: 'org/:id', component: OrgProfile },
            { path: 'apply/:id', component: ApplicationForm },

            /* ORG */
            { path: 'org/dashboard', component: OrgDashboard },
            { path: 'org/members', component: ManageMembers },
            { path: 'org/applications', component: ReviewApplications },
            { path: 'org/events', component: UpcomingEvents },
            { path: 'org/events/new', component: NewEvent },
            { path: 'org/events/edit/:id', component: EventEditor },
            { path: 'org/attendance', component: AttendanceTracker },

            /* OSA */
            { path: 'osa/dashboard', component: OSAAdminDashboard },
            { path: 'osa/colleges', component: CollegeProgramManager },
            { path: 'osa/orgs', component: GlobalOrgManager },
            { path: 'osa/registration', component: OrgRegister },
            { path: 'osa/students', component: StudentManager },
        ],
    },
];

export default createRouter({
    history: createWebHistory(),
    routes,
});
