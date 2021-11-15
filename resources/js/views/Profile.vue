<template>

    <div class="page-container text-center">      
      <div class="container-fluid tm-content-container">
            <div class="row">
              <div class="col-md-12 col-lg-8">
                
                  <div class="tm-bg-dark col-md-12 section1"></div>
                  <div class="tm-bg-dark col-md-12 section2"></div>
                
              </div>
              <div class="col-md-12  col-lg-4">
                  <div class="tm-bg-dark col-md-12 section3"></div>                
              </div>
            </div>
      </div>





    <b-row class="mt-5">
      <b-col cols="6">
        <b-card header="Profile" bg-variant="dark" text-variant="white">
          <h2>{{ profile.username }}</h2>
        </b-card>
      </b-col>
      <b-col cols="6">

      </b-col>
    </b-row>

    <b-card class="mt-5" header="Last comments" bg-variant="dark" text-variant="white">
      <b-card-body style="min-height: 500px; padding: 0">
        <CommentItem v-for="(comment, key) in comments" :key="key" :comment="comment"/>
      </b-card-body>
    </b-card>

    <br>
    <br>
  </div>
</template>
<script>
import CommentItem from "../components/entities/entity/comments/CommentItem";

export default {
  components: {CommentItem},
  data() {
    return {
      comments: []
    }
  },

  filters: {
    role(data) {
      const roles = {1: 'Admin', 2: 'User'};
      return roles[data]
    }
  },

  computed: {
    profile() {
      return this.$store.getters['auth/profile']
    }
  },

  mounted() {
    this.fetchComments()
  },

  methods: {
    emitLogout() {
      this.logout().then(() => {
        this.$router.push({name: 'auth.signin'})
      })
    },
    fetchComments() {
      this.$api.profile.comments().then(response => {
        this.comments = response.data;
      })
    }
  }
}
</script>
<style lang="scss">
.section1{
  padding: 180px 30px;
  margin-bottom: 30px;
  margin-top: 30px;
}
.section2{
  padding: 101px 30px;
}
.section3{
  padding: 296px 30px;
  margin-bottom: 30px;
  margin-top: 30px;
}
</style>