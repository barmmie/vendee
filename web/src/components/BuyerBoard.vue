<template>
  <div class="container">
      <div class="row ">
        <div class="col">
          <h3>Deposit</h3>

          <Deposit :user-detail="userDetail" @fetch-user-detail="fetchUserDetail" />

        </div>
        <div class="col">
          <h3>Buy</h3>

          <Purchase @fetch-user-detail="fetchUserDetail" />
        </div>
      </div>
  </div>
</template>

<script>

import { Form, Field, ErrorMessage } from "vee-validate";
import Deposit from "./Deposit.vue";
import Purchase from "./Purchase.vue";
import UserService from "../services/user.service";
import {mapGetters} from "vuex";

export default {
  name: "BuyerBoard",
  components: {
    Deposit,
    Purchase,
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    return {
      userDetail: null
    }
  },
  mounted() {
    this.fetchUserDetail()
  },
  computed: {
    ...mapGetters({
      currentUser: "auth/user"
    })
  },
  methods: {
    fetchUserDetail() {
      return UserService.getUser(this.currentUser.id).then(
          response => {
            this.userDetail = response.data.data;
            return Promise.resolve(response.data);
          },
          error => {
            return Promise.reject(error);
          }
      );
    },
  }
};
</script>
