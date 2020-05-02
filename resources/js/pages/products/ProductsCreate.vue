<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Product
    </h3>
    <b-row>
    <b-col md="6" offset-md="3" >
    <b-form>
      <b-form-group label='Product Name' label-for='product_name'>
        <b-form-input :state="validateState('name')" aria-describedby='input-live-feedback' id='product_name' type='text' name='product_name' required autocomplete='product_name' autofocus v-model="$v.product.name.$model" @blur='checkIsValid($v.product.name, $event)' placeholder="Enter name"></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>This field <span v-if="!$v.product.name.required">is required and </span>cannot be more than 50 characters</b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label='Product Description' label-for='product_description'>
        <!-- <b-form-input id='product_description' type='text' name='product_description' required autocomplete='product_description' autofocus v-model='product.description'></b-form-input> -->
        <b-form-textarea
                id="product_description"
                class="form-control"
                name="product_description"
                required
                autocomplete="current-description"
                v-model="$v.product.description.$model"
                :state="validateState('description')"
                @blur='checkIsValid($v.product.description, $event)'
                aria-describedby="input-description-live-feedback"
                placeholder="Enter description"
                rows="4"
              ></b-form-textarea>
        <!-- <b-form-invalid-feedback v-if='messages.product.description' force-show>{{messages.product.description[0]}}</b-form-invalid-feedback> -->
        <b-form-invalid-feedback id='input-live-feedback'>This field <span v-if="!$v.product.description.required">is required and </span>cannot be more than 50 characters</b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label='Product Cost' label-for='product_cost'>
        <b-form-input id='product_cost' type='number' name='product_cost' required autocomplete='product_cost' v-model="$v.product.cost.$model" :state="validateState('cost')" @blur='checkIsValid($v.product.cost, $event)' placeholder="Enter cost"></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>
          This field
          <span v-if="!$v.product.cost.required">is required</span>
          <span v-if="!$v.product.cost.minValue">must be a positive number</span>
        </b-form-invalid-feedback>
      </b-form-group>
      <b-form-group class='mb-0'>
        <div class='col-md-4'>
          <b-button :disabled="$v.product.$invalid" v-on:click='submit()' v-if='!submiting' class='btn btn-primary'><span v-if='editing'>Update</span>
      <span v-else>Create</span></b-button>
          <b-button v-else class='btn btn-info'>
            <b-spinner small label='Loading...'></b-spinner>
          </b-button>
        </div>
      </b-form-group>
    </b-form>
    </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import { FormPlugin, ButtonPlugin, SpinnerPlugin } from "bootstrap-vue";
import Vue from "vue";
import { required, maxLength, minLength, minValue, number } from 'vuelidate/lib/validators'
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
      let errors = app.$v.product.$invalid;

      if(!errors){
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
            .catch(err => {
              app.submiting = false;
            });
        }
      }
      }
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.product[name];
      return $dirty ? !$error : null;
    },
    checkIsValid (val, event) {
      if (val.$invalid) {
        event.target.classList.add('form__input-shake')
        setTimeout(() => {
          event.target.classList.remove('form__input-shake')
        }, 600)
      }
    }
  },
  // validation rules
  validations: {
    product: {
      name: {
        required,
        maxLength: maxLength(50)
      },
      description: {
        required,
        maxLength: maxLength(50)
      },
      cost: {
        required,
        minValue: minValue(0)
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
<style>
.form__input-shake {
  animation: shake 0.2s;
  animation-iteration-count: 3;
}

@keyframes shake {
  0% { transform: translateX(0px)  }
  25% { transform: translateX(2px) }
  50% { transform: translateX(0px)  }
  75% { transform: translateX(-2px) }
  100% { transform: translateX(0px)  }
}
</style>