<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Invoice
    </h3>
    <b-form>
    <b-row>
      <b-col md="3">
            <b-form-group label="Invoice Number" label-for="invoice_number">
                <b-form-input id='invoice_number' type='number' name='invoice_number' required autocomplete='invoice_number' autofocus v-model='invoice.invoice_number'></b-form-input>
                <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
            </b-form-group>
      </b-col>
      <b-col md="3">
            <b-form-group label="Currency" label-for="currency">
                <b-form-select plain v-model="invoice.currency" :options="currencyOptions"></b-form-select>
            </b-form-group>
      </b-col>
    </b-row>
    <hr>
    <b-row>
      <b-col>
        <b-form-group label="Invoice Date" label-for="invoice_date">
                <b-form-input id='invoice_date' type='date' name='invoice_date' required autocomplete='invoice_date' autofocus v-model='invoice.invoice_date'></b-form-input>
                <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group label="Due Date" label-for="due_date">
                <b-form-input id='due_date' type='date' name='due_date' required autocomplete='due_date' autofocus v-model='invoice.due_date'></b-form-input>
                <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
        </b-form-group>
      </b-col>
    </b-row>
      <b-form-group label="Invoice Notes" label-for="notes">
        <b-form-textarea id='note' name='note' rows='4' placeholder='Enter note' autocomplete='note' autofocus v-model='invoice.note'>
        </b-form-textarea>
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
  name: "InvoiceCreate",
  props: {
    invoice: {
      type: Object,
      default: function() {
        return {
          invoice_number: "",
          client_id: "",
          invoice_date: "",
          due_date: "",
          currency: "eur"
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
      selectedCurrency: null,
      currencyOptions: [
        { value: 'eur', text: '€ - Euro' },
        { value: 'gbp', text: '£ - Pound Sterling'},
        { value: 'usd', text: '$ - US Dollar'}
      ],
      messages: {
        invoice: {  
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
            .put("/api/invoice/"+ app.invoice.id, app.invoice)
            .then(response => {
              this.$router.push("/invoices");
            })
            .catch(err => {console.log(err.response)});
        } else {
          axios
            .post("/api/invoices/", app.invoice)
            .then(response => {
              this.$router.push("/invoices");
            })
            .catch(err => {});
        }

        app.submiting = false;
      }
    }
  },
  computed: {
    nameState() {
      if (this.invoice.name.length == 0) {
        return null;
      } else if (this.invoice.name.length > 4) {
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