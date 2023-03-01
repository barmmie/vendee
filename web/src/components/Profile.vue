<template>
  <div class="container">
    <header class="jumbotron">
      <h3>
        <strong>{{currentUser.username}}</strong> Profile
      </h3>
    </header>

    <p>
      <strong>Id:</strong>
      {{currentUser.id}}
    </p>

    <p>
      <strong>Role:</strong>
      {{currentUser.role}}
    </p>

  </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
  name: 'Profile',
  computed: {
     ...mapGetters({
        currentUser: "auth/user",
      }),
  },
  mounted() {
    if (!this.currentUser) {
      this.$router.push('/login');
    }
  }
};
</script>