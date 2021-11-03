<template>
  <b-modal
    :title="field.id ? 'Edit Field' : 'Create Field'"
    :visible.sync="visible"
    @submit.prevent.native=""
    @hide="handleClose(null)"
  >
    <b-form>
      <b-form-input type="text" v-model="field.key" placeholder="Key*" />
      <b-form-input type="text" v-model="field.value" placeholder="Value" />
      <b-form-textarea type="text" v-model="field.description" placeholder="Password" />
    </b-form>

    <template #modal-footer="{ ok, cancel }">
      <b-button @click="handleClose(null) && cancel()">Cancel</b-button>
      <b-button variant="primary" v-if="field.id" @click="edit() && ok()">Save</b-button>
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
      key: null,
      value: null,
      description: null
    };
    return {
      loading: false,
      status: 'hidden',
      resolve: null,
      reject: null,
      field: initialState,
      initialState: initialState,
      error: null
    }
  },

  computed: {
    ...mapState('dialogs/field', {
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
        this.field.id = value.id;
        this.status = value.status;
        this.resolve = value.resolve;
        this.reject = value.reject;

        if (this.field.id) {
          this.load();
        }
      }
    }
  },

  methods: {
    ...mapActions({
      close: 'dialogs/field/clear',
    }),
    clearData() {
      this.field = this.initialState;
    },
    handleClose(done = null) {
      done ? done() : this.status = 'hidden';
      //  this.$refs.form.resetFields();
    },
    load() {
      this.loading = true;
      this.$api.field.get(this.field.id).then(response => {
        this.field = response.data;
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
      this.$api.field.create(this.field).then(response => {
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
      this.$api.field.update(this.field.id, this.field).then(response => {
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
