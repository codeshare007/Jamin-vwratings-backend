import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../../store/index'

import AuthRoutes from './routes/auth'
import RatingsRoutes from './routes/ratings'
import AdminRoutes from './routes/admin'

import { Admin, Authenticated, Guest, middlewarePipeline } from './middleware'

Vue.use(VueRouter);

const routes = [
  {
    path: '/auth',
    name: 'auth',
    redirect: { name: 'auth.signin' },
    component: () => import('../../layouts/default'),
    children: AuthRoutes
  },
  {
    path: '/',
    name: 'ratings',
    redirect: { name: 'ratings.dashboard' },
    component: () => import('../../layouts/default'),
    children: RatingsRoutes
  },
  {
    path: '/admin',
    name: 'admin',
    redirect: { name: 'admin.dashboard' },
    component: () => import('../../layouts/admin'),
    meta: {
      middleware: [Admin],
    },
    children: AdminRoutes
  },
  {
    path: '/404',
    name: '404',
    component: () => import('../../views/NotFound'),
    meta: {
      title: 'Page not found'
    }
  }, {
    path: '*',
    redirect: '/404'
  }
];

const router = new VueRouter({
  base: process.env.BASE_URL,
  mode: 'history',
  routes: routes
});

router.beforeEach((to, from, next) => {
  document.title = 'vwRatings ' + to.meta.title;
  next();
});

middlewarePipeline(router, store);

export default router
