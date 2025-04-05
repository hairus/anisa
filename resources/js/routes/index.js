import { createRouter, createWebHistory } from "vue-router"


import dashboardPage from "../layouts/dashboard.vue"
import LoginPage from "../layouts/login.vue"
import notFound from "../components/notFound.vue"
import ContentPage from "../pages/admin/contents.vue"
import tablesPage from "../pages/tables.vue"
import AboutPage from "../pages/about.vue"
import Op from "../layouts/op.vue"
import HomePage from "../pages/op/home.vue"
import OpUpload from "../pages/op/upload/uploadPage.vue"
import finalisasi from "../pages/op/finalisasi.vue"
import Cp from "../pages/op/cp/cp.vue"
import Residu from "../pages/op/residu/residu.vue"

import { useAuthStore } from "../store/index.js"

const routes = [{
        path: '/login',
        component: LoginPage,
        name: "LoginPage"
    },
    {
        path: '/admin',
        component: dashboardPage,
        name: "admin",
        children: [{
                path: 'dashboard',
                component: ContentPage,
                name: "content"
            },
            {
                path: 'about',
                component: AboutPage,
                name: "about"
            },
            {
                name: "residu",
                path: "residu",
                component: Residu
            },

        ]
    },
    {
        path: '/op',
        component: Op,
        name: "op",
        children: [{
                path: 'home',
                component: HomePage,
                name: "home"
            },
            {

                name: "opUpload",
                path: "upload",
                component: OpUpload

            },
            {

                name: "finalisasi",
                path: "finalisasi",
                component: finalisasi

            },
            {

                name: "cp",
                path: "cp",
                component: Cp

            },
            {
                name: "datatable-demo",
                path: "datatable-demo",
                component: () => import("../components/DataTable.vue")
            },
            {

                name: "residu",
                path: "residu",
                component: Residu
            },
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound,
        name: ""

    },

]

const router = createRouter({
    history: createWebHistory(),
    routes, // short for `routes: routes`
})

router.beforeEach(async(to, from, next) => {
    const store = useAuthStore()
    if (to.name !== 'LoginPage' && store.isAuth === false) {
        next({ name: 'LoginPage' })
    } else {
        next()
    }
})


export default router
