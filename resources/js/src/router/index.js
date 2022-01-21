import Vue from 'vue'
import VueRouter from 'vue-router'

// Routes
import apps from './routes/apps'
import dashboard from './routes/dashboard'
import uiElements from './routes/ui-elements/index'
import pages from './routes/pages'
import chartsMaps from './routes/charts-maps'
import formsTable from './routes/forms-tables'
import others from './routes/others'

import admin from './routes/admin'
import client from './routes/client'

import { canNavigate } from '@/libs/acl/routeProtection'
import { isUserLoggedIn, getUserData, getHomeRouteForLoggedInUser } from '@/auth/utils'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    scrollBehavior() {
        return { x: 0, y: 0 }
    },
    routes: [
        { path: '/', redirect: { name: 'dashboard-ecommerce' } },
        ...apps,
        ...dashboard,
        ...pages,
        ...chartsMaps,
        ...formsTable,
        ...uiElements,
        ...others,
        ...admin,
        ...client,
        {
            path: '*',
            redirect: 'error-404',
        },
    ],
})

router.beforeEach((to, _, next) => {
    console.log("to", to)
    var userData = JSON.parse(localStorage.getItem('userData'))
    console.log("userData", userData)
    console.log(canNavigate(to))
    if (!canNavigate(to)) {
        // Redirect to login if not logged in
        if (userData == null) {
            return next('/login')
        }
        // If logged in => not authorized
        return next('/access-control')
    }
    // Redirect if logged in
    if (to.meta.redirectIfLoggedIn && userData != null) {
        next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
    }

    return next()
})

/** 
router.beforeEach((to, from, next) => {
    const publicPages = ['/login', '/register', '/home'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = localStorage.getItem('user');

    // trying to access a restricted page + not logged in
    // redirect to login page
    if (authRequired && !loggedIn) {
        next('/login');
    } else {
        next();
    }
});
*/
/** 
router.beforeEach((to, _, next) => {
    const isLoggedIn = isUserLoggedIn()

    if (!canNavigate(to)) {
        // Redirect to login if not logged in
        if (!isLoggedIn) return next({ name: 'auth-login' })

        // If logged in => not authorized
        return next({ name: 'misc-not-authorized' })
    }

    // Redirect if logged in
    if (to.meta.redirectIfLoggedIn && isLoggedIn) {
        const userData = getUserData()
        next(getHomeRouteForLoggedInUser(userData ? userData.role : null))
    }

    return next()
})
*/

// ? For splash screen
// Remove afterEach hook if you are not using splash screen
router.afterEach(() => {
    // Remove initial loading
    const appLoading = document.getElementById('loading-bg')
    if (appLoading) {
        appLoading.style.display = 'none'
    }
})

export default router