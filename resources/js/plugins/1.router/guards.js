export const setupGuards = (router) => {
  // 👉 router.beforeEach
  // Docs: https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards
  router.beforeEach((to) => {
    /*
     * If it's a public route, continue navigation. This kind of pages are allowed to visited by login & non-login users. Basically, without any restrictions.
     * Examples of public routes are, 404, under maintenance, etc.
     */
    if (to.meta.public) return;

    const isLoggedIn = !!(
      useCookie('userData').value && useCookie('accessToken').value
    );
    if (!isLoggedIn && to.meta.requiresAuth) return '/login';
  });
};
