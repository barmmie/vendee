import axios from 'axios';
import authHeader from "./auth-header";

const API_URL = 'http://localhost:8080/api/';

class AuthService {
  login(user) {
    return axios
      .post(API_URL + 'login', user)
      .then(response => {
        if (response.data.token) {
          localStorage.setItem('user', JSON.stringify(response.data));
        }

        return response.data;
      });
  }

  logout() {
      return axios
          .delete(API_URL + 'logout', { headers: authHeader() })
          .then(response => {
              localStorage.removeItem('user');
          }, err => {
              localStorage.removeItem('user');
          });
  }

    forceLogout(user) {
        return axios
            .delete(API_URL + 'logout/all', { auth: user })
            .then(response => {});
    }

  register(user) {
    return axios.post(API_URL + 'user', user);
  }
}

export default new AuthService();
