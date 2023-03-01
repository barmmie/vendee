import ProductService from '../services/product.service';

const state = () => ({
    products: [],
    productsPaginatedData: null,
    product: null,
    isLoading: false,
    isCreating: false,
    createdData: null,
    isUpdating: false,
    updatedData: null,
    isDeleting: false,
    deletedData: null
})

const getters = {
    productList: state => state.products,
    productsPaginatedData: state => state.productsPaginatedData,
    product: state => state.product,
    isLoading: state => state.isLoading,
    isCreating: state => state.isCreating,
    isUpdating: state => state.isUpdating,
    createdData: state => state.createdData,
    updatedData: state => state.updatedData,

    isDeleting: state => state.isDeleting,
    deletedData: state => state.deletedData
};

const actions = {
    async fetchAllProducts({ commit }, query = null) {
        let page = 1;
        let search = '';
        if(query !== null){
            page = query?.page || 1;
            search = query?.search || '';
        }

        commit('setProductIsLoading', true);

        await ProductService.getAllProducts(page, search)
            .then(res => {
                const products = res.data.data;
                commit('setProducts', products);

                res.data.pagination = {
                    total: res.data.meta.total,
                    per_page: res.data.meta.per_page,
                    current_page: res.data.meta.current_page,
                    total_pages: res.data.meta.last_page
                };
                commit('setProductsPaginated', res.data);
                commit('setProductIsLoading', false);
            }).catch(err => {
                console.log('error', err);
                Promise.reject(err);
                commit('setProductIsLoading', false);
            });
    },

    async fetchDetailProduct({ commit }, id) {
        commit('setProductIsLoading', true);
        await ProductService.getProduct(id)
            .then(res => {
                commit('setProductDetail', res.data.data);
                commit('setProductIsLoading', false);
            }).catch(err => {
                console.log('error', err);
                Promise.reject(err);
                commit('setProductIsLoading', false);
            });
    },

    async storeProduct({ commit }, product) {
        commit('setProductIsCreating', true);
        await ProductService.storeProduct(product)
            .then(res => {
                commit('saveNewProducts', res.data.data);
                commit('setProductIsCreating', false);
            }).catch(err => {
                console.log('error', err);
                Promise.reject(err);
                commit('setProductIsCreating', false);
            });
    },

    async updateProduct({ commit }, product) {
        commit('setProductIsUpdating', true);
        commit('setProductIsUpdating', true);
        await ProductService.updateProduct(product)
            .then(res => {
                commit('saveUpdatedProduct', res.data.data);
                commit('setProductIsUpdating', false);
            }).catch(err => {
                Promise.reject(err);
                commit('setProductIsUpdating', false);
            });
    },

    async deleteProduct({ commit }, id) {
        commit('setProductIsDeleting', true);
        await ProductService.deleteProduct(id)
            .then(res => {
                commit('setDeleteProduct', id);
                commit('setProductIsDeleting', false);
            }).catch(err => {
                console.log('error', err);
                commit('setProductIsDeleting', false);
            });
    },

    updateProductInput({ commit }, e) {
        commit('setProductDetailInput', e);
    }
}

const mutations = {
    setProducts: (state, products) => {
        state.products = products
    },

    setProductsPaginated: (state, productsPaginatedData) => {
        state.productsPaginatedData = productsPaginatedData
    },

    setProductDetail: (state, product) => {
        state.product = product
    },

    setDeleteProduct: (state, id) => {
        state.productsPaginatedData.data.filter(x => x.id !== id);
    },

    setProductDetailInput: (state, e) => {
        let product = state.product;
        product[e.target.name] = e.target.value;
        state.product = product
    },

    saveNewProducts: (state, product) => {
        state.products.unshift(product)
        state.createdData = product;
    },

    saveUpdatedProduct: (state, product) => {
        state.products.unshift(product)
        state.updatedData = product;
    },

    setProductIsLoading(state, isLoading) {
        state.isLoading = isLoading
    },

    setProductIsCreating(state, isCreating) {
        state.isCreating = isCreating
    },

    setProductIsUpdating(state, isUpdating) {
        state.isUpdating = isUpdating
    },

    setProductIsDeleting(state, isDeleting) {
        state.isDeleting = isDeleting
    },

}

export const product  = {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}