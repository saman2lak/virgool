import { computed, ref } from "@vue/composition-api";
import axios from "axios";
import uploadBase64 from "../../modules/file/uploadBase64";
import { store } from "../../store/index";

export default function profile() {
    //
    const form = ref(null);
    const user = ref(null);
    const url = ref(process.env.MIX_APP_URL + "/");
    const editing = ref({
        username: false,
        email: false
    });
    const old = ref({
        username: null,
        email: null
    });
    const errors = ref({
        username: null,
        email: null
    });

    const FullUserName = computed(() => {
        return url.value + user.value.username;
    });

    const cancel = data => {
        user.value[data] = old.value[data];
        editing.value[data] = false;
    };
    const changeEdit = data => {
        old.value[data] = user.value[data];
        editing.value[data] = !editing.value[data];
    };
    const uploadProfile = event => {
        user.value.profile_name = event.target.files[0].name;
        user.value.profile_src = user.value.profile = uploadBase64(event);
    };

    const getUser = () => {
        axios.get("/api/me").then(res => {
            user.value = res.data;
            old.value.username = res.data.username;
            old.value.email = res.data.email;
        });
    };

    const update = () => {
        if (form.value.validate()) {
            axios
                .patch("/api/profile", user.value)
                .then(res => {
                    store.dispatch("user/setName", res.data.name);
                    errors.value = {
                        email: null,
                        username: null
                    };
                    editing.value = {
                        email: null,
                        username: null
                    };
                })
                .catch(err => {
                    for (const index in err.response.data.errors) {
                        errors.value[index] =
                            err.response.data.errors[index][0];
                        editing.value[index] = true;
                    }
                });
        }
    };

    getUser();
    return {
        form,
        getUser,
        user,
        editing,
        old,
        errors,
        url,
        FullUserName,
        cancel,
        changeEdit,
        uploadProfile,
        update
    };
}
