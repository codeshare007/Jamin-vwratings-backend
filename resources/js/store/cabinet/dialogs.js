import UserEditDialog from './dialogs/user';
import AviEditDialog from './dialogs/avi'
import AviCommentEditDialog from './dialogs/avicomment'
export default {
  namespaced: true,
  modules: {
    user: UserEditDialog,
    avi: AviEditDialog,
    aviComment: AviCommentEditDialog
  },
}
