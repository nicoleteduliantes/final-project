<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../../stores/auth";

const email = ref("");
const password = ref("");

const router = useRouter();
const auth = useAuthStore();

const login = () => {
    auth.loginStudent({
        email: email.value
    });

    router.push("/dashboard");
};
</script>