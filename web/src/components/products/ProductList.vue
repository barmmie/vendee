<template>
  <div class="container">
    <div class="row justify-content-center mt-2 mb-2">
      <div class="col-8">
        <h4 class="text-left mb-2">All Products</h4>
      </div>
      <div class="col-4">
        <input
            type="text"
            class="form-control"
            placeholder="Search Products..."
            @input="searchProducts"
            v-model="query.search"
        />
      </div>
    </div>
    <div class="">
      <div class="" v-if="!isLoading">
        <div class="row border-bottom border-top p-2 bg-light">
          <div class="col-1">Sl</div>
          <div class="col-3">Product Name</div>
          <div class="col-2">Product Price</div>
          <div class="col-3">Amount Available</div>
          <div class="col-2">Actions</div>
        </div>

        <div v-for="(product, index) in productsPaginatedData.data" :key="product.id">
          <div class="row border-1 p-2">
            <div class="col-1 text-left">
              {{ index + 1 }}
            </div>
            <div class="col-3">
              {{ product.productName }}
            </div>
            <div class="col-2">
              <strong class="text-danger">{{ product.cost }} cents </strong>
            </div>
            <div class="col-3">
              {{ product.amountAvailable }}
            </div>
            <div class="col-2">
              <slot name="action" :product="product">
                <router-link
                    :to="{ name: 'product.edit', params: { id: product.id } }"
                    class="btn btn-sm btn-primary mr-2"
                    title="Edit Product"
                >
                  Edit
                </router-link>
                <button
                    class="btn btn-sm btn-danger mx-2"
                    title="Delete Product"
                    @click="deleteProductModal(product.id)"
                >
                  Delete
                </button>
              </slot>
            </div>
          </div>

        </div>
      </div>

      <div v-if="isLoading" class="text-center mt-5 mb-5">
        Loading Products...
        <div class="spinner-grow" role="status">
        </div>
      </div>
    </div>

    <!-- Insert Pagination Part -->
    <div v-if="productsPaginatedData !== null" class="vertical-center mt-2 mb-5">
      <v-pagination
          v-model="query.page"
          :pages="productsPaginatedData.pagination.total_pages"
          :range-size="2"
          active-color="#DCEDFF"
          @update:modelValue="getResults"
      />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import VPagination from "@hennge/vue3-pagination";

export default {
  data() {
    return {
      query: {
        page: 1,
        search: "",
      },
    };
  },
  components: {
    VPagination,
  },
  computed: { ...mapGetters({
      productList: "product/productList",
      productsPaginatedData: "product/productsPaginatedData",
      isLoading: "product/isLoading",
      isDeleting: "product/isDeleting", deletedData: "product/deletedData"
  }) },

  methods: {
    ...mapActions({deleteProduct: "product/deleteProduct", fetchAllProducts :"product/fetchAllProducts"}),
    getResults() {
      this.fetchAllProducts(this.query);
    },

    searchProducts() {
      this.fetchAllProducts(this.query);
    },
    deleteProductModal(id) {
      this.$swal
          .fire({
            text: "Are you sure to delete the product ?",
            icon: "error",
            cancelButtonText: "Cancel",
            confirmButtonText: "Yes, Confirm Delete",
            showCancelButton: true,
          })
          .then((result) => {
            if (result["isConfirmed"]) {
              this.deleteProduct(id).then(() => {
                this.fetchAllProducts({
                  page: 1,
                  search: ''
                });
                this.$swal.fire({
                  text: "Success, Product has been deleted.",
                  icon: "success",
                  position: 'top-end',
                  timer: 1000,
                });
              });

            }
          });
    },
  },

  created() {
    this.fetchAllProducts(this.query);
  },
};
</script>