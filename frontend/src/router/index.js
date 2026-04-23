import { createRouter, createWebHistory } from "vue-router"

/* LAYOUT */
import MainLayout from "@/layouts/MainLayout.vue"

/* STUDENT PAGES */
import StudentDashboard from "@/pages/student/StudentDashboard.vue"
import Discover from "@/pages/student/Discover.vue"
import CurrentMemberships from "@/pages/student/CurrentMemberships.vue"
import ParticipationRecord from "@/pages/student/ParticipationRecord.vue"
import EventCalendar from "@/pages/student/EventCalendar.vue"

/* ORG PAGES */
import OrgDashboard from "@/pages/org/OrgDashboard.vue"
import ManageMembers from "@/pages/org/ManageMembers.vue"
import ReviewApplications from "@/pages/org/ReviewApplications.vue"
import UpcomingEvents from "@/pages/org/UpcomingEvents.vue"
import NewEvent from "@/pages/org/NewEvent.vue"
import EventEditor from "@/pages/org/EventEditor.vue"
import AttendanceTracker from "@/pages/org/AttendanceTracker.vue"

const routes = [
{
path: "/",
component: MainLayout,
children: [

/* STUDENT */
{ path: "dashboard", component: StudentDashboard },
{ path: "discover", component: Discover },
{ path: "memberships", component: CurrentMemberships },
{ path: "participation", component: ParticipationRecord },
{ path: "calendar", component: EventCalendar },

/* ORG */
{ path: "org/dashboard", component: OrgDashboard },
{ path: "org/members", component: ManageMembers },
{ path: "org/applications", component: ReviewApplications },
{ path: "org/events", component: UpcomingEvents },
{ path: "org/events/new", component: NewEvent },
{ path: "org/events/edit/:id", component: EventEditor },
{ path: "org/attendance", component: AttendanceTracker },

]
}
]

export default createRouter({
history: createWebHistory(),
routes
})