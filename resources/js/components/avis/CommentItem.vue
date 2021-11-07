<template>
  <div class="commentItem mt-2">
    <b-badge v-if="comment.opinion === 1" class="ml-2" variant="success">{{ comment.opinion | opinion }}</b-badge>
    <b-badge v-if="comment.opinion === 0" class="ml-2" variant="danger">{{ comment.opinion | opinion }}</b-badge>
    <div class="commentItem__attachments">
      <div v-for="(attachment, key) in comment.attachments" :key="key">
        <b-img class="attachmentItem m-2" :src="attachment.path"/>
      </div>
    </div>
    <div class="commentItem__content">
      <p>{{ comment.content }}</p>
    </div>
  </div>
</template>
<script>
import moment from "moment";

export default {
  props: {
    comment: {
      attachments: Array
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


  data() {
    return {
      options: [
        {text: 'Positive', value: 1},
        {text: 'Negative', value: 0},
      ],
    }
  }
}
</script>
<style lang="scss">
.commentItem {
  font-size: 20px;
  background: transparent;
  margin: 20px;

  &__attachments {
    display: flex;
    justify-content: flex-start;
  }

  &__content {
    padding: 10px;
    min-height: 100px;
    border-bottom: 1px solid #ffffff61;
    position: relative;
  }

  .attachmentItem {
    width: 100px;
    height: 100px;
  }

  p {
    margin-bottom: 0;
  }
}
</style>
