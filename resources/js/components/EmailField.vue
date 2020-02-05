<template>
  <b-form-group label='Email Address' label-for='email'>
    <b-form-input v-model='email' id='email' type='email' name='email' required autocomplete='email' autofocus @focus="active = 'email'" @blur="active = 'null'" :state='validationState'></b-form-input>
    <b-form-invalid-feedback v-if='message' force-show>{{message[0]}}</b-form-invalid-feedback>
  </b-form-group>
</template>

<script>
import Vue from "vue";
import { FormPlugin, ButtonPlugin } from "bootstrap-vue";
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);

export default {
  name: "EmailField",
  props: {
    messages: Array
  },
  data() {
    return {
      message: !!this.messages ? this.messages : [],
      active: null,
      email:""
    };
  },
  computed: {
    validationState() {
      if (this.email.length == 0 && this.message.length == 0) {
        return null;
      } else if (
        /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(this.email)
      ) {
        this.message = [];
        this.$emit('email-update',this.email);
        return true;
      } else {
          if(this.email.length===0){
            this.message = ["Please enter an email address"]
        }
        else{
            this.message = ["Please enter a valid email address"]
        }
        return false;
      }
    }
  }
};
</script>