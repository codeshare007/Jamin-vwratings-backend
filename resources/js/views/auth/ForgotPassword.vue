<template>
  <b-row class="forgetPage">
    <div class="auth-container">
      <b-form>
        <div v-if="!sent">
          <h3 class="font-weight-bold text-center">Password reset</h3>

          <span class="d-block text-danger text-center">{{ error }}</span>

          <b-form-group label="Enter your email to reset your password" v-if="!loading">
            <b-form-input
              class="pt-4 pb-4"
              placeholder="Email you signed up with"
              type="email"
              v-model="$v.form.email.$model"
              autofocus
            />
          </b-form-group>

          <div class="d-flex justify-content-center" v-if="loading">
            <b-spinner/>
          </div>

          <div class="mt-3 text-center">
            <b-button class="btn-submit mr-2" type="submit" @click="send">Send</b-button>
            <b-button :to="{name: 'auth.signin'}">Back</b-button>
          </div>
        </div>
      </b-form>

      <div class="auth-page__sent mb-3" v-if="sent">
        <h2 class="font-weight-bold">Check your mail</h2>
        <p>We sent the instructions to: <br> {{ form.email }}</p>
        <b-button :to="{name: 'auth.signin'}">Back</b-button>
      </div>
    </div>
  </b-row>
</template>
<script>
import {required} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      error: null,
      loading: false,
      sent: false,
      form: {
        email: ''
      }
    }
  },

  validations: {
    form: {
      email: {required}
    }
  },


  methods: {
    send(e) {
      e.preventDefault();
      this.$v.form.email.$touch();
      if (this.$v.form.email.$error) {
        return;
      }

      this.loading = true;
      this.$api.auth.forgotPass(this.form).then(response => {
        this.loading = false;
        if (response.data.data === 'passwords.sent') this.sent = true;
      }).catch(error => {
        if (error.response.data.errors) {
          this.error = error.response.data.errors.email[0];
          this.loading = false;
        }
      })
    },
  }
}
</script>
<style lang="scss">
.forgetPage {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: inherit;

  .auth-container {
    background: #000;
    padding: 25px;
    width: 340px;
    border-radius: 5px;
    margin-bottom: 100px;


    .btn-submit {
      background-color: #508f3e;
    }
  }
}
</style>
