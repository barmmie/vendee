<template>
  <div id="app">
    <nav class="navbar navbar-expand navbar-dark bg-dark">
      <a href="/" class="navbar-brand">Vendee</a>
      <div class="navbar-nav mr-auto">
        <li class="nav-item">
          <router-link to="/home" class="nav-link">
            Home
          </router-link>
        </li>
        <li v-if="showSellerBoard" class="nav-item">
          <router-link to="/seller" class="nav-link">Seller Board</router-link>
        </li>
        <li v-if="showBuyerBoard" class="nav-item">
          <router-link to="/buyer" class="nav-link">Buyer Board</router-link>
        </li>

      </div>

      <div v-if="!currentUser" class="navbar-nav ml-auto">
        <li class="nav-item">
          <router-link to="/register" class="nav-link">
            <font-awesome-icon icon="user-plus" /> Sign Up
          </router-link>
        </li>
        <li class="nav-item">
          <router-link to="/login" class="nav-link">
            <font-awesome-icon icon="sign-in-alt" /> Login
          </router-link>
        </li>
      </div>

      <div v-if="currentUser" class="navbar-nav ml-auto">
        <li class="nav-item">
          <router-link to="/profile" class="nav-link">
            {{ currentUser.username }}
          </router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" @click.prevent="logOut">
             LogOut
          </a>
        </li>
      </div>
    </nav>

    <div class="container mt-5">
      <router-view />
    </div>
  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {

  computed: { ...mapGetters({
      currentUser: "auth/user",
    }),
    showSellerBoard() {
      return this.currentUser?.is_seller;
    },
    showBuyerBoard() {
      return this.currentUser?.is_buyer;
    }
  },
  methods: {
    logOut() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login');
    }
  }
};
</script>
