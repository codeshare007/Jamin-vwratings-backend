<template>
  <div class="admin-layout">
    <b-row>
      <b-col cols="2">
        <SidebarNav />
      </b-col>
      <b-col cols="10">
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
