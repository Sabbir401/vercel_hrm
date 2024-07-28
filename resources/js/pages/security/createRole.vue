<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";

const permissions = ref([]);
const empp = ref([]);
const emp_img = ref([]);
const attendences = ref([]);

const error = ref([]);

const form = ref({
    department: "",
    Employee_Id: "",
    From_Date: "",
    To_Date: "",
});

const getData = async () => {
    try {
        const response = await axios.get("/api/permission");
        permissions.value = response.data;
        console.log(permissions.value);
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
    }
};

const getEmployee = async (id) => {
    try {
        const response = await axios.get(`/api/emp/${id}`);
        empp.value = response.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const getEmployeeImg = async (id) => {
    try {
        const responseimg = await axios.get(`/api/empimg/${id}`);
        emp_img.value = responseimg.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const resetForm = () => {
    Object.keys(form.value).forEach((key) => {
        if (typeof form.value[key] === "string") {
            form.value[key] = "";
        }
    });
};

const submit = async () => {
    try {
        const response = await axios.post(`/api/attendence/edit`, form.value);
        attendences.value = response.data;
        if (response.data.success) {
            resetForm();
            alert("Successfully Inserted Attendence");
        }
    } catch (error) {
        console.error("Error submitting Attendence:", error);
    }
};

onMounted(() => getData());
</script>

<template>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Role Management</h1>
                    </div>
                    <div class="d-flex">
                        <div class="col-lg-8">
                            <form class="forms-sample" @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Role Name</label
                                            >
                                            <input
                                                type="text"
                                                class="form-control"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Permissions</label
                                            >
                                            <div
                                                class="d-flex align-items-center"
                                            >
                                                <div
                                                class="d-flex align-items-center"
                                                    v-for="permission in permissions"
                                                    :key="permission.id"
                                                >
                                                    <input
                                                        type="checkbox"
                                                        v-model="inactive"
                                                    />
                                                    <label class="px-2 pt-0">{{
                                                        permission.name
                                                    }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-6">
                                        <input
                                            type="submit"
                                            class="btn btn-success pl-2 form-control"
                                        />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="card-title">Attendence table</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(attend, index) in attendences"
                                    :key="attend.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ attend.Date }}</td>
                                    <td>{{ attend.Time_In }}</td>
                                    <td>{{ attend.Time_Out }}</td>
                                    <td>{{ attend.Status }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
