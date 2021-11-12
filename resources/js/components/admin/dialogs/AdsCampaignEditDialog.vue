<template>
  <b-modal
    :title="campaign.id ? 'Edit Comment' : 'Create Comment'"
    :visible.sync="visible"
    @submit.prevent.native=""
    size="lg"
    @hide="handleClose(null)"
  >
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Name">
            <b-form-input v-model="campaign.name" />
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Timer">
            <b-form-input v-model="campaign.timer" />
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Individual radios" v-slot="{ ariaDescribedby }">
            <b-form-radio v-model="campaign.active" :aria-describedby="ariaDescribedby" name="some-radios" value="1">Yes</b-form-radio>
            <b-form-radio v-model="campaign.active" :aria-describedby="ariaDescribedby" name="some-radios" value="0">No</b-form-radio>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Description">
        <b-form-textarea v-model="campaign.description" />
      </b-form-group>

      <b-form-group label="Content">
        <b-form-textarea v-model="campaign.content" />
      </b-form-group>
    </b-form>

    <template #modal-footer="{ ok, cancel }">
      <b-button @click="handleClose(null) && cancel()">Cancel</b-button>
      <b-button variant="primary" v-if="campaign.id" @click="edit() && ok()">Save</b-button>
      <b-button variant="success" v-else @click="create" :disabled="loading">Create</b-button>
    </template>
  </b-modal>
</template>
<script>

import {mapActions, mapState} from 'vuex';

export default {
  name: 'AdsCampaignDialog',
  data() {
    let initialState = {
      id: null,
      name: null,
      description: null,
      timer: null,
      content: null,
      active: null
    };
    return {
      loading: false,
      status: 'hidden',
      resolve: null,
      reject: null,
      campaign: initialState,
      initialState: initialState,
      error: null
    }
  },

  computed: {
    ...mapState('dialogs/campaign', {
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
        this.campaign.id = value.id;
        this.status = value.status;
        this.resolve = value.resolve;
        this.reject = value.reject;

        if (this.campaign.id) {
          this.load();
        }
      }
    }
  },


  methods: {
    ...mapActions({
      close: 'dialogs/campaign/clear',
    }),
    clearData() {
      this.campaign = this.initialState;
    },
    handleClose(done = null) {
      done ? done() : this.status = 'hidden';
    },
    load() {
      this.loading = true;
      this.$api.adminAdsCampaigns.get(this.campaign.id).then(response => {
        this.campaign = response.data;
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
      this.$api.adminAdsCampaigns.create(this.campaign).then(response => {
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
      this.$api.adminAdsCampaigns.update(this.campaign.id, this.campaign).then(response => {
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
