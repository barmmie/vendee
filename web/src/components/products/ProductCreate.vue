<template>
  <div class="container">

    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <h6>Add Product</h6>
          </div>
          <div class="card-body">
            <!-- <form v-on:submit.prevent="onSaveProduct" :validation-schema="schema"> -->
            <VeeForm
                :validation-schema="schema"
                @submit="onSubmit"
            >
              <!-- <Form @submit="onSaveProduct" :validation-schema="schema"> -->
              <div class="form-group row my-1">
                <div class="col-12">
                  <label>Product Name:</label>
                  <Field
                      id="productName"
                      v-model="product.productName"
                      name="productName"
                      type="text"
                      class="form-control"
                  />
                  <ErrorMessage
                      name="productName"
                      class="text-capitalize text-danger"
                  />
                </div>
                <div class="col-12">
                  <label>Product Cost:</label>
                  <Field
                      v-model="product.cost"
                      name="cost"
                      type="number"
                      class="form-control"
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
                      v-model="product.amountAvailable"
                      name="amountAvailable"
                      type="number"
                      class="form-control"
                  />
                  <ErrorMessage
                      name="amountAvailable"
                      class="text-capitalize text-danger"
                  />
                </div>
              </div>
              <div class="form-group">
                <router-link
                    to="/products"
                    class="btn btn-secondary mr-2"
                >
                  Cancel
                </router-link>
                <input
                    v-if="!isCreating"
                    type="submit"
                    class="btn btn-primary mx-2 my-2"
                    value="Add Product"
                >
                <button
                    v-if="isCreating"
                    class="btn btn-primary mx-2 my-2"
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
            </VeeForm>
          </div>
        </div>
      </div>
      <div class="col"></div>
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
      message: "",
      product: {},
    };
  },

  computed: { ...mapGetters({isCreating: "product/isCreating", createdData: "product/createdData"}) },

  watch: {
    createdData: function () {
      if (this.createdData !== null && !this.isCreating) {



      }
    },
  },

  methods: {
    ...mapActions({storeProduct: "product/storeProduct"}),
    onSubmit() {
      this.message = "";
      this.storeProduct(this.product)
      .then(() => {
        this.$swal.fire({
          text: "Success, Product has been added.",
          icon: "success",
          timer: 1000,
        });
        this.$router.push({ name: "seller" });
      }, (error) => {

        console.dir(error);
        this.message =
            (error.response &&
                error.response.data &&
                error.response.data.message) ||
            error.message ||
            error.toString();
      });
    },
  },
};
</script>