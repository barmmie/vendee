<template>
  <Products>
    <template v-slot:action="{product}">
      <button
          class="btn btn-sm btn-danger mx-2"
          title="Buy Product"
          @click="buyProduct(product)"
      >
        Buy
      </button>
    </template>
  </Products>
</template>

<script>
import Products from "./products/ProductList.vue";
import ProductService from "./../services/product.service"

export default  {
  components: {
    Products,
  },
  data() {
    return {
      loadingProducts: false,
      products: [],
    }
  },
  methods: {
    buyProduct(product) {
      this.$swal
          .fire({
            text: `Buy ${product.productName}`,
            icon: "info",
            input: 'text',
            inputLabel: 'Desired Quantity',
            inputValue: 1,
            cancelButtonText: "Cancel",
            confirmButtonText: "Yes, Confirm Purchase",
            showCancelButton: true,
          })
          .then(({isConfirmed, value}) => {
            if (isConfirmed) {
              ProductService.purchaseProduct({productId: product.id, quantity: value}).then((res) => {
                this.$emit('fetch-user-detail')
                const data = res.data;

                const change = [];

                Object.keys(data.change).forEach(function(key) {
                  if(data.change[key] > 0) {
                    change.push(`${key} cents: ${data.change[key]}pc`)
                  }
                });

                this.$swal.fire({
                  title: "Product has been purchased.",
                  text: `Amount Spent: ${data.amountSpent}`,
                  footer: `Change Breakdown ${change.join(', ')}`,
                  icon: "success",
                });
              }, (err) => {
                console.dir(err);
                this.$swal.fire({
                  text: err.response.data.error,
                  icon: "error",
                })
              });

            }
          });
    },
  }
}
</script>