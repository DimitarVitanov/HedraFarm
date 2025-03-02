<template>
    <div class="password_setup">
        <div
            class="d-flex justify-content-center align-items-center password_setup--container"
        >
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-5 mx-auto">
                        <div
                            class="password_setup--form bg-white shadow-lg rounded p-8 d-flex flex-column justify-content-center"
                        >
                            <h4 class="text-center">Set Your Password</h4>
                            <form @submit.prevent="submitPassword">
                                <div>
                                    <label class="form-label" for="password"
                                        >New Password</label
                                    >
                                    <br />
                                    <input
                                        type="password"
                                        v-model="form.password"
                                        id="password"
                                        class="form-control"
                                        required
                                    />
                                </div>

                                <div class="mt-3">
                                    <label
                                        class="form-label"
                                        for="password_confirmation"
                                        >Confirm Password</label
                                    >
                                    <br />
                                    <input
                                        class="form-control"
                                        type="password"
                                        v-model="form.password_confirmation"
                                        id="password_confirmation"
                                        required
                                    />
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button
                                        class="btn btn-danger w-80 mt-2"
                                        type="submit"
                                        :disabled="loading"
                                    >
                                        {{
                                            loading
                                                ? "Saving..."
                                                : "Set Password"
                                        }}
                                    </button>
                                </div>
                            </form>

                            <p v-if="error" class="text-center error">{{ error }}</p>
                            <p v-if="success" class="text-center success">{{ success }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            form: {
                password: "",
                password_confirmation: "",
            },
            loading: false,
            error: null,
            success: null,
        };
    },
    methods: {
        async submitPassword() {
            this.loading = true;
            this.error = null;
            this.success = null;

            try {
                const response = await axios.post("/password/set", this.form);
                this.success = response.data.message;
                // Redirect to dashboard after success
                setTimeout(() => {
                    this.$inertia.visit("/dashboard");
                }, 2000);
            } catch (error) {
                if (error.response && error.response.data.errors) {
                    this.error = Object.values(error.response.data.errors)
                        .flat()
                        .join(", ");
                } else {
                    this.error = "Something went wrong. Please try again.";
                }
            } finally {
                this.loading = false;
            }
        },
    },
};
</script>

<style scoped>
.password_setup {
    height: 100vh;
    width: "100%";
    background-color: "#F8FAFB";
}
.password_setup--container {
    height: 100vh;
    width: 100%;
}
.password_setup--form {
    min-height: 400px;
}

.error {
    color: red;
    margin-top: 10px;
}

.success {
    color: green;
    margin-top: 10px;
}
</style>
