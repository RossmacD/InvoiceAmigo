<template>
  <div>
    <LoadingPage v-if='!loaded'></LoadingPage>
    <div v-else>
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
              <b-form-datepicker id='example-datepicker' v-model='invoice.invoice_date'></b-form-datepicker>
            </b-form-group>
          </b-col>
          <b-col>
            <b-form-group label='Due Date' label-for='due_date'>
              <b-form-datepicker id='due_date' v-model='invoice.due_date'></b-form-datepicker>
            </b-form-group>
          </b-col>
        </b-row>
        <hr />
        <h3>Invoice Items</h3>
        <h4>Products</h4>
        <!-- handle=".handle" :options="{draggable:'.table-responsive-md > table > tbody > tr',handle:'.handle'}"  @start="drag = true" @end="drag = false"-->
        <!-- ORDER:
        <Dragable v-model='invoice.products'>
          <div v-for='product in invoice.products' v-bind:key='product'>Name: {{product.name}}</div>
        </Dragable>-->

        <!-- <Dragable v-model='invoice.products' tag='span' draggable='.table-light'> -->
        <b-table responsive='md' striped hover :fields='fields' :items='invoice.products' foot-clone>
          <template v-slot:cell(no.)='data'>
            <span>
              {{ data.index + 1 }}
              <b-icon class='handle' icon='arrows-expand' style='width: 20px; height: 20px'></b-icon>
            </span>
          </template>
          <template v-slot:cell(name)='name_data'>
            <vue-bootstrap-typeahead
              :data='searchResults'
              :id='`line_name${name_data.index}`'
              :name='`line_name${name_data.index}`'
              autocomplete='line_name'
              :serializer='s=>s.name'
              :value='name_data'
              @hit='fillWithResult(name_data.index)'
              :minMatchingChars='1'
              v-model='invoice.products[name_data.index].name'
              @input='searchProducts(name_data.index)'
            />
          </template>

          <template v-slot:cell(description)='description_data'>
            <b-form-group>
              <b-form-input
                :id='`line_description${description_data.index}`'
                type='text'
                :name='`line_description${description_data.index}`'
                required
                autocomplete='line_description'
                :value='description_data'
                v-model='invoice.products[description_data.index].description'
              ></b-form-input>
              <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
            </b-form-group>
          </template>

          <template v-slot:cell(cost)='cost_data'>
            <b-form-group>
              <b-form-input
                @input='totalCost()'
                :id='`line_cost${cost_data.index}`'
                type='number'
                :name='`line_cost${cost_data.index}`'
                required
                autocomplete='line_cost'
                :value='cost_data'
                v-model='invoice.products[cost_data.index].cost'
              ></b-form-input>
              <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
            </b-form-group>
          </template>

          <template v-slot:cell(quantity)='quantity_data'>
            <b-form-group>
              <b-form-input
                @input='totalCost()'
                :id='`line_quantity${quantity_data.index}`'
                type='number'
                :name='`line_quantity${quantity_data.index}`'
                required
                autocomplete='line_quantity'
                :value='quantity_data'
                v-model='invoice.products[quantity_data.index].quantity'
              ></b-form-input>
              <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
            </b-form-group>
          </template>
          <template v-slot:cell()>
            <span class='align-text-bottom'>
              <h5>x</h5>
            </span>
          </template>
          <template v-slot:cell(options)='options_data'>
            <b-row>
              <b-form-group description='Save' label-for='line_options0'>
                <b-form-checkbox
                  :id='`line_save${options_data.index}`'
                  type='number'
                  :name='`line_save${options_data.index}`'
                  required
                  autocomplete='line_options'
                  value='true'
                  v-model='invoice.products[options_data.index].save'
                ></b-form-checkbox>
              </b-form-group>
              <DeleteButton v-on:on-confirm='deleteRow' :id='options_data.index' :index='options_data.index'></DeleteButton>
            </b-row>
          </template>
          <template v-slot:foot(quantity)>Total Cost: €{{total}}</template>
          
           <template v-slot:foot()>
             <br>
           </template>
        </b-table>
        <!-- </Dragable> -->
        <hr />
        <b-form-group label='Invoice Notes' label-for='notes'>
          <b-form-textarea id='note' name='note' rows='4' placeholder='Enter note' autocomplete='note' v-model='invoice.note'></b-form-textarea>
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
  </div>
