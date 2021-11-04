export default [
  {
    path: '',
    name: 'ratings',
    component: () => import('../../layouts/ratings'),
    children: [
      {
        name: 'ratings.profile',
        path: 'profile',
        component: () => import('../../views/Profile'),
        meta: {title: 'Profile'}
      },
      {
        name: 'ratings.dashboard',
        path: 'dashboard',
        component: () => import('../../views/Dashboard'),
        meta: {title: 'Dashboard'}
      },
      {
        name: 'ratings.avis.list',
        path: 'avis',
        component: () => import('../../views/avis/List'),
        meta: {title: 'Avis'}
      },
      {
        name: 'ratings.avis.view',
        path: 'avis/:id',
        props: true,
        component: () => import('../../views/avis/View'),
        meta: {title: 'Avi View'}
      },
      {
        name: 'ratings.parties.list',
        path: 'parties',
        component: () => import('../../views/parties/List'),
        meta: {title: 'Parties'}
      },
      {
        name: 'ratings.creeps.list',
        path: 'creeps',
        component: () => import('../../views/creeps/List'),
        meta: {title: 'Creeprs'}
      },
      {
        name: 'ratings.faq',
        path: 'faq',
        component: () => import('../../views/Faq'),
        meta: {title: 'FAQ'}
      },
      {
        name: 'ratings.contact',
        path: 'contact',
        component: () => import('../../views/Contact')
      },
    ]
  },
  {
    path: 'admin',
    name: 'admin',
    redirect: {name: 'admin.dashboard'},
    component: () => import('../../layouts/admin'),
    children: [
      {
        name: 'admin.dashboard',
        path: 'dashboard',
        component: () => import('../../views/admin/Dashboard'),
        meta: {title: 'Admin'}
      },
      {
        name: 'admin.users',
        path: 'users',
        component: () => import('../../views/admin/Users'),
        meta: {title: 'Users'}
      },
      {
        name: 'admin.avis',
        path: 'avis',
        component: () => import('../../views/admin/Avis'),
        meta: {title: 'Avis'}
      },
      {
        name: 'admin.comments',
        path: 'avis/comments',
        component: () => import('../../views/admin/AvisComments'),
        meta: {title: 'Avis Comments'}
      },
      {
        name: 'admin.ratings',
        path: 'avis/ratings',
        component: () => import('../../views/admin/AvisRatings'),
        meta: {title: 'Avis Ratings'}
      },
      {
        name: 'admin.messages',
        path: 'messages',
        component: () => import('../../views/admin/Messages'),
        meta: {title: 'Messages'}
      }
    ]
  },
];
