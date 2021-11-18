<template>
  <div class="commentForm">
    <p>Choose your opinion about {{ name }} before you can post</p>
    <b-form class="d-flex flex-column" ref="commentForm">
      <b-row class="justify-content-between align-items-center">
        <b-col />
        <b-col class="d-flex justify-content-center">
          <b-form-group class="m-0">
            <b-form-radio-group
              :class="{ 'form-group__error': !errorRefreshed && $v.form.opinion.$error, 'form-group__selected': $v.form.opinion.$model !== null }"
              v-model="$v.form.opinion.$model"
              :state="validateState('opinion')"
              :options="[{text: 'Positive', value: 1}, {text: 'Negative', value: 2}]"
              buttons
            />
          </b-form-group>
        </b-col>
        <b-col class="d-flex justify-content-end">
          <div class="mt-2 pt-2 ml-3" v-if="$v.form.opinion.$model !== null">
            <b-button class="entityView__uploadButton" @click="openUploadDialog">
              <b-icon-paperclip />
            </b-button>
            <input type="file" ref="file" @change="onFileChange" class="d-none" multiple="multiple"/>
          </div>
        </b-col>
      </b-row>

      <div class="text-center text-danger" style="height: 30px">
        <p class="m-0" v-if="$v.form.$error && !errorRefreshed">You need to make a post or upload an image or both</p>
      </div>

      <b-form-textarea
        v-if="$v.form.opinion.$model !== null"
        v-model="$v.form.comment.$model"
        :state="validateState('comment')"
        ref="commentArea"
        placeholder="Type here... but we delete pathetic posts"
      />

      <div class="mt-3 d-flex justify-content-between">
          <span class="text-danger d-block" v-if="previews.length">
            If this isn't the pic you wanted just hit upload again.
          </span>
        <div class="d-flex mt-3">
          <viewer :images="previews">
            <img alt class="imagePreview" v-for="src in previews" :key="src" :src="src">
          </viewer>
        </div>
      </div>

      <div class="d-flex justify-content-end w-100" v-if="$v.form.opinion.$model !== null">
        <b-button @click="send">Send</b-button>
      </div>
    </b-form>
  </div>
</template>
<script>
const { required, minLength } = require('vuelidate/lib/validators');

export default {
  props: {
    id: {},
    name: String,
    comments: Array,
    method: String
  },

  data() {
    return {
      form: {
        opinion: null,
        comment: '',
        files: []
      },
      errorRefreshed: false,
      previews: [],
    }
  },

  validations: {
    form: {
      opinion: {
        required
      },
      comment: {
        minLength: minLength(1),
        required(v) {
          return this.form.files.length || required(v)
        }
      },
      files: {
        required(v) {
          return this.form.comment || required(v)
        }
      }
    }
  },


  methods: {
    openUploadDialog() {
      this.$refs['file'].click();
    },

    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    onFileChange(e) {
      this.form.files = e.target.files;
      for (let i in this.form.files) {
        const file = this.form.files[i];
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

    send() {

      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.errorRefreshed = false;
        return;
      }

      let formData = new FormData();
      formData.append('comment', this.$v.form.comment.$model);
      formData.append('opinion', this.$v.form.opinion.$model);

      if (this.$refs.file.files.length) {
        for (let i = 0; i < this.$refs.file.files.length; i++) {
          let file = this.$refs.file.files[i];
          formData.append(`attachments[${i}]`, file);
        }
      }

      this.$api[this.method].comment(this.id, formData).then(() => {
        this.$emit('send');
        this.$v.form.comment.$model = '';
        this.$v.form.opinion.$model = null;
        this.$v.form.files.$model = [];
        this.errorRefreshed = true;
        this.$refs['commentForm'].reset();
        this.previews = [];

        this.$bvToast.toast('Success', {
          autoHideDelay: 500,
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
.commentForm {

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

  .btn-group-toggle {

    .btn {
      &:first-child {
        background: #218838;
      }

      &:last-child {
        background: #c82333;
      }
    }
  }

  .form-group__selected {
    .btn:not(.active) {
      background: #5a6268;
    }
  }

  .btn-secondary {
    &.active, &:focus, &:active {
      outline: 0;
      box-shadow: none;
    }
  }
}
</style>
