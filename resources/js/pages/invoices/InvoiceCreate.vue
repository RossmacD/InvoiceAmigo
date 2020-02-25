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
        <!-- handle=".handle" :options="{draggable:'.table-responsive-md > table > tbody > tr',handle:'.handle'}"  @start="drag = true" @end="drag = false"-->
        <!-- ORDER:
        <Dragable v-model='invoice.products'>
          <div v-for='product in invoice.products' v-bind:key='product'>Name: {{product.name}}</div>
        </Dragable>-->

        <!-- <Dragable v-model='invoice.products' tag='span' draggable='.table-light'> -->
        <b-table responsive='md' striped hover :fields='fields' :items='invoice.invoiceLines' foot-clone>
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
              v-model='invoice.invoiceLines[name_data.index].name'
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
                v-model='invoice.invoiceLines[description_data.index].description'
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
                v-model='invoice.invoiceLines[cost_data.index].cost'
              ></b-form-input>
              <b-form-invalid-feedback id='input-live-feedback'>Required</b-form-invalid-feedback>
            </b-form-group>
          </template>
          <template v-slot:cell(type)='type_data'>
            <!-- <b-form-select v-model="invoice.invoiceLines[type_data.index]"  options="{product}"></b-form-select> -->
            <b-dropdown id='dropdown-1' :text='invoice.invoiceLines[type_data.index].dropText'>
              <b-dropdown-group id='dropdown-product'>
                <b-dropdown-item @click='setDropText(type_data.index,`product`,null)'>Product</b-dropdown-item>
              </b-dropdown-group>
              <b-dropdown-divider></b-dropdown-divider>
              <b-dropdown-group id='dropdown-service' header='Service'>
                <b-dropdown-item @click='setDropText(type_data.index,`service`,`hour`)'>Hourly</b-dropdown-item>
                <b-dropdown-item @click='setDropText(type_data.index,`service`,`day`)'>Daily</b-dropdown-item>
                <b-dropdown-item @click='setDropText(type_data.index,`service`,`week`)'>Weekly</b-dropdown-item>
              </b-dropdown-group>
            </b-dropdown>
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
                v-model='invoice.invoiceLines[quantity_data.index].quantity'
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
                  v-model='invoice.invoiceLines[options_data.index].save'
                ></b-form-checkbox>
              </b-form-group>
              <DeleteButton v-on:on-confirm='deleteRow' :id='options_data.index' :index='options_data.index'></DeleteButton>
            </b-row>
          </template>
          <template v-slot:foot(quantity)>Total Cost: €{{total}}</template>

          <template v-slot:foot()>
            <br />
          </template>
        </b-table>
        <b-button v-on:click='addRow()' variant='success'>Add Line</b-button>
        <b-button v-b-modal.helper-modal variant='success'>Add Line with helper</b-button>

        <!-- Modals -->
        <b-modal hide-footer id='helper-modal' title='Add Invoice Line'>
          <b-button class='mt-3' block @click='getAllProducts()'>Add Product</b-button>
          <div v-if='helperShow==`Products`'>
            <h5 class='mt-4'>Search:</h5>
            <vue-bootstrap-typeahead :data='helperProducts' autocomplete='line_name' :serializer='s=>s.name' @hit='fillHelperResult()' v-model='helperKeyword' :minMatchingChars='1' />
            <h3>Your {{helperShow}}</h3>
            <b-spinner v-if='!helperProducts[0]' small label='Loading...'></b-spinner>
            <b-list-group v-for='(item,index) in helperProducts' v-bind:key='item.id'>
              <b-list-group-item :active='helperProducts[index].selected'>
                {{index+1}}. {{item.name}}
                <!-- <b-button class='float-right' @click='helperProducts[index].quantity=1;helperProducts[index].selected=true;helperCart.push(helperProducts[index]);'>Add</b-button> -->
                <b-form-checkbox class='float-right' v-model='helperProducts[index].selected' @change='groupToggle(index)' switch name='check-button' />
              </b-list-group-item>
            </b-list-group>
          </div>
          <div>
            <b-button class='mt-3' block @click='getAllServices()'>Add Service</b-button>
            <b-button class='mt-3' block>Add new</b-button>
          </div>
          <h3 class='mt-4'>Add Items to invoice:</h3>
          <b-list-group v-for='(item,index) in helperCart' v-bind:key='item.id'>
            <b-list-group-item>
              {{index+1}}. {{item.name}}
              <b-form-spinbutton class='float-right' style='width:25%;' v-model='helperCart[index].quantity' min='1' max='100'></b-form-spinbutton>
            </b-list-group-item>
          </b-list-group>
          <b-button class='mt-3' variant='success' block @click='saveCart()'>Save</b-button>
          <hr />
          <b-button class='mt-3' variant='light' block @click='$bvModal.hide(`helper-modal`)'>Cancel</b-button>
        </b-modal>

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
  ButtonPlugin,
  ModalPlugin
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
Vue.use(ModalPlugin);
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
          invoiceLines: [
            {
              name: "",
              description: "",
              cost: "",
              quantity: "",
              type: "product",
              dropText: "Product",
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
        { key: "type", variant: "active" },
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
      total: 0,
      helperProducts: [],
      helperServices: [],
      helperCart: [],
      helperShow: null,
      helperKeyword: ""
    };
  },
  methods: {
    totalCost() {
      this.total = this.invoice.invoiceLines.reduce(
        (acc, line) => acc + line.cost * line.quantity,
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
      this.invoice.invoiceLines.push({
        name: "",
        description: "",
        cost: "",
        quantity: "",
        type: "product",
        dropText: "Product",
        save: false
      });
    },
    deleteRow(id, index) {
      this.invoice.invoiceLines.splice(index, 1);
    },
    getAllProducts() {
      const app = this;
      if (app.isAuthenticated) {
        app.helperShow = "Products";
        if (!!!app.helperProducts[0]) {
          axios
            .get("/api/products")
            .then(response => {
              app.helperProducts = response.data.products.data;
            })
            .catch(err => {
              console.log(error);
            });
        }
      }
    },
    getAllServices() {
      const app = this;
      if (app.isAuthenticated) {
        app.helperShow = "Services";
        if (!!app.helperServices) {
          axios
            .get("/api/services")
            .then(response => {
              app.helperServices = response.data.services.data;
            })
            .catch(err => {
              console.log(error);
            });
        }
      }
    },
    searchProducts(index) {
      const app = this;
      axios
        .get("/api/search/products", {
          params: { keywords: app.invoice.invoiceLines[index].name }
        })
        .then(res => {
          app.searchResults = res.data.invoiceLines;
        })
        .catch(err => {
          console.log("CANT FETCH", err);
        });
    },
    groupToggle(index) {
      const app = this;
      //Delete Product if selected otherwise add to cart
      if (app.helperProducts[index].selected) {
        app.helperCart = app.helperCart.filter(item => {
          item.id !== app.helperProducts[index].id;
        });
      } else {
        app.helperProducts[index].quantity = 1;
        app.setDropTextForItem(app.helperProducts[index]);
        app.helperCart.push(app.helperProducts[index]);
      }
    },
    fillWithResult(index) {
      //Fill in Invoice Line after autocomplete is selected
      const newProduct = this.searchResults.filter(
        result => result.name == this.invoice.invoiceLines[index].name
      );
      this.invoice.invoiceLines[index].description = newProduct[0].description;
      this.invoice.invoiceLines[index].cost = newProduct[0].cost;
      if ("undefined" === typeof newProduct[0]["rate_unit"]) {
        console.log("undefined");
        //Update the type + dropdown Text
        this.setDropText(index, "product", null);
      } else {
        console.log("defined");
        this.invoice.invoiceLines[index].rate_unit = newProduct[0].rate_unit;
        this.setDropText(index, "service", newProduct[0].rate_unit);
      }
      this.invoice.invoiceLines[index].quantity = 1;
      this.totalCost();
    },
    fillHelperResult() {
      //Add to helper autocomplete is selected
      const newProduct = this.helperProducts.filter(
        result => result.name == this.helperKeyword
      );
      newProduct[0].quantity = 1;
      newProduct[0].selected = true;
      if (newProduct[0].type === "product") {
        newProduct[0].dropText = "Product";
      } else if (unit === "day") {
        newProduct[0].dropText = "Daily";
      } else {
        newProduct[0].dropText =
          newProduct[0].rate_unit.charAt(0).toUpperCase() +
          newProduct[0].rate_unit.slice(1) +
          "ly";
      }

      this.helperCart.push(newProduct[0]);
    },
    saveCart() {
      this.invoice.invoiceLines = [].concat(
        this.invoice.invoiceLines,
        this.helperCart
      );
      this.$bvModal.hide("helper-modal");
    },
    setDropText(index, type, unit) {
      this.invoice.invoiceLines[index].rate_unit = unit;
      //Set Text for dropdown + update line type
      if (type === "product") {
        this.invoice.invoiceLines[index].type = type;
        this.invoice.invoiceLines[index].dropText = "Product";
      } else {
        this.invoice.invoiceLines[index].type = "service";
        if (unit === "day") {
          this.invoice.invoiceLines[index].dropText = "Daily";
        } else {
          this.invoice.invoiceLines[index].dropText =
            unit.charAt(0).toUpperCase() + unit.slice(1) + "ly";
        }
      }
    },
    setDropTextForItem(item) {
      if (item.type === "product") {
        item.dropText = "Product";
      } else if (unit === "day") {
        item.dropText = "Daily";
      } else {
        item.dropText =
          item.rate_unit.charAt(0).toUpperCase() +
          item.rate_unit.slice(1) +
          "ly";
      }
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
            .post("/api/invoices", app.invoice)
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
.append-fix > .btn {
  border-radius: 0px 15px 15px 0px !important;
}
</style>