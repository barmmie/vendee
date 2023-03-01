import axios from 'axios';
import authHeader from './auth-header';

const API_URL = 'http://localhost:8080/api/';

class ProductService {

    getAllProducts(page, search) {
        return axios.get(API_URL + `product`, { headers: authHeader(), params: { page, search} });
    }

    purchaseProduct(purchaseData) {
        return axios.post(API_URL + `buy`, purchaseData, { headers: authHeader() });
    }

    storeProduct(productData) {
        return axios.post(API_URL + `product`, productData, { headers: authHeader() });
    }

    updateProduct(product) {
        return axios.put(API_URL + `product/${product.id}`, product, { headers: authHeader() });
    }

    deleteProduct(id) {
        return axios.delete(API_URL + `product/${id}`, { headers: authHeader() });
    }

    getProduct(id) {
        return axios.get(API_URL + `product/${id}`, { headers: authHeader() });
    }
}

export default new ProductService();
