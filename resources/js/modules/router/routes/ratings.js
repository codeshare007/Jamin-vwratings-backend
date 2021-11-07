export default [
  {
    name: 'ratings.dashboard',
    path: '',
    component: () => import('../../../views/Dashboard'),
    meta: {title: 'Dashboard'}
  },
  {
    name: 'ratings.profile',
    path: 'profile',
    component: () => import('../../../views/Profile'),
    meta: {title: 'Profile'}
  },
  {
    name: 'ratings.avis.list',
    path: 'avis',
    component: () => import('../../../views/avis/List'),
    meta: {title: 'Avis'}
  },
  {
    name: 'ratings.avis.view',
    path: 'avis/:id',
    props: true,
    component: () => import('../../../views/avis/View'),
    meta: {title: 'Avi View'}
  },
  {
    name: 'ratings.parties.list',
    path: 'parties',
    component: () => import('../../../views/parties/List'),
    meta: {title: 'Parties'}
  },
  {
    name: 'ratings.creeps.list',
    path: 'creeps',
    component: () => import('../../../views/creeps/List'),
    meta: {title: 'Creeprs'}
  },
  {
    name: 'ratings.faq',
    path: 'faq',
    component: () => import('../../../views/Faq'),
    meta: {title: 'FAQ'}
  },
  {
    name: 'ratings.contacts',
    path: 'contacts',
    component: () => import('../../../views/Contact'),
    meta: {title: 'Contacts'}
  },
  {
    name: 'ratings.buttons',
    path: 'buttons',
    component: () => import('../../../views/buttons/buttons'),
    meta: {title: 'Buttons'}
  },
]
