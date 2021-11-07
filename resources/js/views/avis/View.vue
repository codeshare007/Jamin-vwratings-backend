<template>
  <div class="aviView">
    <div class="pl-2 pt-2">
      <b-button :to="{ name: 'ratings.avis.list' }">Back</b-button>
    </div>

    <div class="aviView__info">
      <p>You're peeking in.....</p>
      <p><span class="aviView__name">{{ avi.name }}</span>'s window</p>
      <div class="d-flex w-100 justify-content-center justify-items-center">
        <star-rating
          :max-rating="12"
          :star-size="30"
          :read-only="true"
          :rating="avi.average_rating"
        />
      </div>
      <hr>
    </div>

    <div class="aviView__rate" v-if="loggedIn">
      <p>Fill the stars below. Change them anytime. Stars above
        are the total average of all ratings.</p>
      <div class="d-flex w-100 justify-content-center justify-items-center">
        <star-rating
          :max-rating="12"
          :star-size="30"
          @rating-selected="setRating"
          :rating="avi.user_rating"
        />
      </div>
    </div>

    <div class="aviView__comment">
      <div v-if="loggedIn">
        <p class="text-center">Post a comment about {{ avi.name }}</p>
        <b-form class="d-flex flex-column">
          <div class="d-flex justify-content-between align-items-center">
            <b-form-group label="Choose your opinion">
              <b-form-radio-group
                v-model="form.opinion" :options="options" buttons
              />
            </b-form-group>
            <div class="mt-2 pt-2 ml-3">
              <b-button class="aviView__uploadButton" @click="openUploadDialog"><b-icon-paperclip /></b-button>
              <input type="file" ref="file" @change="onFileChange" class="d-none" multiple="multiple" />
            </div>
          </div>
          <b-form-textarea
            v-model="form.comment"
            ref="commentArea"
            placeholder="Choose positive or negative before submitting"
          />
          <div class="mt-3">
            <b-button @click="comment">Send</b-button>
          </div>

          <div class="d-flex mt-3">
            <div class="mr-2" v-for="(item, key) in files">
              <img :src="item.previewUrl" alt="" style="width: 100px; height: 100px; object-fit: cover" />
            </div>
          </div>
        </b-form>
      </div>
      <div v-else>
        <h2 class="text-danger">Log in to rate and comment</h2>
      </div>
    </div>

    <div class="comments" v-if="!loading">
      <div class="comments__sortBlock">
        <button
          v-for="(item, key) in filters" :key="key"
          @click="changeFilter(item.value)"
          :class="{'active': item.value === currentFilter}"
        >{{ item.name }}
        </button>
      </div>
      <div class="comments__list">
        <CommentItem
          v-for="(comment, key) in sortedComments"
          :key="key"
          :comment="comment"
        />
        <div v-if="!Object.keys(sortedComments).length">No comments</div>
      </div>
    </div>

  </div>
</template>
<script>
const {required, minLength} = require('vuelidate/lib/validators')
import StarRating from 'vue-star-rating'

import CommentItem from "../../components/avis/CommentItem";

export default {
  props: ['id'],
  data() {
    return {
      avi: {
        average_rating: null,
        user_rating: null,
        comments: []
      },
      previewUrl: null,
      currentFilter: 1,
      filters: [
        {name: 'Newest', value: 1},
        {name: 'Oldest', value: 2},
        {name: 'Pics', value: 3}
      ],
      options: [
        {text: 'Positive', value: 1},
        {text: 'Negative', value: 0},
      ],
      files: [],
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
    CommentItem,
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
    openUploadDialog(e) {
      e.preventDefault();
      this.$refs['file'].click();
    },

    onFileChange(e) {
      this.files = e.target.files;
      for (let i in this.files) {
        const file = this.files[i];
        if (!file) continue;
        if (typeof file.type === 'undefined') continue;
        if (!file.type.match('image.*')) continue;
        const reader = new FileReader()
        const that = this.files[i];

        reader.addEventListener('load', (e) => {
          that.previewUrl = reader.result;
        }, false);

        if (file) {
          reader.readAsDataURL(file)
        }
      }
    },

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

    changeFilter(filter) {
      this.currentFilter = filter;

      if (filter === 1) {
        this.sortComments('id', 'desc')
      }

      if (filter === 2) {
        this.sortComments('id', 'asc');
      }

      if (filter === 3) {
        this.pics = !this.pics;
      }
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
        this.form = {comment: null, reaction: null}
        this.fetchAvi();
      });
    }
  }
}
</script>
<style lang="scss">
.aviView {
  background: #24252d;
  border-radius: 5px;
  color: white;

  .btn {
    background: #00a71c;
    border: 0;

    &.active, &:focus, &:active {
      outline: 0;
      box-shadow: none;
    }
  }

  hr {
    background: #007209;
  }

  .aviView__name {
    font-size: 30px;
    color: #23e116;
  }

  &__info {
    padding: 0 40px;
    text-align: center;
    font-family: 'Futura PT', sans-serif;
    font-size: 24px;

    p {
      margin-bottom: 0;
    }
  }

  &__rate {
    margin-bottom: 30px;

    p {
      text-align: center;
      font-size: 22px;
      font-family: 'Futura PT', sans-serif;
    }
  }

  &__comment {
    padding: 0 40px;

    textarea {
      background: #173618;
      border: 1px solid #ffffff61;
      color: #fff;

      &:focus {
        background: #173618;
        color: white;
        box-shadow: unset;
        border: 1px solid #ffffff61;
      }

      &::placeholder {
        color: white;
      }
    }

    .btn-secondary {
      &.active, &:focus, &:active {
        outline: 0;
        box-shadow: none;
      }
    }
  }

  .comments {
    padding: 0 150px;

    &__sortBlock {
      display: flex;
      justify-content: center;
      position: relative;
      margin: 40px 20px 40px;
      overflow: hidden;

      &:after {
        content: '';
        position: absolute;
        height: 2px;
        bottom: 0;
        width: 100%;
        border-bottom: 2px solid #007209;
      }

      button {
        border: 0;
        background: unset;
        color: white;
        font-family: 'Futura PT', sans-serif;
        font-size: 22px;
        position: relative;
        margin: 0;
        z-index: 2;
        border-bottom: 2px solid transparent;

        &:focus {
          outline: 0;
        }

        &.active {
          border-bottom: 2px solid white;
        }
      }
    }
  }
}
</style>
