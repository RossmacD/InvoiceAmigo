<template>
  <div class="myMain flex1 ">
    <LoadingPage v-if='!invoiceList'></LoadingPage>
    <EmptyIndex indexType='invoice' v-else-if='filteredInvoiceList.length===0'></EmptyIndex>
    <b-card v-else v-for='(invoice,index) in filteredInvoiceList' v-bind:key='invoice.id' class='mt-3 mb-4 smallFooter shadow-sm' footer-bg-variant='light'>
            <b-row>
                <b-col  style='display:flex;'>
                <b-badge :variant='invoice.status===`paid`?`success`:`danger`' class='text-light flexCenter p-2 mx-1' style='text-transform: capitalize;'>
                <span>{{invoice.status==='unseen'&&recieving?'Unopened':invoice.status}}</span>
                </b-badge>
                <h5 class='flexCenter mx-1'>#{{ invoice.invoice_number }}:</h5>
                <h5 v-if='recieving'  class='flexCenter mx-1'>{{invoice.business_name}}</h5>
                <h5 class='flexCenter mx-1' v-else-if='invoice.draft_email ||invoice.user'>{{ invoice.draft_email || invoice.user.email}}</h5>
            </b-col>
             <b-col>
            <b-button class='float-right mx-1 ' variant='secondary' :pressed='false' :to='`invoices/`+invoice.id' size='sm'>
              <b-icon variant='light' icon='eye-fill' style='width: 20px; height: 20px'></b-icon>
            </b-button>
            <div v-if='!recieving'>
              <b-button class='float-right mx-1 ' variant='secondary' :pressed='false' :to='`invoices/edit/`+invoice.id' v-if='invoice.status==`draft`' size='sm'>
                <b-icon variant='light' icon='pen' style='width: 20px; height: 20px'></b-icon>
              </b-button>
              <DeleteButton class='float-right mx-1 ' v-on:on-confirm='deleteInvoice' :id='invoice.id' :index='index' v-if='invoice.status==`draft`'></DeleteButton>
              <ReversalButton class='float-right mx-1 ' v-on:on-confirm='reverseInvoice' :id='invoice.id' :index='index' v-else></ReversalButton>
            </div>
          </b-col>
            </b-row>
            
            <template v-slot:footer >
            <b-row class=''>
                <b-col style="display: flex;flex-direction: row;">
                    <small class="my-0 mx-1 op05">Sent: <strong>{{invoice.invoice_date}}</strong></small>
                    <small class="my-0 mx-1 op05">Due: <strong>{{invoice.due_date}}</strong></small>
                </b-col>
                <b-col class='text-right'>
                    <h5 class="my-0 mx-1">Amount: <strong>€{{invoice.total_cost}}</strong></h5>
                </b-col>
            </b-row>
          </template>
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
      pageRoute:"/"+this.itemName+"/",
    };
  },
  methods: { deleteInvoice(id, index) {
      this.$emit("on-delete-confirm", this.id, this.index);
    },
    reverseInvoice(id, index) {
      this.$emit("on-reverse-confirm", this.id, this.index);
    }
  },
  mounted() {
     
  },
  computed: {
    filteredInvoiceList: function () {
        const app=this;
        return this.invoiceList.filter((invoice)=> {
            return app.filter==='any' || invoice.status===app.filter
        })
  }},
  }
</script>