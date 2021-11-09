export default [
  {
    name: 'ratings.home',
    path: '',
    component: () => import('../../../views/Home'),
    meta: {title: 'Home'}
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
    name: 'ratings.specialone',
    path: 'specialone',
    component: () => import('../../../views/buttons/specialone'),
    meta: {title: 'Special Page'}
  },
  {
    name: 'ratings.specialtwo',
    path: 'specialtwo',
    component: () => import('../../../views/buttons/specialtwo'),
    meta: {title: 'Special Page'}
  },
  {
    name: 'ratings.peepscreeps',
    path: 'peepscreeps',
    component: () => import('../../../views/buttons/peepscreeps'),
    meta: {title: 'Peeps & Creeps'}
  },
  {
    name: 'ratings.claim',
    path: 'claim',
    component: () => import('../../../views/buttons/claim'),
    meta: {title: 'Claim Name'}
  },
  {
    name: 'ratings.info',
    path: 'info',
    component: () => import('../../../views/buttons/info'),
    meta: {title: 'Info'}
  },
  {
    name: 'ratings.contact',
    path: 'contact',
    component: () => import('../../../views/buttons/Contact'),
    meta: {title: 'Contact Us'}
  },
  {
    name: 'ratings.buttons',
    path: 'buttons',
    component: () => import('../../../views/buttons/buttons'),
    meta: {title: 'Buttons'}
  },
  {
    name: 'ratings.notfound',
    path: '404',
    component: () => import('../../../views/NotFound'),
    meta: {title: 'Not Found'}
  }
]
