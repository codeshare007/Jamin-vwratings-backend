export default [
  {
    name: 'auth.signin',
    path: 'signin',
    component: () => import('../../views/auth/SignIn'),
    meta: {
      title: 'Authorization'
    }
  },
  {
    name: 'auth.signup',
    path: 'signup',
    component: () => import('../../views/auth/SignUp'),
    meta: {
      title: 'Registration'
    }
  },
];
