<template>
  <transition name="fade">
    <div class="entityView" v-if="screenLoaded">

      <EntityInfo
        placeholder="You're peeking in....."
        :average-rating="item.average_rating"
        :screen="screenBack"
        :name="item.name"
      />

      <EntityRate
        v-if="loggedIn"
        @rated="rate"
        placeholder="Fill the stars below. Change them anytime. Stars above are the total average of all ratings."
        :user_rating="item.user_rating"
      />

      <CommentForm
        v-if="loggedIn"
        :id="id"
        :method="method"
        :name="item.name"
        :comments="item.comments"
      />

      <div v-if="loggedIn === false">
        <h2 class="text-danger text-center">Log in to rate and comment</h2>
      </div>

      <CommentList
        :comments="item.comments"
      />
    </div>
  </transition>
</template>
<script>
import EntityInfo from "./entity/EntityInfo";
import EntityRate from './entity/EntityRate';
import CommentForm from './entity/comments/CommentForm';
import CommentList from './entity/comments/CommentList';
import CommentItem from "./entity/comments/CommentItem";

export default {
  props: {
    id: {},
    method: String
  },
  data() {
    return {
      item: {
        average_rating: null,
        user_rating: null,
        comments: []
      },
      screenLoaded: false,
    }
  },

  components: {
    EntityInfo,
    CommentItem,
    EntityRate,
    CommentForm,
    CommentList
  },

  mounted() {
    this.fetchItem();
  },

  computed: {
    screenBack() {
      return `ratings.${this.method}.list`
    },
    loggedIn() {
      return this.$store.getters['auth/loggedIn']
    },
  },

  methods: {
    rate(value) {
      this.$api[this.method].rating(this.id, {rating: value})
        .then(() => this.fetchItem());
    },
    fetchItem() {
      this.$api[this.method].get(this.id).then(response => {
        this.item = response.data;
        this.screenLoaded = true;
      })
    },
  }
}
</script>
<style lang="scss">

.entityView {
  background: black;
  padding: 40px 30px;
}

.entityView {

  &__buttons {
    padding-bottom: 30px;
  }

  @media screen and (min-width: 1024px) {
    &__buttons {
      padding: 20px;
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

}
</style>
