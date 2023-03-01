import axios from "axios";

export function setup() {
    axios.interceptors.response.use((response) => {
        return response
    }, function(error) {
        if (error.response.status) {
            switch (error.response.status) {
                case 401:
                    localStorage.removeItem('user')
                    window.location.href = "login";
                    break;
            }
            return Promise.reject(error.response);
        }
        return Promise.reject(error);
    });
}