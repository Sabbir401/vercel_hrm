<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const department = ref([]);
const empp = ref([]);
const emp_img = ref([]);

const error = ref([]);
const fileSizeWarning = ref();
const leaveType = ref();
const leave = ref([]);
const leaveSummery = ref([]);

const form = ref({
    department: "",
    Employee_Id: "",
    From_Date: "",
    To_Date: "",
    Purpose: "",
    Leave_Type_Id: "",
    Status: "Pending",
    file: null,
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
        const [responseimg, responseleave, responseSummery] = await axios.all([
            axios.get(`/api/empimg/${id}`),
            axios.get(`/api/leave/${id}`),
            axios.get(`/api/leave-summery/${id}`),
        ]);
        emp_img.value = responseimg.data;
        leave.value = responseleave.data;
        leaveSummery.value = responseSummery.data;
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

const resetForm = () => {
    Object.keys(form.value).forEach((key) => {
        if (typeof form.value[key] === "string") {
            form.value[key] = "";
        } else {
            form.value[key] = null;
        }
    });
};

const getImage = (e) => {
    const file = e.target.files[0];
    if (file.size > 512000) {
        fileSizeWarning.value = true;
        e.target.value = null;
    } else {
        fileSizeWarning.value = false;
        form.file = e.target.files[0];
    }
};

const submit = async () => {
    const config = {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    };
    const formdata = new FormData();
    formdata.append("file", form.file);

    for (const key in form.value) {
        if (form.value.hasOwnProperty(key) && key !== "file") {
            formdata.append(key, form.value[key]);
        }
    }

    try {
        const response = await axios.post("/api/leave", formdata, config);
        if (response.data.success) {
            resetForm();
            alert("Apply Completed Successfully");
        }
    } catch (err) {
        error.value = err.response.data.errors;
    }
};

const totalDays = (start, end) => {
    const startDate = new Date(start);
    const endDate = new Date(end);
    const diffTime = Math.abs(endDate - startDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays;
};

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
                        <div class="col-lg-5">
                            <form class="forms-sample" @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-lg-6">
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
                                    <div class="col-lg-6">
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

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >From Date</label
                                            >
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="exampleInputEmail1"
                                                placeholder="Address"
                                                v-model="form.From_Date"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >To Date</label
                                            >
                                            <input
                                                type="date"
                                                class="form-control"
                                                id="exampleInputEmail1"
                                                placeholder="Address"
                                                v-model="form.To_Date"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Prupose/Reason</label
                                            >
                                            <textarea
                                                class="form-control"
                                                id="exampleFormControlTextarea1"
                                                rows="2"
                                                v-model="form.Purpose"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"
                                                >Leave Type</label
                                            >
                                            <select
                                                class="form-control"
                                                v-model="form.Leave_Type_Id"
                                                id=""
                                            >
                                                <option selected disabled>
                                                    select
                                                </option>
                                                <option
                                                    v-for="leave in leaveType"
                                                    :key="leave.id"
                                                    :value="leave.id"
                                                >
                                                    {{ leave.Name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="exampleInputEmail1" class=""
                                            >Documents
                                            <span
                                                >(If sick leave is more than 2
                                                days)</span
                                            >
                                            <div
                                                v-if="fileSizeWarning"
                                                class="text-danger"
                                            >
                                                File size exceeds 500 KB. Please
                                                choose a smaller file.
                                            </div></label
                                        >
                                        <input
                                            type="file"
                                            class="form-control"
                                            id="disabledTextInput"
                                            aria-describedby="emailHelp"
                                            @change="getImage"
                                        />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <input
                                            type="submit"
                                            class="btn-submit"
                                        />
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-lg-3 d-flex justify-content-center">
                            <div v-for="i in emp_img" :key="i.id">
                                <img
                                    :src="i.img_url"
                                    height="100%"
                                    width="100%"
                                    style="max-height: 200px; max-width: 200px"
                                />
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <table class="leave-apply">
                                <tr>
                                    <th>Leave Type</th>
                                    <th>Entitled</th>
                                    <th>Enjoyed</th>
                                    <th>Balance</th>
                                </tr>
                                <tr v-for="(info, typeName) in totalLeaveDays" :key="typeName">
                                    <th>{{ typeName  }}</th>
                                    <td>{{  info.maxDays  }}</td>
                                    <td>{{ info.totalDays }}</td>
                                    <td>{{ info.maxDays-info.totalDays }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="text-center">
                        <h1 class="mb-5">Leave Summary</h1>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>From Data</th>
                                    <th>To Date</th>
                                    <th>Total Days</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="ver-align"
                                    v-for="(l, index) in leave"
                                    :key="l.id"
                                >
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ l.From_Date }}</td>
                                    <td>{{ l.To_Date }}</td>
                                    <td>
                                        {{ l.daysBetween }}
                                    </td>
                                    <td>{{ l.Purpose }}</td>
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
    padding: 10px 3px;
    border: 1px solid black;
    text-align: center;
}
.leave-apply {
    width: 100%;
}
</style>
