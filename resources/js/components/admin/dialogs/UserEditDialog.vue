<template>
  <b-modal
    :title="user.id ? 'Edit User' : 'Create User'"
    :visible.sync="visible"
    @submit.prevent.native=""
    @hide="handleClose(null)"
  >
    <b-form>
      <b-form-group label="Username">
        <b-input type="text" v-model="user.username" />
      </b-form-group>
      <b-form-group label="Email">
        <b-input type="text" v-model="user.email" />
      </b-form-group>
      <b-form-group label="Role">
        <b-form-select :options="roles" v-model="user.role" />
      </b-form-group>
      <b-form-group label="Password">
        <b-input type="password" v-model="user.password" />
      </b-form-group>
    </b-form>

    <template #modal-footer="{ ok, cancel }">
      <b-button @click="handleClose(null) && cancel()">Cancel</b-button>
      <b-button variant="primary" v-if="user.id" @click="edit() && ok()">Save</b-button>
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
      name: null,
      email: null,
      role: 1,
      password: null
    };
    return {
      loading: false,
      status: 'hidden',
      roles: [
        {value: 1, text: 'Administrator'},
        {value: 2, text: 'User'},
      ],
      resolve: null,
      reject: null,
      user: initialState,
      initialState: initialState,
      error: null
    }
  },

  computed: {
    ...mapState('dialogs/user', {
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
        this.user.id = value.id;
        this.status = value.status;
        this.resolve = value.resolve;
        this.reject = value.reject;

        if (this.user.id) {
          this.load();
        }
      }
    }
  },


  methods: {
    ...mapActions({
      close: 'dialogs/user/clear',
    }),
    clearData() {
      this.user = this.initialState;
    },
    handleClose(done = null) {
      done ? done() : this.status = 'hidden';
    },
    load() {
      this.loading = true;
      this.$api.adminUsers.get(this.user.id).then(response => {
        this.user = response.data;
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
      this.$api.adminUsers.create(this.user).then(response => {
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
      this.$api.adminUsers.update(this.user.id, this.user).then(response => {
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
