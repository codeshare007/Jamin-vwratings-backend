<template>
  <b-modal
    :title="avi.id ? 'Edit Avi' : 'Create Avi'"
    :visible.sync="visible"
    @submit.prevent.native=""
    @hide="handleClose(null)"
  >
    <b-form>
      <b-form-group label="Avi name">
        <b-input type="text" v-model="avi.name" />
      </b-form-group>
      <b-form-group label="User Id">
        <b-input type="text" v-model="avi.user_id" />
      </b-form-group>
    </b-form>

    <template #modal-footer="{ ok, cancel }">
      <b-button @click="handleClose(null) && cancel()">Cancel</b-button>
      <b-button variant="primary" v-if="avi.id" @click="edit() && ok()">Save</b-button>
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
      name: null,
    };
    return {
      loading: false,
      status: 'hidden',
      resolve: null,
      reject: null,
      avi: initialState,
      initialState: initialState,
      error: null
    }
  },

  computed: {
    ...mapState('dialogs/avi', {
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
        this.avi.id = value.id;
        this.status = value.status;
        this.resolve = value.resolve;
        this.reject = value.reject;

        if (this.avi.id) {
          this.load();
        }
      }
    }
  },


  methods: {
    ...mapActions({
      close: 'dialogs/avi/clear',
    }),
    clearData() {
      this.avi = this.initialState;
    },
    handleClose(done = null) {
      done ? done() : this.status = 'hidden';
    },
    load() {
      this.loading = true;
      this.$api.adminAvis.get(this.avi.id).then(response => {
        this.avi = response.data;
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
      this.$api.adminAvis.create(this.avi).then(response => {
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
      this.$api.adminAvis.update(this.avi.id, this.avi).then(response => {
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
