<script>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { useStore } from "vuex";

export default {
    setup() {
        const router = useRouter();
        const store = useStore();
        const form = reactive({
            email: "",
            password: "",
        });
        const err = ref("");

        const login = async () => {
            await axios.post("/api/login", form).then((res) => {
                if (res.data.success) {
                    // localStorage.setItem('token',res.data.data.token);
                    store.dispatch("setToken", res.data.data.token);
                    router.push({ name: "Home" });
                } else {
                    err.value = res.data.message;
                }
            });
        };

        return {
            form,
            login,
            err,
        };
    },
};
</script>
<template>
    <div class="text-center mb-5">
        <img src="/public/storage/uploads/1718087744_artisan.png" height="300px" width="350px" />
        <h1>Artisan Human Resource Information System</h1>
    </div>
    <div class="card col-lg-3 mx-auto">
        <div class="card-body px-5 py-5">
            <h3 class="card-title text-left mb-3">Login</h3>
            <form @submit.prevent="login">
                <div class="form-group">
                    <label>Username or email *</label>
                    <input
                        type="text"
                        class="form-control p_input"
                        v-model="form.email"
                    />
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input
                        type="password"
                        class="form-control p_input"
                        v-model="form.password"
                    />
                </div>
                <div
                    class="form-group d-flex align-items-center justify-content-between"
                >
                    <div class="form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" />
                            Remember me
                        </label>
                    </div>
                    <a href="#" class="forgot-pass">Forgot password</a>
                </div>
                <div class="text-center">
                    <button
                        type="submit"
                        class="btn btn-primary btn-block enter-btn"
                    >
                        Login
                    </button>
                </div>
                <p class="sign-up">
                    Don't have an Account?<a href="#"> Sign Up</a>
                </p>
            </form>
        </div>
        
    </div>
</template>

<style scoped>
    h1{
        font-size: 60px;
    }
</style>
