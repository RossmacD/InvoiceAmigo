<template>
  <footer class='page-footer'>
    <b-container class='footer-bg'>
      <b-row>
        <b-col md='12' class='py-4 mt-1'>
          <div class='mb-5 flex flexEnd'>
            <b-button variant="outline-success ">
              <b-icon icon="pencil-square" class=''> </b-icon><p class=" m-0">
                Create Invoice
              </p>
            </b-button>

            <b-button variant="outline-light mx-1">
              <b-icon icon="gear-fill" class=''> </b-icon><p class="m-0">
                Settings
              </p>
            </b-button>

            <b-button variant="outline-light mx-1" style="self-align:flex-start">
              <b-icon icon="cloud-fill" class=''> </b-icon><p class=" m-0">
                cPanel Admin
              </p>
            </b-button>
            
          </div>
        </b-col>
      </b-row>
    </b-container>
    <!-- Copyright -->
    <div class='footer-copyright text-center text-white py-3'>
      © 2020 Copyright:
      <a>InvoiceAmigo</a>
    </div>
  </footer>
</template>

<script>
import { BIcon,} from 'bootstrap-vue'
export default {
components: {
    BIcon,
  },
}
</script>

<style>
</style>