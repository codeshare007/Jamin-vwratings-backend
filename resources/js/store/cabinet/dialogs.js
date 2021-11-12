import UserEditDialog from './dialogs/user';
import AviEditDialog from './dialogs/avi'
import AviCommentEditDialog from './dialogs/avicomment'
import AdsCampaignEditDialog from './dialogs/campaign'
export default {
  namespaced: true,
  modules: {
    user: UserEditDialog,
    avi: AviEditDialog,
    aviComment: AviCommentEditDialog,
    campaign: AdsCampaignEditDialog
  },
}
