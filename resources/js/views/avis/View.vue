<template>
  <div class="avi-view">
    <div class="pl-4 pt-4">
      <b-button :to="{name: 'ratings.avis.list'}">Back</b-button>
    </div>
    <b-card class="mb-3" header="Avi's Profile" v-if="!loading">
      <div class="text-center">
        <p>You're peeking in.....</p>
        <p><span class="avi-name">{{ avi.name }}</span>'s window</p>
        <div class="d-flex w-100 justify-content-center justify-items-center">
          <star-rating :max-rating="12" :star-size="30" :read-only="true" :rating="avi.average_rating"/>
        </div>
      </div>
      <div v-if="loggedIn">
        <hr class="bg-white">
        <p class="text-center">
          Fill the stars below. Change them anytime. Stars above are the total average of all ratings.
        </p>
        <div class="d-flex w-100 justify-content-center justify-items-center">
          <star-rating :max-rating="12" :star-size="30" @rating-selected="setRating" :rating="avi.user_rating"/>
        </div>
      </div>
    </b-card>
    <b-card class="mb-3" header="New comment" v-if="loggedIn">
      <b-form class="d-flex align-items-start flex-column">
        <p>Post a comment about {{ avi.name }}</p>
        <div class="d-flex justify-content-between align-items-center">
          <b-form-group label="Choose your opinion" v-slot="{ ariaDescribedby }">
            <b-form-radio-group
              v-model="form.opinion"
              :options="options"
              :aria-describedby="ariaDescribedby"
              name="radios-btn-default"
              buttons
            ></b-form-radio-group>
          </b-form-group>
          <div class="mt-2 pt-2 ml-3">
            <b-file type="file" ref="file" multiple="multiple"></b-file>
          </div>
        </div>
        <b-form-textarea v-model="form.comment" placeholder="Choose positive or negative before submitting"/>
        <div class="mt-3">
          <b-button @click="comment">Send</b-button>
        </div>
      </b-form>
    </b-card>

    <div class="text-center" v-else>
      <h2 class="text-danger">Log in to rate and comment</h2>
    </div>

    <b-card header="Comments" class="pb-3" v-if="!loading">
      <div class="commentSort">
        <b-button class="mr-2" @click="sortComments('id', 'desc')">Newest to First</b-button>
        <b-button class="mr-2" @click="sortComments('id', 'asc')">In order - 1st to last</b-button>
        <b-button class="mr-2" @click="pics = !pics">Pics</b-button>
      </div>

      <hr class="bg-white">

      <div class="commentBlock">
        <div class="commentItem mt-2" v-for="(item, key) in this.sortedComments" :key="key">
          <b-badge v-if="item.opinion === 1" class="ml-2 p-2" variant="success">{{ item.opinion | opinion }}</b-badge>
          <b-badge v-if="item.opinion === 0" class="ml-2 p-2" variant="danger">{{ item.opinion | opinion }}</b-badge>
          <div class="commentItem__attachments">
            <div v-for="(attachment, key) in item.attachments" :key="key">
              <b-img style="width: 100px; height: 100px" class="m-2" :src="attachment.path"/>
            </div>
          </div>
          <div class="commentItem__content">
            <p>{{ item.content }}</p>
            <span class="commentItem__date">{{ item.created_at | date }}</span>
          </div>
        </div>
      </div>
    </b-card>
  </div>
</template>
<script>
const { required, minLength } = require('vuelidate/lib/validators')

import StarRating from 'vue-star-rating'
import moment from 'moment';

export default {

  props: ['id'],

  data() {
    return {
      avi: {
        comments: []
      },
      options: [
        {text: 'Positive', value: 1},
        {text: 'Negative', value: 0},
      ],
      sort: 'id',
      sortDir: 'desc',
      pics: false,
      loading: true,
      form: {
        opinion: null,
        comment: null,
      }
    }
  },

  orderByName: false,

  components: {
    StarRating
  },

  validators: {
    form: {
      opinion: {
        required
      },
      comment: {
        required: required,
        minLength: minLength(1)
      }
    }
  },

  filters: {
    opinion(data) {
      if (typeof data === 'number') {
        return data ? 'positive' : 'negative';
      }
      return '';
    },
    date(data) {
      return moment(data).format('MMMM Do YYYY, h:mm:ss')
    }
  },

  mounted() {
    this.fetchAvi()
  },

  computed: {
    loggedIn() {
      return this.$store.getters['auth/loggedIn']
    },
    sortedComments() {
      if (this.pics) {
        return this.avi.comments.filter(item => {
          if (item['attachments'].length) return item;
        })
      } else {
        return this.avi.comments.sort((a, b) => {
          let modifier = 1;
          if (this.sortDir === 'desc') modifier = -1;
          if (a[this.sort] < b[this.sort]) return -1 * modifier;
          if (a[this.sort] > b[this.sort]) return 1 * modifier;
          return 0;
        });
      }
    }
  },

  methods: {
    sortComments(sort, sortDir) {
      this.pics = false;
      this.sortDir = sortDir
      this.sort = sort;
    },
    fetchAvi() {
      this.$api.avis.get(this.id).then(response => {
        this.avi = response.data;
        this.loading = false;
      })
    },

    setRating(rating) {
      this.$api.avis.rating(this.id, {rating: rating}).then(() => {
        this.fetchAvi();
      })
    },

    comment(e) {
      e.preventDefault();
      let formData = new FormData();
      formData.append('comment', this.form.comment);
      formData.append('opinion', this.form.opinion);

      if (this.$refs.file.files.length) {
        for (let i = 0; i < this.$refs.file.files.length; i++) {
          let file = this.$refs.file.files[i];
          formData.append(`attachments[${i}]`, file);
        }
      }

      this.$api.avis.comment(this.id, formData).then(() => {
        this.form = { comment: null, reaction: null }
        this.fetchAvi();
      });
    }
  }
}
</script>
<style lang="scss">
.avi-view {
  background: #24252d;
  border-radius: 5px;
  min-height: 1000px;

  p {
    margin-bottom: 0;
  }

  .avi-name {
    font-size: 30px;
    color: #23e116;
  }

  .vue-star-rating {
    align-items: flex-end;
  }

  .btn {
    background: green;
    border: 0;
    color: white;

    &:focus {
      outline: 0;
      box-shadow: unset;
      border-color: unset;
    }
  }

  .commentSort {
    display: flex;
    justify-content: center;
  }

  .card {

    textarea {
      color: white;
      background: #173618;
      border: 1px solid #ffffff61;

      &::placeholder {
        color: white;
      }
    }

    .commentItem {
      font-size: 20px;


      &__attachments {
        display: flex;
        justify-content: flex-start;
      }

      &__date {
        right: 0;
        bottom: 8px;
        font-size: 18px;
        display: block;
        position: absolute;
      }

      &__content {
        padding: 9px 10px 0;
        min-height: 100px;
        border-bottom: 1px solid #ffffff61;
        position: relative;
      }

      p {
        margin-bottom: 0;
      }
    }

    background: transparent;
    margin: 20px;

    .card-header,
    .card-body {
      border: 1px solid #ffffff61;
      color: white;
    }

    .card-body p {
      font-size: 20px;
    }
  }
}
</style>
