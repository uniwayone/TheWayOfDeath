<template>
  <v-menu
    bottom
    left
    min-width="200"
    offset-y
    origin="top right"
    transition="scale-transition"
  >
    <template v-slot:activator="{ attrs, on }">
      <v-btn
        class="ml-2"
        min-width="0"
        text
        v-bind="attrs"
        v-on="on"
      >
        <v-icon color="white">mdi-account</v-icon>
      </v-btn>
    </template>

    <v-list
      :tile="false"
      flat
      nav
    >
      <v-list-item class="nav-v-list-item"
        to="/profile"
      >
        <v-list-item-title>我的</v-list-item-title>
      </v-list-item>
      <!-- <AppBarItem
        to="#"
      >
        <v-list-item-title>Settings</v-list-item-title>
      </AppBarItem> -->
      <v-divider
        class="mb-2 mt-2"
      />
      <v-list-item class="nav-v-list-item" @click="logout">
        <v-list-item-title >退出</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
import AppBarItem from '../app/BarItem'
export default {
  name: 'DefaultAccount',

  components : {
    AppBarItem,
  },

  data: () => ({
    profile: [
      { title: 'Profile' },
      { title: 'Settings' },
      { divider: true },
      { title: 'Log out' },
    ],
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')
      // Redirect to login.
      this.$router.push({ name: 'login' })
    },
  }
}
</script>
