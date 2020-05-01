<template>
  <div>
    <LoadingPage v-if='!invoiceList'></LoadingPage>
    <EmptyIndex indexType='invoice' v-else-if='filteredInvoiceList.length===0'></EmptyIndex>
    <b-card v-else v-for='(invoice,index) in filteredInvoiceList' v-bind:key='invoice.id' class='my-2' footer-bg-variant='light' :footer='invoice.created_at' header header-bg-variant='dark'>
      <template v-slot:header>
        <b-row>
          <b-col>
            <b-badge :variant='invoice.status===`paid`?`success`:`danger`' class='text-dark py-2 float-left' style='text-transform: capitalize;'>
              <span>{{invoice.status==='unseen'&&recieving?'Unopened':invoice.status}}</span>
            </b-badge>
            <h3 class='text-light float-left mx-2'>#{{ invoice.invoice_number }}:</h3>
          </b-col>
          <b-col>
            <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to='`invoices/`+invoice.id' size='sm'>
              <b-icon variant='light' icon='eye-fill' style='width: 20px; height: 20px'></b-icon>
            </b-button>
            <div v-if='!recieving'>
              <b-button class='float-right mx-1' variant='secondary' :pressed='false' :to='`invoices/edit/`+invoice.id' v-if='invoice.status==`draft`' size='sm'>
                <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
              </b-button>
              <DeleteButton class='float-right mx-1' v-on:on-confirm='deleteInvoice' :id='invoice.id' :index='index' v-if='invoice.status==`draft`'></DeleteButton>
              <ReversalButton class='float-right mx-1' v-on:on-confirm='reverseInvoice' :id='invoice.id' :index='index' v-else></ReversalButton>
            </div>
          </b-col>
        </b-row>
      </template>
      <b-row>
        <b-col>
          <h4 v-if='recieving'>Recieved from: {{invoice.business_name}}</h4>
          <h4 v-else-if='invoice.draft_email ||invoice.user'>Invoice sent to: {{ invoice.draft_email || invoice.user.email}}</h4>

          <p>{{invoice.note}}</p>
        </b-col>
        <b-col></b-col>
      </b-row>
    </b-card>
  </div>
</template>

<script>
import axios from "axios";
import Vue from "vue";
import { mapGetters, mapState } from "vuex";
import { SpinnerPlugin, ButtonPlugin, CardPlugin,BadgePlugin } from "bootstrap-vue";
import EmptyIndex from "./EmptyIndex";
import LoadingPage from "./LoadingPage";
import ErrorPage from "./ErrorPage";
import DeleteButton from "./DeleteButton";
import ReversalButton from "./ReversalButton";
export default {
 name: "InvoiceIndexTab",
  components: {
    EmptyIndex,
    DeleteButton,
    LoadingPage,
    ErrorPage,
    ReversalButton
  },
   props:{ 
    invoiceList:{
      type: Array,
      },
      recieving:{
          type: Boolean,
          default:false
      },
      filter:{
          type:String,
          default:'any'
      }
  },
  data() {
    return {
      deleteText: "",
      apiRoute: "/api/"+this.itemName+"/",
      pageRoute:"/"+this.itemName+"/"
    };
  },
  methods: { deleteInvoice(id, index) {
      this.$emit("on-delete-confirm", this.id, this.index);
    },
    reverseInvoice(id, index) {
      this.$emit("on-reverse-confirm", this.id, this.index);
    }
  },
  computed: {
    filteredInvoiceList: function () {
        const app=this;
        return this.invoiceList.filter((invoice)=> {
            return app.filter==='any' || invoice.status===app.filter
        })
  }}
  }
</script>