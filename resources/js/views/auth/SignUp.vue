<template>
  <div class="auth-signup" style="min-height: inherit">
    <b-row class="d-flex justify-content-center align-items-center" style="min-height: inherit">
      <div class="auth-container">
        <h2 class="mb-4">Create Account</h2>
        <span v-for="(error, key) in errors" :key="key" class="text-danger d-block mb-4">{{ error }}</span>
        <b-form>
          <b-form-input
            class="mb-3"
            v-model="$v.form.username.$model"
            type="text"
            :state="validateState('username')"
            placeholder="Username"
          />
          <b-form-input
            class="mb-3"
            v-model="$v.form.email.$model"
            :state="validateState('email')"
            type="text"
            placeholder="Email (optional for password reset & Prizes)"
          />
          <b-form-input
            class="mb-3"
            v-model="$v.form.password.$model"
            type="password"
            :state="validateState('password')"
            placeholder="Password"
          />
          <b-form-input
            class="mb-3"
            v-model="$v.form.password_repeat.$model"
            type="password"
            :state="validateState('password_repeat')"
            placeholder="Confirm password"
          />
          <b-check
            class="mb-3"
            :state="validateState('eighteen')"
            v-model="$v.form.eighteen.$model"
          >I am at least 18yrs old
          </b-check>
          <div class="mb-3">
            <b-button type="submit" @click="register">Register</b-button>
          </div>

          <router-link :to="{ name: 'auth.signin'}">Already a member? Login</router-link>
        </b-form>
      </div>
    </b-row>
  </div>
</template>
<script>
import {mapActions} from "vuex";

const {required, minLength, email, sameAs} = require('vuelidate/lib/validators')
export default {
  data() {
    return {
      form: {
        username: null,
        email: null,
        password: null,
        password_repeat: null,
        eighteen: false
      },
      errors: []
    }
  },

  validations: {
    form: {
      username: {
        required: required,
        minLength: minLength(1)
      },
      email: {email},
      password: {
        required: required,
        minLength: minLength(6)
      },
      password_repeat: {
        required: required,
        minLength: minLength(6)
      },
      eighteen: {
        sameAs: sameAs(() => true)
      }
    }
  },

  methods: {
    ...mapActions({
      login: 'auth/LOGIN'
    }),
    validateState(name) {
      const {$dirty, $error} = this.$v.form[name];
      return $dirty ? !$error : null;
    },

    register(e) {
      e.preventDefault();

      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.errorRefreshed = false;
        return;
      }

      const payload = this.form;
      this.$api.auth.register(payload).then(response => {
        if (response.status === 'success') {
          this.login(payload).then(() => {
            this.$router.push({name: 'ratings.profile'})
          })
        }
      }).catch(error => {
        this.errors = [];
        const errors = error.response.data.errors;
        for (let i in errors) {
          let error = errors[i][0]
          this.errors.push(error)
        }
      })
    }
  }
}
</script>
<style lang="scss">
.auth-signup {

  h2, a, label {
    color: white;
  }

  h2 {
    font-family: 'Futura PT', sans-serif;
  }

  .form-control {
    border-radius: 0;
  }

  .auth-container {
    background: #000;
    padding: 25px;
    width: 340px;
    border-radius: 5px;
    margin-bottom: 100px;
  }
  
  .btn-secondary {
    color: #fff;
    background-color: transparent;
    border-color: #99ff66;
	font-size: 1rem;
}

.btn-secondary:hover {
    color: #57bd25;
    background-color: #ffffff;
    border-color: #57bd25;
}
}
</style>
