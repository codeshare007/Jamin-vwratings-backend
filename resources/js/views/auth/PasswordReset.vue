<template>
  <div class="auth-page" style="min-height: inherit">
    <b-row class="d-flex justify-content-center align-items-center" style="min-height: inherit">
      <div class="auth-container">
        <b-row class="d-flex justify-content-center align-items-center">
          <div class="d-flex flex-column mb-2">
            <span v-for="(error, key) in errors" :key="key" class="text-center text-danger d-block mb-2">{{
                error
              }}</span>
          </div>
          <b-col cols="12">
            <b-form-input
              v-model="$v.form.email.$model"
              type="email"
              :state="validateState('email')"
              placeholder="Email"
              class="mb-4"
            />
            <b-form-input
              v-model="$v.form.password.$model"
              type="password"
              :state="validateState('password')"
              placeholder="Password (must be 8 characters minimal length)"
              class="mb-4"
            />
            <b-form-input
              v-model="$v.form.password_confirmation.$model"
              type="password"
              :state="validateState('password_confirmation')"
              placeholder="Password confirmation"
              class="mb-4"
            />

            <div class="d-flex">
              <button class="btn-submit mr-2" @click="changePassword">Change</button>
              <b-button :to="{name: 'auth.signin'}">Back</b-button>
            </div>
          </b-col>
        </b-row>
      </div>
    </b-row>
  </div>
</template>
<script>
const {required, email, minLength} = require('vuelidate/lib/validators')

export default {
  props: {
    token: String
  },
  data() {
    return {
      errors: [],
      form: {
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },

  validations: {
    form: {
      email: {required, email},
      password: {
        required: required,
        minLength: minLength(8)
      },
      password_confirmation: {
        required: required,
        minLength: minLength(8)
      },
    }
  },

  methods: {
    changePassword() {
      this.$v.form.$touch();
      if (this.$v.form.$anyError) {
        this.errorRefreshed = false;
        return;
      }

      if (this.token) {

        const payload = {
          token: this.token,
          password: this.form.password,
          password_confirmation: this.form.password_confirmation,
          email: this.form.email
        }

        this.$api.auth.resetPassword(payload).then(response => {
          console.log(response);
        //  this.$router.push({name: 'auth.signin'})
        }).catch(error => {
          this.errors = [];
          const errors = error.response.data.errors;
          for (let i in errors) {
            let error = errors[i][0]
            this.errors.push(error)
          }
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

  .btn-submit {
    color: #fff;
    background-color: #508f3e;
    border-radius: 5px;
    border: 0;
    padding: 6px 12px;

    &:focus {
      outline: 0;
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
    width: 430px;
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