</template>

<script>
import axios from "axios";
import Dragable from "vuedraggable";
import {
  FormPlugin,
  SpinnerPlugin,
  TablePlugin,
  BIcon,
  FormDatepickerPlugin,
  ButtonPlugin
} from "bootstrap-vue";
import Vue from "vue";
import DeleteButton from "../../components/DeleteButton";
import LoadingPage from "../../components/LoadingPage";
import { mapGetters, mapState } from "vuex";
import { AUTH_REQUEST } from "../../store/actions/auth";
import VueBootstrapTypeahead from "vue-bootstrap-typeahead";
Vue.use(SpinnerPlugin);
Vue.use(ButtonPlugin);
Vue.use(FormPlugin);
Vue.use(TablePlugin);
// Vue.use(Dragable);
Vue.use(FormDatepickerPlugin);

export default {
  name: "InvoiceCreate",
  components: {
    DeleteButton,
    LoadingPage,
    BIcon,
    Dragable,
    VueBootstrapTypeahead
  },
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
          products: [
            {
              name: "",
              description: "",
              cost: "",
              quantity: "",
              save: false
            }
          ],
          services: []
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
      searchResults: [],
      keywords: "",
      loaded: false,
      drag: true,
      fields: [
        "no.",
        "name",
        "description",
        { key: "cost", variant: "active" },
        { key: " ", variant: "active" },
        { key: "quantity", variant: "active" },
        "options"
      ],
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
      submiting: false,
      total: 0
    };
  },
  methods: {
    totalCost() {
      this.total = this.invoice.products.reduce(
        (acc, product) => acc + product.cost * product.quantity,
        0
      );
    },
    getDate(addon) {
      const toTwoDigits = num => (num < 10 ? "0" + num : num);
      let today = new Date();
      let year = today.getFullYear();
      let month = toTwoDigits(today.getMonth() + 1);
      let day = toTwoDigits(today.getDate() + addon);
      return `${year}-${month}-${day}`;
    },
    addRow() {
      this.invoice.products.push({
        name: "",
        description: "",
        cost: "",
        quantity: "",
        save: false
      });
    },
    deleteRow(id, index) {
      this.invoice.products.splice(index, 1);
    },
    searchProducts(index) {
      // this.invoice.products[name_data.index].name
      axios
        .get("/api/search/products", {
          params: { keywords: this.invoice.products[index].name }
        })
        .then(res => {
          console.log(res);
          this.searchResults = res.data.products;
        })
        .catch(err => {
          console.log("CANT FETCH", err);
        });
    },
    fillWithResult(index) {
      const newProduct = this.searchResults.filter(
        result => result.name == this.invoice.products[index].name
      );
      this.invoice.products[index].description = newProduct[0].description;
      this.invoice.products[index].cost = newProduct[0].cost;
      this.invoice.products[index].quantity = 1;
      this.totalCost();
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
    if (!this.editing) {
      this.invoice.due_date = this.getDate(1);
      this.invoice.invoice_date = this.getDate(0);
      const app = this;
      axios
        .get("/api/invoice/create")
        .then(res => {
          console.log(res.data.invoice_number);
          app.invoice.invoice_number = res.data.invoice_number;
          this.loaded = true;
        })
        .catch(err => {
          console.log(err);
        });
    } else {
      this.loaded = true;
    }
  },
  computed: {
    ...mapGetters(["isAuthenticated"]),
    ...mapState({})
  }
};
</script>
<style>
div.b-calendar-grid-body > div > div > span {
  padding: 0.5em 0.5em !important;
  border-radius: 45% !important;
}
</style>