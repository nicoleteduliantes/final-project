import { createRouter, createWebHistory } from "vue-router";

// AUTH
import Landing from "../pages/auth/Landing.vue";
import StudentLogin from "../pages/auth/StudentLogin.vue";
import StudentRegister from "../pages/auth/StudentRegister.vue";
import AdminLogin from "../pages/auth/AdminLogin.vue";

// STUDENT
import StudentDashboard from "../pages/student/StudentDashboard.vue";
import Discover from "../pages/student/Discover.vue";
import OrgProfile from "../pages/student/OrgProfile.vue";
import ApplicationForm from "../pages/student/ApplicationForm.vue";
import EventDetail from "../pages/student/EventDetail.vue";
import EventParticipation from "../pages/student/EventParticipation.vue";
import CurrentMemberships from "../pages/student/CurrentMemberships.vue";
import ParticipationRecord from "../pages/student/ParticipationRecord.vue";
import EventCalendar from "../pages/student/EventCalendar.vue";

const routes = [
    // AUTH
    { path: "/", component: Landing },
    { path: "/student-login", component: StudentLogin },
    { path: "/student-register", component: StudentRegister },
    { path: "/admin-login", component: AdminLogin },

    // DASHBOARD
    {
        path: "/dashboard",
        component: StudentDashboard,
        meta: { breadcrumb: "Dashboard" }
    },

    // DISCOVER FLOW (IMPORTANT: NESTED)
    {
        path: "/discover",
        component: Discover,
        meta: { breadcrumb: "Discover" }
    },

    {
        path: "/org/:id",
        component: OrgProfile,
        meta: {
        breadcrumb: (route) => `Organization Profile`
        }
    },

    {
        path: "/apply/:id",
        component: ApplicationForm,
        meta: { breadcrumb: "Application Form" }
    },

    {
        path: "/event/:id",
        component: EventDetail,
        meta: { breadcrumb: "Event Details" }
    },

    {
        path: "/join/:id",
        component: EventParticipation,
        meta: { breadcrumb: "Join Event" }
    },

    // OTHER PAGES
    {
        path: "/memberships",
        component: CurrentMemberships,
        meta: { breadcrumb: "Current Memberships" }
    },
    {
        path: "/participation",
        component: ParticipationRecord,
        meta: { breadcrumb: "Participation Record" }
    },
    {
        path: "/calendar",
        component: EventCalendar,
        meta: { breadcrumb: "Event Calendar" }
    }
];

export default createRouter({
    history: createWebHistory(),
    routes
});