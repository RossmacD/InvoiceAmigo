<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Service
    </h3>
    <b-row>
    <b-col md="6" offset-md="3" >
    <b-form>
      <b-form-group label='Service Name' label-for='service_name'>
        <b-form-input aria-describedby='input-live-feedback' id='service_name' type='text' name='service_name' required autocomplete='service_name' autofocus v-model="$v.service.name.$model" :state="validateState('name')" @blur='checkIsValid($v.service.name, $event)' placeholder="Enter name"></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>This field <span v-if="!$v.service.name.required">is required and </span>cannot be more than 50 characters</b-form-invalid-feedback>
      </b-form-group>
      <b-form-group label='Service Description' label-for='service_description'>
        <b-form-textarea
                id="service_description"
                class="form-control"
                name="service_description"
                required
                autocomplete="current-description"
                v-model="$v.service.description.$model"
                :state="validateState('description')"
                @blur='checkIsValid($v.service.description, $event)'
                aria-describedby="input-description-live-feedback"
                placeholder="Enter description"
                rows="4"
              ></b-form-textarea>
        <b-form-invalid-feedback id='input-live-feedback'>This field <span v-if="!$v.service.description.required">is required and </span>cannot be more than 50 characters</b-form-invalid-feedback>

      </b-form-group>

      <b-row>
        <b-col md="6">
        <b-form-group label='Service Rate' label-for='service_rate'>
          <b-form-input id='service_rate' type='number' name='service_rate' required autocomplete='service_rate' v-model="$v.service.cost.$model" :state="validateState('cost')" @blur='checkIsValid($v.service.cost, $event)' placeholder="Enter cost"></b-form-input>
        <b-form-invalid-feedback id='input-live-feedback'>
          This field
          <span v-if="!$v.service.cost.required">is required</span>
          <span v-if="!$v.service.cost.minValue">must be a positive number</span>
        </b-form-invalid-feedback>
        </b-form-group>
        </b-col>
        <b-col md="6">
        <b-form-group label='Unit' label-for='service_rate'>
          <b-form-select plain v-model='service.rate_unit' :options='options' :state="validateState('cost')"></b-form-select>
        </b-form-group>
        </b-col>
      </b-row>

      <b-form-group class='mb-0'>
        <div class='col-md-4'>
          <b-button :disabled="$v.service.$invalid" v-on:click='submit()' v-if='!submiting' class='btn btn-primary'>
            <span v-if='editing'>Update</span>
            <span v-else>Create</span>
          </b-button>
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
  name: "ServiceCreate",
  props: {
    service: {
      type: Object,
      default: function() {
        return {
          name: "",
          description: "",
          cost: "",
          rate_unit: "hour"
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
        service: {
          name: [],
          description: [],
          cost: [],
          rate_unit: []
        }
      },
      options: [
        { value: "hour", text: "Hourly" },
        { value: "day", text: "Daily" },
        { value: "week", text: "Weekly" }
      ],
      submiting: false
    };
  },
  methods: {
    submit() {
      const app = this;
      let errors = app.$v.service.$invalid;

      if(!errors){
      app.submiting = true;
      if (app.isAuthenticated) {
        if (app.editing) {
          axios
            .put("/api/services/" + app.service.id, app.service)
            .then(response => {
              this.$router.push("/services");
            })
            .catch(err => {
              console.log(err.response);
            });
        } else {
          axios
            .post("/api/services/", app.service)
            .then(response => {
              this.$router.push("/services");
            })
            .catch(err => {
              app.submiting = false;
            });
        }
      }
      }
    },
    validateState(name) {
      const { $dirty, $error } = this.$v.service[name];
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
    service: {
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
      if (this.service.name.length == 0) {
        return null;
      } else if (this.service.name.length > 4) {
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