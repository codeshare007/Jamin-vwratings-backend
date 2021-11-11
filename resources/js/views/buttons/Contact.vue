<template>
    <div class="page-container text-center">      
      <div class="container-fluid tm-content-container">
            <div class="position-relative page-width-1 page-right">
              <div class="tm-bg-dark content-pad">
		  <div v-if="!sent">
			<div>
			  <h1>Message Us</h1>
			  <p>If you need us to reply to your message be sure to leave an email address.</p>
			</div>

			  <b-form>
				<b-form-input class="mb-1" placeholder="Player Name (optional)" v-model="$v.form.name.$model" />
			  </b-form-group>

				<b-form-input class="mb-1" placeholder="Reply Email (optional)" v-model="$v.form.email.$model" />
			  </b-form-group>

				<b-form-textarea rows="3" class="mb-1" placeholder="Type your message....." v-model="$v.form.content.$model" />
			  </b-form-group>

			  <div class="d-flex justify-content-end">
				<b-button @click="sendMessage" variant="primary">Send</b-button>
			  </div>
			</b-form>
		  </div>
		  <div v-else>
			<h1>Message sent</h1>
		  </div>
		</div>
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
