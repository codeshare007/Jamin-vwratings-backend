<template>
  <div class="aviView">
    <div class="aviView__buttons">
      <b-button :to="{ name: 'ratings.avis.list' }">Back</b-button>
    </div>

    <div class="aviView__info">
      <p>You're peeking in.....</p>
      <p><span class="aviView__name">{{ avi.name }}</span>'s window</p>
      <div class="d-flex w-100 justify-content-center justify-items-center">
        <star-rating
          :max-rating="12"
          :increment="0.5"
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
          :increment="0.5"
          @rating-selected="setRating"
          :rating="avi.user_rating"
        />
      </div>
    </div>

    <div class="aviView__comment">
      <div v-if="loggedIn">
        <p>Post a comment about {{ avi.name }}</p>
        <b-form class="d-flex flex-column" ref="commentForm">
          <b-row class="justify-content-between align-items-center">
            <b-col>

            </b-col>
            <b-col class="d-flex justify-content-center">
              <b-form-group class="m-0" label="Choose your opinion">
                <b-form-radio-group
                  :class="{ 'form-group__error': !errorRefreshed && $v.opinion.$error }"
                  v-model="$v.opinion.$model"
                  :state="validateState('opinion')"
                  :options="options"
                  buttons
                />
              </b-form-group>
            </b-col>
            <b-col class="d-flex justify-content-end">
              <div class="mt-2 pt-2 ml-3" v-if="this.$v.opinion.$model !== null">
                <b-button class="aviView__uploadButton" @click="openUploadDialog">
                  <b-icon-paperclip/>
                </b-button>
                <input type="file" ref="file" @change="onFileChange" class="d-none" multiple="multiple"/>
              </div>
            </b-col>
          </b-row>

          <div class="text-center text-danger" style="height: 30px">
            <p class="m-0" v-if="$v.$error && !errorRefreshed">Not all fields submitted, opinion, images or message required</p>
          </div>

          <b-form-textarea
            v-if="$v.opinion.$model !== null"
            v-model="$v.comment.$model"
            :state="validateState('comment')"
            ref="commentArea"
            placeholder="Choose positive or negative before submitting"
          />
          <div class="mt-3 d-flex justify-content-between">
            <div>
              <div class="d-flex mt-3">
                <viewer :images="previews">
                  <img alt class="imagePreview" v-for="src in previews" :key="src" :src="src">
                </viewer>
              </div>
              <span class="m-2 text-danger d-block" v-if="previews.length">Are you sure?</span>
            </div>
            <div v-if="this.$v.opinion.$model !== null">
              <b-button @click="send">Send</b-button>
            </div>
          </div>
        </b-form>
      </div>
      <div v-else>
        <h2 class="text-danger text-center">Log in to rate and comment</h2>
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
      index: 0,
      previews: [],
      errorRefreshed: false,
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
      sort: 'id',
      sortDir: 'desc',
      pics: false,
      loading: true,
      opinion: null,
      comment: '',
      files: []
    }
  },

  orderByName: false,

  components: {
    CommentItem,
    StarRating
  },

  validations: {
    opinion: {
      required
    },
    comment: {
      minLength: minLength(1),
      required(v) {
        return this.files || required(v)
      }
    },
    files: {
      required(v) {
        return this.comment || required(v)
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

    validateState(name) {
      const {$dirty, $error} = this.$v[name];
      return $dirty ? !$error : null;
    },

    onFileChange(e) {
      this.files = e.target.files;
      for (let i in this.files) {
        const file = this.files[i];
        if (!file) continue;
        if (typeof file.type === 'undefined') continue;
        if (!file.type.match('image.*')) continue;
        const reader = new FileReader()
        const that = this;

        this.previews = [];
        reader.addEventListener('load', (e) => {
          that.previews.push(reader.result);
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

    send(e) {
      this.$v.$touch();
      if (this.$v.$anyError) {
        this.errorRefreshed = false;
        return;
      }

      let formData = new FormData();
      formData.append('comment', this.$v.comment.$model);
      formData.append('opinion', this.$v.opinion.$model);

      if (this.$refs.file.files.length) {
        for (let i = 0; i < this.$refs.file.files.length; i++) {
          let file = this.$refs.file.files[i];
          formData.append(`attachments[${i}]`, file);
        }
      }

      this.$api.avis.comment(this.id, formData).then(() => {
        this.fetchAvi();
        this.$v.comment.$model = '';
        this.$v.opinion.$model = null;
        this.$v.files.$model = [];
        this.errorRefreshed = true;
        this.$refs['commentForm'].reset();
        this.previews = [];

        this.$bvToast.toast('Success', {
          autoHideDelay: 5000,
          title: 'Comment successfully sent',
          variant: 'success',
          solid: true,
          appendToast: true,
          toaster: 'b-toaster-bottom-right'
        })
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
  padding-bottom: 70px;

  &__buttons {
    padding-bottom: 30px;
  }

  @media screen and (min-width: 1024px) {
    &__buttons {
      padding: 20px;
    }
  }

  .vue-star-rating-star {
    width: 20px;

    @media screen and (min-width: 1024px) {
      width: 30px;
    }
  }

  @media screen and (max-width: 1024px) {
    margin: 20px;
    padding: 20px;
  }

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

  .form-group__error {
    border: 2px solid red;
    border-radius: 5px;
  }

  .aviView__name {
    font-size: 30px;
    color: #23e116;
  }

  &__info {

    @media screen and (min-width: 1024px) {
      padding: 0 40px;
    }

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

    .imagePreview {
      cursor: pointer;
      width: 100px;
      height: 100px;
      object-fit: cover;
      margin-right: 20px;
      border-radius: 10px;
    }

    @media screen and (min-width: 1024px) {
      padding: 0 170px;
    }

    p {
      text-align: center;
      font-size: 20px;
      font-family: 'Futura PT', sans-serif;
    }

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

    @media screen and (min-width: 1024px) {
      padding: 0 150px;
    }

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
