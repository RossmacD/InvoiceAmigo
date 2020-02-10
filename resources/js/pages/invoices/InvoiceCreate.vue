<template>
  <div>
    <h3 class='text-center'>
      <span v-if='editing'>Update</span>
      <span v-else>Create</span> Invoice
    </h3>
    <b-form>
      <b-row>
        <b-col md='3'>
          <b-form-group label='Invoice Number' label-for='invoice_number'>
            <b-form-input id='invoice_number' type='number' name='invoice_number' required autocomplete='invoice_number' autofocus v-model='invoice.invoice_number'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
          </b-form-group>
        </b-col>
        <b-col md='3'>
          <b-form-group label='Currency' label-for='currency'>
            <b-form-select plain v-model='invoice.currency' :options='currencyOptions'></b-form-select>
          </b-form-group>
        </b-col>
      </b-row>
      <hr />
      <b-row>
        <b-col>
          <b-form-group label='Invoice Date' label-for='invoice_date'>
            <b-form-input id='invoice_date' type='date' name='invoice_date' required autocomplete='invoice_date'  v-model='invoice.invoice_date'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label='Due Date' label-for='due_date'>
            <b-form-input id='due_date' type='date' name='due_date' required autocomplete='due_date'  v-model='invoice.due_date'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Enter at least 5 letters</b-form-invalid-feedback>
          </b-form-group>
        </b-col>
      </b-row>
      <hr />
      <b-table striped hover :fields='fields' :items='invoice.items'>
        <template v-slot:cell(no.)='data'>{{ data.index + 1 }}</template>
        <template v-slot:cell(name)='name_data'>
          <b-form-group>
            <b-form-input id='line_name0' type='text' name='line_name0' required autocomplete='line_name' :value="name_data"  v-model='invoice.items[0].name'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
          </b-form-group>
        </template>

        <template v-slot:cell(description)='description_data'>
          <b-form-group>
            <b-form-input id='line_description0' type='text' name='line_description0' required autocomplete='line_description' :value="description_data"  v-model='invoice.items[0].description'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
          </b-form-group>
        </template>

        <template v-slot:cell(cost)='cost_data'>
          <b-form-group>
            <b-form-input id='line_cost0' type='number' name='line_cost0' required autocomplete='line_cost' :value="cost_data"  v-model='invoice.items[0].cost'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
          </b-form-group>
        </template>

        <template v-slot:cell(quantity)='quantity_data'>
          <b-form-group>
            <b-form-input id='line_quantity' type='number' name='line_quantity0' required autocomplete='line_quantity' :value="quantity_data"  v-model='invoice.items[0].quantity'></b-form-input>
            <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
          </b-form-group>
        </template>
      </b-table>
      <hr />
      <b-form-group label='Invoice Notes' label-for='notes'>
        <b-form-textarea id='note' name='note' rows='4' placeholder='Enter note' autocomplete='note'  v-model='invoice.note'></b-form-textarea>
      </b-form-group>

      <b-form-group class='mb-0'>
        <div class='col-md-4'>
          <b-button v-on:click='submit()' v-if='!submiting' class='btn btn-primary'>
            <span v-if='editing'>Update</span>
            <span v-else>Create</span>
          </b-button>
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
import {
  FormPlugin,
  ButtonPlugin,
  SpinnerPlugin,
  TablePlugin
} from "bootstrap-vue";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { AUTH_REQUEST } from "../../store/actions/auth";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(TablePlugin);
export default {
  name: "InvoiceCreate",
  props: {
    invoice: {
      type: Object,
      default() {
        return {
          invoice_number: "",
          client_id: "",
          invoice_date: "",
          due_date: "",
          currency: "eur",
          items: [{ name: "", description: "", cost: "", quantity: "" }]
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
      fields: ["no.", "name", "description", "cost", "quantity", "options"],
      currencyOptions: [
        { value: "eur", text: "€ - Euro" },
        { value: "gbp", text: "£ - Pound Sterling" },
        { value: "usd", text: "$ - US Dollar" }
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
    getDate() {
      const toTwoDigits = num => (num < 10 ? "0" + num : num);
      let today = new Date();
      let year = today.getFullYear();
      let month = toTwoDigits(today.getMonth() + 1);
      let day = toTwoDigits(today.getDate());
      return `${year}-${month}-${day}`;
    },
    submit() {
      const app = this;
      app.submiting = true;
      if (app.isAuthenticated) {
        if (app.editing) {
          axios
            .put("/api/invoices/" + app.invoice.id, app.invoice)
            .then(response => {
              this.$router.push("/invoices");
            })
            .catch(err => {
              console.log(err.response);
            });
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
  mounted() {
    this.editing ? "" : (this.invoice.due_date = this.getDate());
    this.editing ? "" : (this.invoice.invoice_date = this.getDate());
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>