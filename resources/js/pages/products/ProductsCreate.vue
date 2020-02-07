<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Product
    </h3>
    <b-form>
      <b-form-group label='Product Name' label-for='product_name'>
        <b-form-input :state='nameState' aria-describedby='input-live-feedback' id='product_name' type='text' name='product_name' required autocomplete='product_name' autofocus v-model='product.name'></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
        <!-- <b-form-invalid-feedback v-if='messages.product.name' force-show>{{messages.product.name[0]}}</b-form-invalid-feedback> -->
      </b-form-group>
      <b-form-group label='Product Description' label-for='product_description'>
        <b-form-input id='product_description' type='text' name='product_description' required autocomplete='product_description' autofocus v-model='product.description'></b-form-input>
        <b-form-invalid-feedback v-if='messages.product.description' force-show>{{messages.product.description[0]}}</b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label='Product Cost' label-for='product_cost'>
        <b-form-input id='product_cost' type='number' name='product_cost' required autocomplete='product_cost' autofocus v-model='product.cost'></b-form-input>
        <b-form-invalid-feedback v-if='messages.product.cost' force-show>{{messages.product.cost[0]}}</b-form-invalid-feedback>
      </b-form-group>

      <b-form-group class='mb-0'>
        <div class='col-md-4'>
          <b-button v-on:click='submit()' v-if='!submiting' class='btn btn-primary'><span v-if='editing'>Update</span>
      <span v-else>Create</span></b-button>
          <b-button v-else class='btn btn-info'>
            <b-spinner small label='Loading...'></b-spinner>
          </b-button>
        </div>
      </b-form-group>
    </b-form>
  </div>
</template>

<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_REQUEST } from "../../store/actions/auth";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);

export default {
  name: "ProductCreate",
  props: {
    product: {
      type: Object,
      default: function() {
        return {
          name: "",
          description: "",
          cost: ""
        };
      }
    },
    editing: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      messages: {
        product: {
          name: [],
          description: [],
          cost: []
        }
      },

      submiting: false
    };
  },
  methods: {
    submit() {
      const app = this;
      app.submiting = true;
      if (app.isAuthenticated) {
        if (app.editing) {
          axios
            .put("/api/products/"+ app.product.id, app.product)
            .then(response => {
              this.$router.push("/products");
            })
            .catch(err => {console.log(err.response)});
        } else {
          axios
            .post("/api/products/", app.product)
            .then(response => {
              this.$router.push("/products");
            })
            .catch(err => {});
        }

        app.submiting = false;
      }
    }
  },
  computed: {
    nameState() {
      if (this.product.name.length == 0) {
        return null;
      } else if (this.product.name.length > 4) {
        return true;
      } else {
        return false;
      }
    },
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>