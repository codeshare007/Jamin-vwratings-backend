export function Authenticated({next, store, to}) {
    if (!store.getters['auth/loggedIn']) {
        return next({name: 'auth.signin'});
    }

    return next({next, store, to});
}

export function Guest({next, store, to}) {
    if (store.getters['auth/loggedIn']) {
        return next({name: 'ratings'})
    }

    return next({next, store, to});
}

export function Admin({next, store, to}) {
  if (!store.getters['auth/loggedIn']) {
    return next({name: 'auth.signin'});
  }

  return next({next, store, to});
}

export function Permission({next, store, to}) {
    function checkPermission(slugs) {
        let checked = false;

        slugs.forEach(slug => {
            if (!checked) {
                let permission = store.getters['auth/profilePermissions'].find(permission => permission.slug === slug);
                if (permission) {
                    checked = true;
                }
            }
        });
        return checked;
    }

    let slug = to.meta.permission;

    if (!Array.isArray(slug))
        slug = [slug]

    if (store && store.state.auth.profile && store.state.auth.permissions) {
        if (!checkPermission(slug)) {
            return next({
                name: 'ratings.dashboard'
            });
        }
    }

    if (store && !store.state.auth.profile) {
        return next({
            name: 'ratings'
        })
    }

    return next({next, store, to});
}

export function middlewarePipeline(router, store) {

    function nextFactory(context, middleware, index) {
        const subsequentMiddleware = middleware[index]
        if (!subsequentMiddleware) return context.next

        return (...parameters) => {
            context.next(...parameters)
            const nextMiddleware = nextFactory(context, middleware, index + 1)
            subsequentMiddleware({...context, next: nextMiddleware})
        }
    }

    router.beforeEach(async (to, from, next) => {
        if (store.getters['auth/loggedIn'] && !store.getters['auth/profile']) {
            // await store.dispatch('auth/GET_PERMISSIONS');
            await store.dispatch('auth/GET_PROFILE');
        }

        let routeMiddleware = to.meta.middleware
        if (!routeMiddleware && to.matched.length > 0 && to.matched[0].meta) {
            routeMiddleware = to.matched[0].meta.middleware;
        }

        if (routeMiddleware) {
            const middleware = Array.isArray(routeMiddleware)
                ? routeMiddleware
                : [routeMiddleware]
            const context = {
                from,
                next,
                router,
                to,
            };
            const nextMiddleware = nextFactory(context, middleware, 1)
            return middleware[0]({...context, store, next: nextMiddleware, to: to})
        }

        return next()
    })
}
