import useUserStore from '@/store/user';
export const setupGuards = (router) => {
  // 👉 router.beforeEach
  // Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
  router.beforeEach((to, from) => {
    /*
     * If it's a public route, continue navigation. This kind of pages are allowed to visited by login & non-login users. Basically, without any restrictions.
     * Examples of public routes are, 404, under maintenance, etc.
     */

    const userStore = useUserStore();
    const isLoggedIn = userStore.isAuthenticated;

    // ⛔ Redirect authenticated users away from guest-only routes (e.g., login, register)
    if (to.meta.loginRouteGuard && isLoggedIn) {
      // If coming from a route, redirect back to it; else fallback to '/'

      return from.name ? from.fullPath : '/';
    }

    if (to.meta.public) return;

    // If user is not logged in, redirect to login page.

    if (!isLoggedIn && to.meta.requiresAuth) return '/login';

    return;
  });
};
