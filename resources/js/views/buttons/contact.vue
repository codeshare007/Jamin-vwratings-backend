<template>
  <div class="contact-page">
    <b-row>
      <b-col cols="6">
        <h1>Message Us</h1>
        <p>If you need us to reply to your message be sure to leave an email address.</p>
        <p>If you are requesting an interview please leave your avi name and put interview in the message.</p>

        <b-form>
          <b-form-group label="Avi Name (optional)">
            <b-form-input v-model="form.avi_name" />
          </b-form-group>

          <b-form-group label="Email (optional)">
            <b-form-input v-model="form.email" />
          </b-form-group>

          <b-form-group label="Message (max 255)">
            <b-form-textarea rows="8" v-model="form.content" />
          </b-form-group>

          <b-button variant="dark">Send</b-button>
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
        avi_name: null,
        email: null,
        content: null
      }
    }
  }
}
</script>
<style lang="scss">
  .contact-page {
    h1, p, legend {
      color: white;
    }
  }
</style>