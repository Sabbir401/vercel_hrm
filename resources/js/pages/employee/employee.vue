<script setup>
import { ref, onMounted } from "vue";
import { useRoute, RouterView } from "vue-router";
import { useStore } from "vuex";

const store = useStore();
const route = useRoute();
const empId = parseInt(route.params.id);

const error = ref(null);
const bloods = ref([]);
const religions = ref([]);
const companies = ref([]);
const phones = ref([]);
const empEdit = ref([]);

const getData = async () => {
    try {
        if (empId) {
            editHandler();
        }
    } catch (err) {
        error.value = err.message || "Error fetching data";
    }
};

const editHandler = async () => {
    try {
        const response = await axios.get(`/api/employee/${empId}/edit`);
        empEdit.value = response.data;
    } catch (err) {
        console.error("Error fetching store data for editing:", err);
    }
};

onMounted(() => getData());
</script>

<template>
    <div v-if="empId">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    :to="{
                        name: 'Employeeid',
                        params: { id: empId },
                        query: { response: empEdit },
                    }"
                    >Employee Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    :to="{ name: 'Personalid', params: { id: empId } }"
                    >Personal Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    :to="{ name: 'Professionalid', params: { id: empId } }"
                    >Professional Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    :to="{ name: 'Officialid', params: { id: empId } }"
                    >Official Information</router-link
                >
            </li>
        </ul>
        <RouterView />
    </div>
    <div v-else>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    to="/employee"
                    >Employee Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    to="/personal"
                    >Personal Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    to="/professional"
                    >Professional Information</router-link
                >
            </li>
            <li class="nav-item">
                <router-link
                    class="nav-link"
                    active-class="active"
                    to="/official"
                    >Official Information</router-link
                >
            </li>
        </ul>
        <RouterView />
    </div>
</template>

<style>
.nav-link:hover{
    color: rgb(44, 103, 116)
}
</style>

