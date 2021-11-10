<template>
  <div class="admin-layout">
    <div v-if="isAdmin">
      <UserEditDialog />
      <AviEditDialog />
      <AviCommentEditDialog />
    </div>
    <b-container fluid class="pr-5 pl-5">
      <HeaderBar />
      <b-row class="p-0 m-0">
        <b-col class="p-0" cols="12" lg="2">
          <SidebarNav />
        </b-col>
        <b-col cols="12" lg="10">
          <router-view />
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import SidebarNav from "../components/admin/SidebarNav";
import HeaderBar from "../components/layout/HeaderBar";
import UserEditDialog from "../components/admin/dialogs/UserEditDialog";
import AviEditDialog from '../components/admin/dialogs/AviEditDialog';
import AviCommentEditDialog from '../components/admin/dialogs/AviCommentEditDialog'

export default {
  components: {
    UserEditDialog,
    AviEditDialog,
    AviCommentEditDialog,
    HeaderBar,
    SidebarNav
  },

  computed: {
    isAdmin() {
      return this.$store.getters['auth/isAdmin']
    }
  }
}
</script>
<style lang="scss">
  .admin-layout {
    min-height: 100vh;

    .search-link {
      background-color: #343a40;
      color: white;
      border: 0;

      &:focus {
        outline: 0;
        border-color: unset;
        box-shadow: unset;
      }

      &::placeholder {
        color: white;
      }
    }

    .pagination {
      .page-link {
        background: #343a40;
        color: white;
        border: 0;
      }

      .page-item.active .page-link {
        background-color: #007bff
      }
    }
  }
</style>
