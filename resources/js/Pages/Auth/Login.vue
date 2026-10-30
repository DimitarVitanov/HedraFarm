<script setup>
import BreezeButton from '@/Components/PrimaryButton.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/GuestLayout.vue';
import BreezeInput from '@/Components/TextInput.vue';
import BreezeLabel from '@/Components/InputLabel.vue';
import BreezeValidationErrors from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head>
            <title>Најава - Хедра Фарм</title>
            <meta name="description" content="Најавете се на вашата сметка на Хедра Фарм." />
            <meta name="robots" content="noindex, nofollow" />
        </Head>

        <BreezeValidationErrors class="mb-4" />

        <div v-if="status" class="ha-success-text" style="margin-bottom: 1rem">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="ha-form">
            <div class="ha-field">
                <BreezeLabel for="email" value="Email" />
                <BreezeInput id="email" type="email" v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <div class="ha-field">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="ha-login-row">
                <label class="ha-remember">
                    <BreezeCheckbox name="remember" v-model:checked="form.remember" />
                    <span>Remember me</span>
                </label>

                <Link v-if="canResetPassword" :href="route('password.request')" class="ha-link">
                    Forgot your password?
                </Link>
            </div>

            <!-- If added the sign in with google button will appear -->
             <!--
                <a :href="route('auth.google')">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" style="margin-left: 3em;height: 40px;border-radius: 12px">
                </a>
             -->

            <BreezeButton class="ha-btn--block" :disabled="form.processing">
                Log in
            </BreezeButton>
        </form>
    </BreezeGuestLayout>
</template>
