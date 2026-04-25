import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
auth.loadUser();
