export default [
  {
    name: 'auth.signin',
    path: 'signin',
    component: () => import('../../../views/auth/SignIn'),
    meta: {title: 'Authorization'}
  },
  {
    name: 'auth.signup',
    path: 'signup',
    component: () => import('../../../views/auth/SignUp'),
    meta: {title: 'Registration'}
  },
  {
    name: 'auth.forgot',
    path: 'forgot-password',
    component: () => import('../../../views/auth/ForgotPassword'),
    meta: {title: 'Forgot Password'}
  },
  {
    name: 'auth.reset',
    path: 'reset-password/:token',
    component: () => import('../../../views/auth/PasswordReset'),
    meta: {title: 'Password Reset'},
    props: true
  }
];
