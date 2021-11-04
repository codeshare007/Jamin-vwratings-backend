<template>
  <div class="admin-layout">
    <b-row class="p-0 m-0 justify-content-center">
      <b-col cols="2">
        <SidebarNav />
      </b-col>
      <b-col cols="8">
        <router-view />
      </b-col>
    </b-row>
  </div>
</template>
<script>
import SidebarNav from "../components/admin/SidebarNav";
export default {
  components: {SidebarNav}
}
</script>
