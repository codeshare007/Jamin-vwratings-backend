<template>
   <div class="position-relative page-right">
		<div class="tm-bg-dark content-pad text-center">
		  <div v-if="!sent">
			<div>
			  <h1>Message Us</h1>
			  <p>If you need us to reply to your message be sure to leave an email address.</p>
			  <p>If you are requesting an interview please leave your avi name and put interview in the message.</p>
			</div>

			<b-form>
			  <b-form-group label="Avi Name (optional)">
				<b-form-input v-model="$v.form.name.$model" />
			  </b-form-group>

			  <b-form-group label="Email (optional)">
				<b-form-input v-model="$v.form.email.$model" />
			  </b-form-group>

			  <b-form-group label="Message (max 255)">
				<b-form-textarea rows="8" v-model="$v.form.content.$model" />
			  </b-form-group>

			  <div class="d-flex justify-content-end">
				<b-button @click="sendMessage" variant="secondary">Send</b-button>
			  </div>
			</b-form>
		  </div>
		  <div v-else>
			<h1>Message sent</h1>
		  </div>
		</div>
	</div>
</template>
<script>
import {required} from "vuelidate/lib/validators";

export default {
  data() {
    return {
      form: {
        name: null,
        email: null,
        content: null
      },

      sent: false
    }
  },


  validations: {
    form: {
      name: {},
      email: {},
      content: {required}
    }
  },


  methods: {
    sendMessage() {
      this.$v.form.$touch();

      if (this.$v.form.$anyError) {
        return;
      }

      const payload = this.form;

      axios.post('/api/v1/send-message', payload).then(response => {
        if (response.data.status === 'success') {
          this.sent = true;
        }
      })
    }
  }
}
</script>
<style lang="scss">
  .contact-page {

    h1, legend {
      font-family: 'Futura PT', sans-serif;
    }

    h1, p, legend {
      color: white;
    }
  }
</style>
