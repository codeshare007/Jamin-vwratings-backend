export default [
  {
    name: 'admin.dashboard',
    path: 'dashboard',
    component: () => import('../../../views/admin/Dashboard'),
    meta: {title: 'Admin'}
  },
  {
    name: 'admin.users',
    path: 'users',
    component: () => import('../../../views/admin/Users'),
    meta: {title: 'Users'}
  },
  {
    name: 'admin.avis',
    path: 'avis',
    component: () => import('../../../views/admin/Avis'),
    meta: {title: 'Avis'}
  },
  {
    name: 'admin.comments',
    path: 'avis/comments',
    component: () => import('../../../views/admin/AvisComments'),
    meta: {title: 'Avis Comments'}
  },
  {
    name: 'admin.ratings',
    path: 'avis/ratings',
    component: () => import('../../../views/admin/AvisRatings'),
    meta: {title: 'Avis Ratings'}
  },
  {
    name: 'admin.messages',
    path: 'messages',
    component: () => import('../../../views/admin/Messages'),
    meta: {title: 'Messages'}
  },
  {
    name: 'admin.parties',
    path: 'parties',
    component: () => import('../../../views/admin/Parties'),
    meta: {title: 'Parties'}
  },
  {
    name: 'admin.parties.comments',
    path: 'parties/comments',
    component: () => import('../../../views/admin/PartiesComments'),
    meta: {title: 'PartiesComments'}
  },
  {
    name: 'admin.parties.ratings',
    path: 'parties/ratings',
    component: () => import('../../../views/admin/PartiesRatings'),
    meta: {title: 'Parties Ratings'}
  },
  {
    name: 'admin.ads_campaigns',
    path: 'campaigns',
    component: () => import('../../../views/admin/AdsCampaigns'),
    meta: {title: 'Ads Campagins'}
  }
]
