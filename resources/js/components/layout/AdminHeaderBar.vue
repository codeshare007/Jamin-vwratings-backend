<template>
  <b-navbar toggleable="lg" class="header-bar" variant="">
    <b-container class="p-0">
      <b-navbar-brand :to="{ name: 'ratings.home' }">
        VW Ratings
      </b-navbar-brand>
      <b-navbar-toggle
        class="bg-transparent p-0"
        target="nav-mobile"
        @click="toggleMobileMenu"
      />
      <div id="nav-mobile" class="nav-mobile" :class="{'d-none': !isMobileMenu }">
        <div class="d-flex justify-content-between mt-2">
          <b-navbar-brand class="ml-3" :to="{ name: 'ratings.home' }">
            Ratings
          </b-navbar-brand>
          <button>
            <b-icon-x
              @click="toggleMobileMenu"
              variant="light"
              height="3rem"
              width="3rem"
            />
          </button>
        </div>

        <div>
          <header-profile v-if="loggedIn"/>
        </div>

        <b-navbar-nav class="ml-auto" v-if="!loading">
          <b-nav-item
            v-for="(item, key) in menu"
            :to="item.path"
            :key="key"
            @click="isMobileMenu = !isMobileMenu"
          >{{ item.name }}
          </b-nav-item>
        </b-navbar-nav>
      </div>
      <b-collapse id="nav-collapse" is-nav>
        <div v-if="loading" class="d-flex justify-content-end w-100">
          <b-skeleton
            class="mt-3 mr-3"
            v-for="i in 3"
            :key="i"
            animation="wave"
            height="30px"
            width="140px"
          />
        </div>
        <b-navbar-nav class="ml-auto" v-else>
          <li v-for="(item, key) in menu" :key="key" class="nav-item">
            <a class="nav-link" :href="item.href" v-if="item.href" v-text="item.name"/>
            <router-link class="nav-link" :to="item.path" v-if="item.path" v-text="item.name"/>
          </li>
          <li class="nav-item" v-if="!loggedIn">
            <router-link class="nav-link" :to="{ name: 'auth.signin'}">sing in</router-link>
          </li>
          <li class="nav-item" v-if="!loggedIn">
            <router-link class="nav-link" :to="{ name: 'auth.signup'}">register</router-link>
          </li>
        </b-navbar-nav>

        <header-profile v-if="loggedIn"/>


      </b-collapse>
    </b-container>
  </b-navbar>
</template>
<script>
import HeaderProfile from "./HeaderProfile";

export default {
  components: {HeaderProfile},
  data() {
    return {
      menu: [
        {name: 'Dashboard', path: {name: 'ratings.home'}},
        {name: 'Parties', path: {name: 'ratings.parties.list'}},
        {name: 'Avis', path: {name: 'ratings.avis.list'}},
        {name: 'Creeps', path: {name: 'ratings.creeps.list'}},
        {name: 'Contact Us', path: {name: 'ratings.contacts'}}
      ],
      isMobileMenu: false,
      loading: false,
    }
  },

  methods: {
    toggleMobileMenu() {
      this.isMobileMenu = !this.isMobileMenu
    }
  },

  computed: {
    loggedIn() {
      return this.$store.getters['auth/loggedIn']
    },
  },
}
</script>
<style lang="scss">
.header-bar {
  height: 120px;
  margin-bottom: 40px;

  .navbar-brand {
    font-family: 'Futura PT', sans-serif;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 25px;
    color: white;

    &:focus,
    &:hover {
      color: white;
    }
  }

  @media screen and (max-width: 994px) {
    background: #00000066;
    height: 54px;

    .navbar-brand img {
      width: 25px;
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 255' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e")
    }
  }

  .nav-mobile {
    width: 100%;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 100000;
    overflow: hidden;
    touch-action: none;
    height: 100%;
    background: rgb(31 31 31 / 98%);
    color: white;

    .nav-item a {
      color: white;
    }
  }

  .navbar-nav .nav-link {
    color: white;
    font-size: 24px;
    text-transform: lowercase;
    font-weight: 400;
    font-family: 'Futura PT', sans-serif;

    &.router-link-exact-active,
    &.router-link-active {
      color: white;
    }

    &:hover {
      color: white;
    }
  }

  .navbar-nav .nav-item a:not(.dropdown-toggle):not(.dropdown-item) {
    margin: 0 10px;
  }
}
</style>
