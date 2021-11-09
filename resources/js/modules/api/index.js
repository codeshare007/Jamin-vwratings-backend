import axios from 'axios';
import Cookie from 'js-cookie';

import apiAuth from './methods/auth';
import apiProfile from './methods/profile';
import apiComments from './methods/comments';
import apiAvis from './methods/avis';

import apiAdminDashboard from './methods/adminDashboard'
import apiAdminAvis from './methods/adminAvis'
import apiAdminComments from './methods/adminCommments'
import apiAdminMessages from './methods/adminMessages'
import apiAdminRatings from './methods/adminRatings';
import apiAdminUsers from './methods/adminUsers'

export default {
  removeJWT() {
    Cookie.remove('access_token');
    Cookie.remove('refresh_token');
  },

  resetJWT(responseData) {
    Cookie.set('access_token', responseData.access_token);
    Cookie.set('refresh_token', responseData.refresh_token);
  },

  clean(obj) {
    for (const propName in obj) {
      // eslint-disable-next-line no-prototype-builtins
      if (!obj.hasOwnProperty(propName)) continue;
      if (obj[propName] === null || obj[propName] === undefined) {
        delete obj[propName];
      }
    }
  },

  trimCookie(cookie) {
    return cookie ? cookie.replace(/[А-Яа-я]/ig, "") : cookie;
  },

  // eslint-disable-next-line no-unused-vars
  setupInstance(store, router) {
    this.apiInstance = axios.create({
      baseURL: '/api/v1/',
      headers: {
        common: {
          'Access': 'application/json',
          'Accept-Language': 'ru',
          'X-Requested-With': 'XMLHttpRequest'
        }
      },
      mode: 'cors'
    });

    this.apiInstance.interceptors.request.use(async config => {
      if (config.method === 'put' || config.method === 'post') {
        this.clean(config.data);
      }
      if (Cookie.get('access_token')) {
        config.headers.common['Authorization'] = `Bearer ${this.trimCookie(Cookie.get('access_token'))}`;
      }
      return config;
    }, async error => {
      return Promise.reject(error);
    });

    this.apiInstance.interceptors.response.use(async response => {
      return response;
    }, async (error) => {
      let code = 0;
      if (error.response)
        code = error.response.status;

      if (code === 401) {
        let errorUrl = error.response.config.url;
        let refreshToken = this.trimCookie(Cookie.get('refresh_token'));
        if (errorUrl !== 'auth/login') {
          if (errorUrl === 'auth/refresh' && refreshToken) {
            await store.dispatch('auth/RESET_PROFILE');
            router.push({name: 'auth.signin'});
          } else {
            await store.dispatch('auth/REFRESH_TOKEN', {
              refresh_token: refreshToken,
            });
          }

          if (store.getters['auth/loggedIn']) {
            try {
              error.response.config.headers.Authorization = `Bearer ${this.trimCookie(Cookie.get('access_token'))}`;
              let response = await axios.request(error.response.config);
              await Promise.resolve(response);
              return response;
            } catch (error) {
              Promise.reject(error);
              return error;
            }
          }
        }
      }

      return Promise.reject(error);
    });
  },

  install(Vue, store, router) {
    this.app = Vue.prototype;
    this.setupInstance(store, router);

    const repositories = {
      auth: apiAuth(this.apiInstance),
      profile: apiProfile(this.apiInstance),
      avis: apiAvis(this.apiInstance),
      comments: apiComments(this.apiInstance),
      adminDashboard: apiAdminDashboard(this.apiInstance),
      adminAvis: apiAdminAvis(this.apiInstance),
      adminComments: apiAdminComments(this.apiInstance),
      adminMessages: apiAdminMessages(this.apiInstance),
      adminRatings: apiAdminRatings(this.apiInstance),
      adminUsers: apiAdminUsers(this.apiInstance)
    };

    Vue.prototype.$api = repositories;
    Vue.prototype.$apiInstance = this.apiInstance;
    store.$api = repositories;
    store.resetJWT = this.resetJWT;
    store.removeJWT = this.removeJWT;
  },
};
