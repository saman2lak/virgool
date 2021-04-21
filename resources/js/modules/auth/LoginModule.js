import { ref } from "@vue/composition-api";
import { store } from "../../store/index";
import router from "../../router/router";

export function LoginModule() {
    const LoginForm = ref(null);
    const form = ref({
        email: null,
        password: null,
        remember: true
    });

    const errors = ref({
        email: null,
        password: null
    });
    const loading = ref(false);

    function login() {
        if (LoginForm.value.validate()) {
            loading.value = true;
            store
                .dispatch("user/login", form.value)
                .then(() => {
                    router.push({ name: "home" });
                })
                .catch(error => {
                    errors.value.email = error.response.data.errors.email[0];
                    errors.value.password =
                        error.response.data.errors.password[0];
                })
                .finally(() => (loading.value = false));
        }
    }

    return {
        login,
        form,
        errors,
        LoginForm,
        loading
    };
}
