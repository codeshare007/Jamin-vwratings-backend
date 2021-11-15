<template>
  <div class="auth-page" style="min-height: inherit">
    <b-row class="d-flex justify-content-center align-items-center" style="min-height: inherit">
      <div class="auth-container">
        <b-row class="d-flex justify-content-center align-items-center">
          <b-col cols="12">
            <b-form-group label="Email">
              <b-form-input v-model="form.email"/>
            </b-form-group>
            <b-form-group label="Password">
              <b-form-input v-model="form.password" type="password" />
            </b-form-group>
            <b-form-group label="Password Repeat">
              <b-form-input v-model="form.password_confirmation" type="password" />
            </b-form-group>

            <b-button @click="changePassword">Change</b-button>
          </b-col>
        </b-row>
      </div>
    </b-row>
  </div>
</template>
<script>
const {required} = require('vuelidate/lib/validators')

export default {
  props: {
    token: String
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },

  validations: {
    form: {
      password: {required},
      password_repeat: {required}
    }
  },

  methods: {
    changePassword() {
      if (this.token) {
        const payload = {
          token: this.token,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
          email: this.form.email
        }

        this.$api.auth.resetPassword(payload).then(response => {
          this.$router.push({name: 'auth.signin'})
        })
      }
    },
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },
  }
}
</script>
<style lang="scss">
.auth-page {
  display: flex;
  justify-items: center;
  flex-direction: column;

  h2 {
    font-family: 'Futura PT', sans-serif;
  }

  a {
    color: #9F6;
  }

  .password-group {
    position: relative;

    .is-valid {
      background-image: unset;
    }
  }

  .password-reveal {
    right: 0;
    top: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 47px;
    height: 100%;
    cursor: pointer;
    position: absolute;

    svg {
      width: 20px;
      height: 20px;
    }
  }

  .error-message {
    height: 30px;
  }

  .cursor-pointer {
    cursor: pointer;
  }

  .auth-container {
    background: #000;
    padding: 25px;
    width: 340px;
    border-radius: 5px;
    margin-bottom: 100px;
  }

  .btn-primary {
    color: #fff;
    background-color: #508f3e;
    border-color: #ffffff;
  }
}
</style>
