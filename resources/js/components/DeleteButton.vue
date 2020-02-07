<template>
  <span>
    <b-button v-if='!text' variant='danger' :pressed='false' @click='clickHandler' size='sm' class='mb-2'>
      <b-icon variant='light' icon='trash-fill' style='width: 20px; height: 20px'></b-icon>
    </b-button>
    <b-button v-else variant='warning' @mouseleave='reset' @click='clickHandler' size='sm' class='mb-2'>
      <b-icon variant='light' icon='alert-circle-fill' style='width: 20px; height: 20px'></b-icon>
      <transition name='slide-fade'>
        <span style='color:#FFF'>{{text}}</span>
      </transition>
    </b-button>
  </span>
</template>

<script>
import Vue from "vue";
import { FormPlugin, ButtonPlugin, BIcon } from "bootstrap-vue";
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);

export default {
  name: "DeleteButton",
  components: {
    BIcon
  },
  props: { id: Number, index: Number },
  data() {
    return {
      text: "",
      confirmed: false
    };
  },
  methods: {
    clickHandler() {
      if (this.text) {
        console.log(this.index);
        this.$emit("on-confirm", this.id, this.index);
      } else {
        this.text = "Are you sure?";
      }
    },
    reset() {
      setTimeout(() => (this.text = ""), 1200);

    }
  }
};
</script>

<style>
/* Enter and leave animations can use different */
/* durations and timing functions.              */
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>