import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            token: "",
            loading: false,
            isAuth: false,
            counter: 0
        }
    },
    actions: {
        isLogin(token, user) {
            this.isAuth = true;
            this.token = token;
            this.user = user
        },
        isLogout() {
            this.isAuth = false;
            this.token = "";
            this.user = "";
            localStorage.removeItem('auth')
        }
    },
    persist: true,
})