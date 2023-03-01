<template>
  <div class="container">
    <div class="card">
      <div class="card-header">
        <h6>Edit Product</h6>
      </div>
      <div class="card-body">
        <VeeForm
            :validation-schema="schema"
            @submit="onSubmit"
        >
          <div v-if="isLoading">
            <div class="text-center">
              <div
                  class="spinner-border text-primary"
                  role="status"
              >
              </div>
              <br>
              Loading Product Details
            </div>
          </div>
          <div v-if="product !== null && !isLoading">
            <div class="form-group row">
              <div class="col-6 my-1">
                <label>Product Name:</label>
                <Field
                    id="productName"
                    name="productName"
                    type="text"
                    class="form-control"
                    :value="product.productName"
                    @input="updateProductInputAction"
                />
                <ErrorMessage
                    name="productName"
                    class="text-capitalize text-danger"
                />
              </div>
              <div class="col-6">
                <label>Product Cost:</label>
                <Field
                    name="cost"
                    type="number"
                    class="form-control"
                    :value="product.cost"
                    @input="updateProductInputAction"
                />
                <ErrorMessage
                    name="cost"
                    class="text-capitalize text-danger"
                />
              </div>
            </div>
            <div class="form-group row my-1">
              <div class="col-12">
                <label>Product Stock (Amount Available):</label>
                <Field
                    name="amountAvailable"
                    type="number"
                    class="form-control"
                    :value="product.amountAvailable"
                    @input="updateProductInputAction"
                />
                <ErrorMessage
                    name="amountAvailable"
                    class="text-capitalize text-danger"
                />
              </div>
            </div>
            <div class="form-group my-3">
              <router-link
                  to="/products"
                  class="btn btn-secondary mr-2"
              >
                Cancel
              </router-link>
              <input
                  v-if="!isUpdating"
                  type="submit"
                  class="btn btn-primary mx-2"
                  value="Save Update"
              >
              <button
                  v-if="isUpdating"
                  class="btn btn-primary"
                  type="button"
                  disabled
              >
                <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                />
                Saving...
              </button>
            </div>
          </div>
        </VeeForm>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import { Field, Form as VeeForm, ErrorMessage } from "vee-validate";
import * as yup from "yup";

export default {
  components: {
    Field,
    VeeForm,
    ErrorMessage,
  },
  setup() {
    const schema = yup.object({
      productName: yup.string().required().min(5),
      cost: yup.number().required(),
      amountAvailable: yup.number().required(),
    });

    return {
      schema,
    };
  },

  data() {
    return {
      id: null,
    };
  },

  computed: {
    ...mapGetters({ isUpdating: "product/isUpdating", updatedData: "product/updatedData", product: "product/product", isLoading: "product/isLoading"}),
  },

  watch: {
    updatedData: function () {
      if (this.updatedData !== null && !this.isUpdating) {
        this.$swal.fire({
          text: "Success, Product has been updated successfully !",
          icon: "success",
          position: "top-end",
          timer: 1000,
        });

        this.$router.push({ name: "seller" });
      }
    },
  },

  created: function () {
    this.id = this.$route.params.id;
    this.fetchDetailProduct(this.id);
  },

  methods: {
    ...mapActions({
      updateProduct: "product/updateProduct",
      updateProductInput: "product/updateProductInput",
      fetchDetailProduct: "product/fetchDetailProduct",
    }),
    onSubmit() {
      this.updateProduct(this.product);
    },
    updateProductInputAction(e) {
      this.updateProductInput(e);
    },
  },
};
</script>