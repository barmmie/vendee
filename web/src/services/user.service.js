import axios from 'axios';
import authHeader from './auth-header';

const API_URL = 'http://localhost:8080/api/';

class UserService {

  getUser(userId) {
    return axios.get(API_URL + `user/${userId}`, { headers: authHeader() });
  }

  depositMoney(depositData) {
    return axios.post(API_URL + `deposit`, depositData, { headers: authHeader() });
  }

  resetDeposit() {
    return axios.post(API_URL + `reset`, {}, { headers: authHeader() });
  }
}

export default new UserService();
