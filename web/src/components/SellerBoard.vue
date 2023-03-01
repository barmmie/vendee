<template>
  <div class="container">
    <div class="row">
      <div class="col">

        <div>
          <router-link
              :to="{ name: 'product.create'}"
              class="btn btn-primary mr-2"
              title="Create Product"
          >
            Create Product
          </router-link>
        </div>
        <Products />
      </div>
    </div>
  </div>

</template>

<script>
import Products from "./products/ProductList.vue";
export default {
  name: "Admin",
  components: {
    Products
  },
  data() {
    return {
      content: "",
    };
  },
};
</script>
