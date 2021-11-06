<template>
  <div class="admin-layout">
    <b-container class="p-0">
      <HeaderBar />
      <b-row class="p-0 m-0">
        <b-col class="p-0" cols="2">
          <SidebarNav />
        </b-col>
        <b-col cols="10">
          <router-view />
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>
<script>
import SidebarNav from "../components/admin/SidebarNav";
import HeaderBar from "../components/layout/HeaderBar";

export default {
  components: {
    HeaderBar,
    SidebarNav
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
