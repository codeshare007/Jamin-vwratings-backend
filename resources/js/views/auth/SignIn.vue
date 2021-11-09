<template>
  <div class="auth-page" style="min-height: inherit">
    <b-row class="d-flex justify-content-center align-items-center" style="min-height: inherit">
      <div class="auth-container">
        <b-row class="d-flex justify-content-center align-items-center">
          <b-col cols="12">
            <div class="auth-page__signin" @keyup.enter="submitLogin()" v-if="this.signin_form">
              <h2 class="text-white">Login</h2>
              <span class="error-message text-center text-danger d-block">{{ this.error }}</span>
              <b-form-group class="m-0 mb-1">
                <b-form-input
                  size="lg"
                  class="mb-2"
                  placeholder="Username"
                  type="text"
                  :state="validateState('username')"
                  v-model="form.username"
                  autofocus
                />
              </b-form-group>

              <b-form-group class="password-group">
                <b-form-input
                  size="lg"
                  class="mb-2"
                  placeholder="Password"
                  :type="this.password_reveal ? 'text' : 'password'"
                  :state="validateState('password')"
                  v-model="form.password"
                />
                <div class="password-reveal" @click="passwordReveal()">
                  <b-icon
                    variant="primary"
                    :icon="this.password_reveal ? 'eye-slash' : 'eye'"
                  />
                </div>
              </b-form-group>
              <b-button size="large" variant="dark" class="w-100" type="submit" @click="submitLogin()">Login</b-button>
              <div class="text-center mt-2"><router-link :to="{ name: 'auth.signup'}" style="font-size: 20px;">Need an account first? Go Register</router-link></div>
              <a class="text-center mt-3 d-block cursor-pointer" @click="signin_form = false">Forgot password?</a>

            </div>
            <div class="auth-page__forgot" v-else>

              <div v-if="!forget_sent">
                <h2 class="font-weight-bold">Forgotten Password ?</h2>

                <span class="d-block mb-5 text-black-50">Enter your email to reset your password</span>

                <b-form-input
                  class="pt-4 pb-4"
                  placeholder="Email"
                  type="text"
                  v-model="forgetForm.email"
                  @keyup.enter="submitForgetPassword()"
                  autofocus
                />

                <div class="mt-3">
                  <b-button variant="primary" class="mr-2" type="submit" @click="submitForgetPassword()">Send</b-button>
                  <b-button @click="signin_form = true">Back</b-button>
                </div>

              </div>

              <div class="auth-page__sent mb-3" v-if="forget_sent">
                <h2 class="font-weight-bold">Check your mail</h2>
                <p>We sent the instructions to: <br> {{ forgetForm.email }}</p>
                <b-button @click="signin_form = true">Back</b-button>
              </div>
            </div>
          </b-col>
        </b-row>
      </div>
    </b-row>
  </div>
</template>
<script>
const { required } = require('vuelidate/lib/validators')
import { mapActions } from 'vuex';

export default {
  data() {
    return {
      forget_sent: false,
      signin_form: true,
      password_reveal: false,
      error: null,
      form: {
        username: '',
        password: ''
      },
      forgetForm: {
        email: ''
      }

    }
  },

  validations: {
    form: {
      username: {required},
      password: {required}
    }
  },

  methods: {
    ...mapActions({
      login: 'auth/LOGIN'
    }),

    passwordReveal() {
      this.password_reveal = !this.password_reveal;
    },

    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    submitForgetPassword() {
      this.$v.form.email.$touch();
      if (this.$v.form.email.$error) {
        return;
      }

      this.$api.auth.forgotPass({email: this.form.email}).then(() => {
        this.forget_sent = true;
      })
    },

    submitLogin() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        return;
      }

      const formData = new FormData();
      formData.append('username', this.form.username);
      formData.append('password', this.form.password);

      this.login(formData).then(() => {
        this.$router.push({name: 'ratings.profile'})
      }).catch(error => {
        this.error = error.response.data.message
      })
    }
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
    color: white;
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
    background: #24252d;
    padding: 25px;
    width: 500px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
}
</style>
