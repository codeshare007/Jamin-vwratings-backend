<template>
  <div class="commentList">
    <div class="commentList__sortBlock">
      <button
        v-for="(item, key) in filters" :key="key"
        @click="changeSort(item.value)"
        :class="{'active': item.value === sort}"
        v-html="item.name"/>
    </div>
    <div class="commentList__items">
      <CommentItem
        v-for="(comment, key) in this.sorted"
        :comment="comment"
        :key="key"
      />
      <div v-if="!Object.keys(this.sorted).length">No comments</div>
    </div>
  </div>
</template>
<script>
import CommentItem from "./CommentItem";

export default {
  props: {
    comments: Array
  },

  components: {
    CommentItem
  },

  data() {
    return {
      sort: 'asc',
      filters: [
        {name: 'Newest', value: 'asc'},
        {name: 'Oldest', value: 'desc'},
        {name: 'Pics', value: 'pics'}
      ]
    }
  },

  computed: {
    sorted() {
      switch (this.sort) {
        case('asc'):
          return this.comments.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
        case('desc'):
          return this.comments.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        case('pics'):
          return this.comments.filter(item => {
            if (Object.keys(item.attachments).length) return item;
          })
      }
    }
  },

  methods: {
    changeSort(value) {
      this.sort = value;
    },
  }
}
</script>
<style lang="scss">
.commentList {

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

  &__items {
    text-align: left;
  }

  .badge {
    display: none;
  }
}
</style>
