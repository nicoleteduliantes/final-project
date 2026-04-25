import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const role = ref('student');
    const isAuthenticated = ref(false);

    function setUser(data, userRole) {
        user.value = data;
        role.value = userRole;
        isAuthenticated.value = true;

        localStorage.setItem('user', JSON.stringify(data));
        localStorage.setItem('role', userRole);
    }

    function loadUser() {
        const savedUser = localStorage.getItem('user');
        const savedRole = localStorage.getItem('role');

        if (savedUser) user.value = JSON.parse(savedUser);
        if (savedRole) role.value = savedRole;
    }

    function logout() {
        user.value = null;
        role.value = 'student';
        isAuthenticated.value = false;

        localStorage.clear();
    }

    return {
        user,
        role,
        isAuthenticated,
        setUser,
        loadUser,
        logout,
    };
});
