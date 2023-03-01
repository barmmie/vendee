<template>
  <div v-if="userDetail">
    <p>
      <strong>Balance:</strong>
      {{userDetail.deposit}} &nbsp;

      <button @click="reset" class="btn btn-sm btn-secondary btn-block" :disabled="loadingReset">
                  <span
                      v-show="loadingReset"
                      class="spinner-border spinner-border-sm"
                  ></span>
        <span>Reset</span>
      </button>
    </p>

    <div v-if="message" class="alert alert-info" role="alert">
      {{ message }}
    </div>

    <Form @submit="handleDeposit" :validation-schema="schema" >
      <div>
        <div class="form-group">
          <label>Deposit</label>
          <Field name="amount" as="select" class="form-select">
            <option selected>Select a role</option>
            <option value="5">Five cents</option>
            <option value="10">Ten cents</option>
            <option value="20">Twenty cents</option>
            <option value="50">Fifty cents</option>
            <option value="100">Hundred cents</option>
          </Field>

          <ErrorMessage name="amount" class="error-feedback" />
        </div>

        <div class="form-group mt-3">
          <button class="btn btn-primary btn-block" :disabled="loading">
                  <span
                      v-show="loading"
                      class="spinner-border spinner-border-sm"
                  ></span>
            <span>Deposit</span>
          </button>
        </div>
      </div>
    </Form>
  </div>
</template>

<script>
import UserService from "../services/user.service";
import * as yup from "yup";
import { Form, Field, ErrorMessage } from "vee-validate";
import { mapGetters} from "vuex";
import {object} from "yup";

export default {
  name: "Deposit",
  props: {
    userDetail: object
  },
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {

    const schema = yup.object().shape({
      amount: yup.number().required("Amount is required!"),
    });
    return {
      schema,
      message: "",
      loading: false,
      loadingReset: false,
    };
  },
  methods: {

    reset() {
      this.loadingReset = true;
      this.message = "";
      UserService.resetDeposit().then(
          (response) => {
            this.message = "Reset succesful"
            this.loadingReset = false;
            this.$emit('fetch-user-detail')
          },
          (error) => {
            this.loadingReset = false;
            this.message =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
          }
      );
    },
    handleDeposit(depositData) {
      this.message = "";
      this.loading = true;

      UserService.depositMoney(depositData).then(
          (response) => {
            this.message = "Deposit successful, proceed to purchase";
            this.loading = false;
            this.$emit('fetch-user-detail')
          },
          (error) => {
            this.message =
                (error.response &&
                    error.response.data &&
                    error.response.data.message) ||
                error.message ||
                error.toString();
            this.loading = false;
          }
      )
    },
  },
};

</script>