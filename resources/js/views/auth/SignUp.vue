<template>
  <div class="auth-signup">
    <b-row class="d-flex justify-content-center">
      <b-col cols="5">
        <h2 class="mb-4">Create Account</h2>
        <b-form>
          <b-form-input class="mb-3" v-model="form.username" type="text" placeholder="Username" />
          <b-form-input class="mb-3" v-model="form.email" type="text" placeholder="Email (optional for password reset)" />
          <b-form-input class="mb-3" v-model="form.password" type="password" placeholder="Password" />
          <b-form-input class="mb-3" v-model="form.password_repeat" type="password" placeholder="Confirm password" />

          <div class="d-inline-flex align-items-center mb-3 ml-2">
            <input id="YearsOld" class="mr-2 form-check" type="checkbox" />
            <label class="m-0" for="YearsOld">I am at least 18yrs old</label>
          </div>

          <div class="mb-3">
            <b-button type="submit" @click="register" variant="primary">Register</b-button>
          </div>

          <router-link :to="{ name: 'auth.signin'}">Already a member? Login</router-link>
        </b-form>
      </b-col>
    </b-row>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        form: {
          username: null,
          email: null,
          password: null,
          password_repeat: null
        }
      }
    },


    methods: {
      register(e) {
        e.preventDefault();
        const payload = this.form;
        this.$api.auth.register(payload).then(response => {
          if (response.status === 'success') {
            this.$router.push({name: 'auth.signin'})
          }
        })
      }
    }
  }
</script>
<style lang="scss">
  .auth-signup {

  }
</style>
