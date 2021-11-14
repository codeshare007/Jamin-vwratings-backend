<template>
  <div class="profile-page">
    <b-row class="mt-5">
      <b-col cols="6">
        <b-card header="Profile" bg-variant="dark" text-variant="white">
          <h2>{{ profile.username }}</h2>
          <p>{{ profile.role | role }}</p>

          <b-button class="mt-3">Logout</b-button>
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
    fetchComments() {
      this.$api.profile.comments().then(response => {
        this.comments = response.data;
      })
    }
  }
}
</script>
