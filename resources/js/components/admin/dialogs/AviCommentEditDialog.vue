<template>
  <b-modal
    :title="aviComment.id ? 'Edit Comment' : 'Create Comment'"
    :visible.sync="visible"
    @submit.prevent.native=""
    @hide="handleClose(null)"
  >
    <b-form>
      <b-form-group label="User Id">
        <b-input type="text" v-model="aviComment.user_id"/>
      </b-form-group>
      <b-form-group label="Avi Id">
        <b-input type="text" v-model="aviComment.avis_id"/>
      </b-form-group>
      <b-form-group label="Content">
        <b-form-textarea rows="7" type="text" v-model="aviComment.content"/>
      </b-form-group>
      <b-form-group label="Opinion">
        <b-form-group label="Button style radios" v-slot="{ ariaDescribedby }">
          <b-form-radio-group
            id="btn-radios-1"
            v-model="aviComment.opinion"
            :options="{1: 'Positive', 2: 'Negative', 0: 'Unset'}"
            :aria-describedby="ariaDescribedby"
            name="radios-btn-default"
            buttons
          ></b-form-radio-group>
        </b-form-group>
      </b-form-group>
    </b-form>

    <template #modal-footer="{ ok, cancel }">
      <b-button @click="handleClose(null) && cancel()">Cancel</b-button>
      <b-button variant="primary" v-if="aviComment.id" @click="edit() && ok()">Save</b-button>
      <b-button variant="success" v-else @click="create" :disabled="loading">Create</b-button>
    </template>
  </b-modal>
</template>
<script>

import {mapActions, mapState} from 'vuex';

export default {
  name: 'UserEditDialog',
  data() {
    let initialState = {
      id: null,
      user_id: null,
      avis_id: null,
      opinion: null,
      content: null
    };
    return {
      loading: false,
      status: 'hidden',
      resolve: null,
      reject: null,
      aviComment: initialState,
      initialState: initialState,
      error: null
    }
  },

  computed: {
    ...mapState('dialogs/aviComment', {
      form: state => state
    }),
    visible: {
      get() {
        return !!(this.status === 'create' || this.status === 'edit')
      },
      set() {
        this.status = 'hidden'
      }
    }
  },


  watch: {
    form: {
      deep: true,
      handler(value) {
        this.clearData();
        this.aviComment.id = value.id;
        this.status = value.status;
        this.resolve = value.resolve;
        this.reject = value.reject;

        if (this.aviComment.id) {
          this.load();
        }
      }
    }
  },


  methods: {
    ...mapActions({
      close: 'dialogs/aviComment/clear',
    }),
    clearData() {
      this.aviComment = this.initialState;
    },
    handleClose(done = null) {
      done ? done() : this.status = 'hidden';
    },
    load() {
      this.loading = true;
      this.$api.adminComments.get(this.aviComment.id).then(response => {
        this.aviComment = response.data;
      }).catch(() => {
        this.reject();
        this.clearData();
        this.close();
      }).finally(() => {
        this.loading = false;
      })
    },
    create() {
      this.error = null;
      this.loading = true;
      this.$api.adminComments.create(this.aviComment).then(response => {
        this.resolve(response);
        this.handleClose();
      }).catch(() => {
      }).finally(() => {
        this.loading = false;
      })
    },
    edit() {
      this.error = null;
      this.loading = true;
      this.$api.adminComments.update(this.aviComment.id, this.aviComment).then(response => {
        this.resolve(response);
        this.handleClose();
      }).catch(() => {
      }).finally(() => {
        this.loading = false;
      })
    }
  }
}
</script>
