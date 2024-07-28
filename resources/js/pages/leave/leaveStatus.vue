<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const department = ref([]);
const empp = ref([]);
const emp_img = ref([]);

const error = ref([]);
const leaveType = ref();
const leave = ref([]);
const selectedType = ref("");
const selectedStatus = ref("Pending");
const leaveSummery = ref([]);

const form = ref({
    department: "",
    Employee_Id: "",
    Status: "",
});

const getData = async () => {
    try {
        const [responsedept, responsetype] = await axios.all([
            axios.get("/api/department"),
            axios.get("/api/leave-type"),
        ]);
        department.value = responsedept.data;
        leaveType.value = responsetype.data;
    } catch (err) {
        error.value = err.message || "Error fetching data";
    } finally {
    }
};

const leaveApproved = async (id) => {
    form.value.Status = 'Approved';
    try {
        const response = await axios.put(`/api/leave/${id}`, form.value);
        empp.value = response.data;
        alert('Leave Approved');
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const leaveReject = async (id) => {
    form.value.Status = 'Rejected';
    try {
        const response = await axios.put(`/api/leave/${id}`, form.value);
        empp.value = response.data;
        alert('Leave Rejected');
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const filteredData = computed(() => {
    return leave.value.filter((item) => {
        return (
            (selectedType.value === "" ||
                item.leave_type.Name === selectedType.value) &&
            (selectedStatus.value === "" ||
                item.Status === selectedStatus.value)
        );
    });
});


const getEmployee = async (id) => {
    try {
        const response = await axios.get(`/api/emp/${id}`);
        empp.value = response.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const getLeave = async (id) => {
    try {
        const response = await axios.get(`/api/leave-summery/${id}`);
        leaveSummery.value = response.data;
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const getEmployeeImg = async (id) => {
    try {
        const [responseimg, responseleave] = await axios.all([
            axios.get(`/api/empimg/${id}`),
            axios.get(`/api/leave/${id}`),
        ]);
        emp_img.value = responseimg.data;
        leave.value = responseleave.data;
        getLeave(id);
    } catch (error) {
        console.error("Error updating store:", error);
    }
};

const totalLeaveDays = computed(() => {
  const totals = {};
  leave.value.forEach(l => {
    const typeName = l.leave_type.Name;
    if (!totals[typeName]) {
        totals[typeName] = {
        totalDays: 0,
        maxDays: l.leave_type.Max_Days,
      };
    }
    totals[typeName].totalDays += l.daysBetween;
  });
  return totals;
});


const openAttachment = (url) => {
    window.open(url, "_blank");
};

onMounted(() => getData());
</script>

<template>
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Leave Apllication Form</h1>
                    </div>
                    <div class="d-flex">
                        <div class="col-lg-4 px-2">
                            <form class="forms-sample" @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Department</label
                                            >
                                            <select
                                                class="form-control"
                                                name="status"
                                                id=""
                                                v-model="form.department"
                                                @change="
                                                    getEmployee(form.department)
                                                "
                                            >
                                                <option selected disabled>
                                                    select
                                                </option>
                                                <option
                                                    v-for="dept in department"
                                                    :key="dept.id"
                                                    :value="dept.id"
                                                >
                                                    {{ dept.Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Employee Name</label
                                            >
                                            <select
                                                class="form-control"
                                                name="status"
                                                id=""
                                                v-model="form.Employee_Id"
                                                @change="
                                                    getEmployeeImg(
                                                        form.Employee_Id
                                                    )
                                                "
                                            >
                                                <option
                                                    v-for="e in empp"
                                                    :key="e.id"
                                                    :value="e.id"
                                                >
                                                    {{ e.Full_Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-4 px-2">
                            <table class="leave-apply">
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Entitled</th>
                                    <th>Enjoyed</th>
                                    <th>Balance </th>
                                </tr>
                                <tr v-for="(info, typeName) in totalLeaveDays" :key="typeName">
                                    <th>{{ typeName  }}</th>
                                    <td>{{  info.maxDays  }}</td>
                                    <td>{{ info.totalDays }}</td>
                                    <td>{{ info.maxDays-info.totalDays }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-lg-4 px-2">
                            <div class="d-flex justify-content-center">
                                <div v-for="i in emp_img" :key="i.id">
                                    <img
                                        :src="i.img_url"
                                        height="100%"
                                        width="100%"
                                        style="
                                            max-height: 110px;
                                            max-width: 110px;
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Leave Summary</h1>
                    </div>

                    <div class="row d-flex justify-content-end">
                        <div class="col-lg-2">
                            <label for="exampleInputEmail1">Leave Type</label>
                            <select v-model="selectedType" class="form-control">
                                <option value="">All Type</option>
                                <option value="Casual">Casual</option>
                                <option value="Sick">Sick</option>
                                <option value="Enarned">Enarned</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="exampleInputEmail1">Leave Status</label>
                            <select
                                v-model="selectedStatus"
                                class="form-control"
                            >
                                <option value="">All Status</option>
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>From Data</th>
                                    <th>To Date</th>
                                    <th>Total Days</th>
                                    <th>Leave Type</th>
                                    <th>Status</th>
                                    <th>Documents</th>
                                    <th>Operation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="ver-align"
                                    v-for="(l, index) in filteredData"
                                    :key="l.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ l.From_Date }}</td>
                                    <td>{{ l.To_Date }}</td>
                                    <td>
                                        {{ l.daysBetween }}
                                    </td>
                                    <td>{{ l.leave_type.Name }}</td>
                                    <td>{{ l.Status }}</td>
                                    <td>
                                        <button
                                            v-if="l.Attachment_Url"
                                            @click="
                                                openAttachment(l.Attachment_Url)
                                            "
                                            class="custom-btn btn-15 mx-2"
                                        >
                                            <i
                                                class="fa-solid fa-file-arrow-down"
                                            ></i>
                                        </button>
                                        <div v-else></div>
                                    </td>
                                    <td>
                                        <button class="custom-btn btn-13 mx-1 px-1" @click="leaveApproved(l.id)">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="custom-btn btn-12 mx-1" @click="leaveReject(l.id)">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.leave-apply th,
.leave-apply td {
    border: 1px solid black;
    text-align: center;
}
.leave-apply {
    width: 100%;
}
</style>
