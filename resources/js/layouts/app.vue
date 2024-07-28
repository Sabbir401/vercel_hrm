<script setup>
import { useStore } from "vuex";
import SideBar from "./Sidebar.vue";
import NavBar from "./Navbar.vue";
// import "bootstrap/dist/css/bootstrap.min.css";
// import "../../css/style.css";
const store = useStore();

const loged = false;
</script>

<template>
    <NavBar v-if="$store.getters.getToken !== 0"></NavBar>
    <div id="layoutSidenav">
        <SideBar v-if="$store.getters.getToken !== 0"></SideBar>
        <div id="layoutSidenav_content">
            <div class="main">
              <div class="container-fluid px-4 py-4">
                <router-view></router-view>
              </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.nav-link active {
    background-color: #007bff;
}
</style>
