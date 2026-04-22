import { defineStore } from "pinia";
import { ref } from "vue";

export const useAuthStore = defineStore("auth", () => {
  const user = ref(null);
  const role = ref("student"); // 👈 IMPORTANT DEFAULT
  const isAuthenticated = ref(false);

  const loginStudent = (data) => {
    user.value = data;
    role.value = "student";
    isAuthenticated.value = true;
  };

  const loginOrg = (data) => {
    user.value = data;
    role.value = "org";
    isAuthenticated.value = true;
  };

  const loginOsa = (data) => {
    user.value = data;
    role.value = "osa";
    isAuthenticated.value = true;
  };

  const logout = () => {
    user.value = null;
    role.value = "student"; // reset safely
    isAuthenticated.value = false;
  };

  return {
    user,
    role,
    isAuthenticated,
    loginStudent,
    loginOrg,
    loginOsa,
    logout
  };
});