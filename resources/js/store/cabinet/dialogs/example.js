const initial = {
    id: null,
    status: 'hidden',
    resolve: null,
    reject: null,
};

export default {
    namespaced: true,
    state: {
        ...initial
    },
    actions: {
        clear({commit}) {
            commit('clear');
        },
        create({commit}) {
            return new Promise((resolve, reject) => {
                commit('set', {
                    status: 'create',
                    id: null,
                    resolve,
                    reject
                });
            });
        },
        edit({commit}, id) {
            return new Promise((resolve, reject) => {
                commit('set', {
                    status: 'edit',
                    id: id,
                    resolve,
                    reject
                });
            });
        },
    },
    mutations: {
        clear(state) {
            state.status = initial.status;
            state.id = initial.id;
            state.resolve = initial.resolve;
            state.reject = initial.reject;
        },
        set(state, {status, id, resolve, reject}) {
            state.status = status;
            state.id = id;
            state.resolve = resolve;
            state.reject = reject;
        },
    },
};
