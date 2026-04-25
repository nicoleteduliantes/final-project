import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthStore = defineStore('auth', () => {
    const user = ref(null);
    const role = ref('student');
    const isAuthenticated = ref(false);

    // LOGIN FUNCTIONS
    function loginStudent(data,token) {
        user.value = data;
        role.value = 'student';
        isAuthenticated.value = true;

        localStorage.setItem('user', JSON.stringify(data));
        localStorage.setItem('role', 'student');
        localStorage.setItem('AUTH_TOKEN', token);
    }

    function loginOrg(data,token) {
        user.value = data;
        role.value = 'org';
        isAuthenticated.value = true;

        localStorage.setItem('user', JSON.stringify(data));
        localStorage.setItem('role', 'org');
        localStorage.setItem('AUTH_TOKEN', token);

    }

    function loginOsa(data, token) {
        user.value = data;
        role.value = 'osa';
        isAuthenticated.value = true;

        localStorage.setItem('user', JSON.stringify(data));
        localStorage.setItem('role', 'osa');
        localStorage.setItem('AUTH_TOKEN', token);

    }

    // LOAD SESSION (REFRESH FIX)

    function loadUser() {
        const savedUser = localStorage.getItem('user');
        const savedRole = localStorage.getItem('role');

        if (savedUser) {
            user.value = JSON.parse(savedUser);
            isAuthenticated.value = true;
        }

        if (savedRole) {
            role.value = savedRole;
        }
    }

    // LOGOUT

    function logout() {
        user.value = null;
        role.value = 'student';
        isAuthenticated.value = false;

        localStorage.removeItem('user');
        localStorage.removeItem('role');
    }

    // EXPORT

    return {
        user,
        role,
        isAuthenticated,
        loginStudent,
        loginOrg,
        loginOsa,
        loadUser,
        logout,
    };
});
