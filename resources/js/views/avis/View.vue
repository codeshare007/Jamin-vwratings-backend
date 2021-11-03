<template>
  <div class="avi-view">

    <div class="pl-4 pt-4">
      <b-button :to="{name: 'ratings.avis.list'}">Back</b-button>
    </div>

    <b-card class="mb-3" header="Avi's Profile">
      <p>You're peeking in.....</p>
      <p>{{ avi.name }}'s window</p>
      <hr>
      <p>Fill the stars below. Change them anytime. Stars above are the total average of all ratings.</p>
    </b-card>

    <b-card class="mb-3" header="New comment">
      <b-form class="d-flex align-items-end flex-column" v-if="loggedIn">
        <b-form-textarea v-model="form.content" placeholder="Place your comment here"></b-form-textarea>
        <div class="mt-3">
          <b-button @click="send">Send</b-button>
        </div>
      </b-form>
    </b-card>

    <b-card header="Comments">
      <div class="commentItem" v-for="(item, key) in avi.comments" :key="key">
        <b-badge class="ml-2" variant="success">{{ item.opinion | opinion }}</b-badge>
        <div class="commentItem__content">
          <p>{{ item.content ? item.content : '...' }}</p>
        </div>
      </div>
    </b-card>
  </div>
</template>
<script>
import {mapState} from "vuex";

export default {
  props: ['id'],
  data() {
    return {
      avi: {
        comments: []
      },

      form: {
        opinion: 1,
        comment: null,
      }
    }
  },

  filters: {
    opinion(data) {
      if (typeof data === 'number') {
        return data ? 'positive' : 'negative';
      }
      return '';
    }
  },

  mounted() {
    this.fetchAvi()
  },

  methods: {
    ...mapState('auth', {
      loggedIn: state => state.loggedIn,
    }),
    fetchAvi() {
      this.$api.avis.get(this.id).then(response => {
        this.avi = response.data;
      })
    },

    send(e) {
      e.preventDefault();
      const payload = this.form;
      this.$api.comments.send(this.id, payload).then(response => {
        this.form = {comment: null, reaction: null}
        this.fetchAvi();
      });
    }
  }
}
</script>
<style lang="scss">
  .avi-view {
    background: black;
    border-radius: 5px;

    .btn {
      background: green;
      border: 0;
      color: white;
    }

    .card {

      textarea {
        color: white;
        background: #173618;
        border: 1px solid green;

        &::placeholder {
          color: white;
        }
      }

      .commentItem {
        font-size: 20px;


        &__content {
          padding: 40px 20px;
          border-bottom: 1px solid green;
        }

        p {
          margin-bottom: 0;
        }
      }

      background: transparent;
      margin: 20px;

      .card-header,
      .card-body {
        border: 1px solid green;
        color: white;
      }

      .card-body p {
        font-size: 20px;
      }
    }
  }
</style>
