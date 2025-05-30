import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: () => {
        return {
            user: null,
            token: "",
            loading: false,
            isAuth: false,
            counter: 0,
            final: false,
            fs:false
        }
    },
    actions: {
        isLogin(token, user) {
            this.isAuth = true;
            this.token = token;
            this.user = user
            this.final = user.final;
            this.fs = user.finalSiswa;
        },
        isLogout() {
            this.isAuth = false;
            this.token = "";
            this.user = "";
            localStorage.removeItem('auth')
        },
    },
    persist: true,
})
