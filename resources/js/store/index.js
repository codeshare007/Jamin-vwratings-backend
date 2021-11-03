import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import avis from './avis'
import profile from "./profile";

import dialogs from './cabinet/dialogs'

Vue.use(Vuex)

export default new Vuex.Store({
    namespaced: true,
    modules: {
        auth,
        profile,
        avis,
        dialogs
    }
})
