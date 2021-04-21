import { ref } from "@vue/composition-api";
import { store } from "../../store/index";
import router from "../../router/router";
export function RegisterModule() {
    /** state */
    const registerForm = ref(null);
    const form = ref({
        email: null,
        password: null
    });
    const errors = ref({
        email: null,
        password: null
    });
    const loading = ref(false);

    /** methods **/
    function register() {
        if (registerForm.value.validate()) {
            loading.value = true;
            store
                .dispatch("user/register", form.value)
                .then(() => {
                    router.push({ name: "verify-email" });
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
        form,
        errors,
        loading,
        registerForm,
        register
    };
}
