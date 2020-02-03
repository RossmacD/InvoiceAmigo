<template>
<div>
  <h1>Home page</h1>
  <h2 v-if="isProfileLoaded"> Welcome back {{name}}</h2>
</div>
</template>

<script>
import { mapGetters, mapState } from "vuex";
export default {
  computed: {
    ...mapGetters(["getProfile", "isAuthenticated", "isProfileLoaded"]),
    ...mapState({
      authLoading: state => state.auth.status === "loading",
      name: state => `${state.user.profile.name}`
    })
  }
};
</script>
