import { createRouter, createWebHistory } from "vue-router";

import store from "./store/store";


const routes = [
    {
        path: "/",
        name: "Home",
        component: () => import('./pages/home.vue'),
    },
    {
        path: "/login",
        name: "Login",
        component: () => import('./pages/login.vue'),
        meta: {
            requireAuth: false,
        },
    },
    {
        path: "/register",
        name: "Register",
        component: () => import('./pages/register.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/employeelist",
        name: "EmpList",
        component: () => import('./pages/employee/employeeList.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/empdetails/:id",
        name: "EmpDetails",
        component: () => import('./pages/employee/employeeDetails.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/emp/:id",
        name: "Emp",
        component: () => import('./pages/employee/employee.vue'),
        meta: {
            requireAuth: true,
        },
        children: [
            {
                path: "/employee",
                name: "Employee",
                component: () => import('./pages/employee/employeeInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/employee/:id",
                name: "Employeeid",
                component: () => import('./pages/employee/employeeInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/personal/",
                name: "Personal",
                component: () => import('./pages/employee/personalInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/personal/:id",
                name: "Personalid",
                component: () => import('./pages/employee/personalInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/official",
                name: "Official",
                component: () => import('./pages/employee/officialInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/official/:id",
                name: "Officialid",
                component: () => import('./pages/employee/officialInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/professional",
                name: "Professional",
                component: () => import('./pages/employee/professionalInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
            {
                path: "/professional/:id",
                name: "Professionalid",
                component: () => import('./pages/employee/professionalInfo.vue'),
                meta: {
                    requireAuth: true,
                },
            },
        ],
    },
    {
        path: "/leave",
        name: "Leave",
        component: () => import('./pages/leave/leave.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/leave-apply",
        name: "LeaveApply",
        component: () => import('./pages/leave/leaveApplication.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/leave-status",
        name: "LeaveStatus",
        component: () => import('./pages/leave/leaveStatus.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/attendence",
        name: "Attendence",
        component: () => import('./pages/employee/attendence.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/atten",
        name: "atten",
        component: () => import('./pages/employee/atten.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/report-attendence",
        name: "AttendenceReport",
        component: () => import('./pages/report/AttendenceReport.vue'),
        meta: {
            requireAuth: true,
        },
    },
    {
        path: "/create-role",
        name: "CreateRole",
        component: () => import('./pages/security/createRole.vue'),
        meta: {
            requireAuth: true,
        },
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requireAuth && store.getters.getToken == 0) {
        return { name: "Login" };
    }
    if (to.meta.requireAuth == false && localStorage.getItem("token")) {
        return { name: "Dashboard" };
    }
});

// router.beforeEach((to, from, next) => {
//     if (to.matched.some(record => record.meta.requiresAuth)) {
//       if (!store.getters.isAuthenticated) {
//         next({
//           path: '/login',
//           query: { redirect: to.fullPath },
//         });
//       } else {
//         next();
//       }
//     } else {
//       next();
//     }
// });


export default router;
