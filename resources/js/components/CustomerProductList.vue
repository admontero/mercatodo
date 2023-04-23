<template>
    <div class="px-lg-4">
        <div class="row g-4">
            <div class="col-md-3">
                <h4 class="my-2">Productos</h4>

                <hr class="d-none d-md-block py-1">

                <input
                    class="form-control mb-3"
                    type="search"
                    placeholder="Búsqueda por nombre"
                    v-model="filters.name"
                    v-on:keyup.enter="getProducts"
                >

                <select class="form-select mb-3" v-model="filters.order">
                    <option value="orderByLatest">Más recientes</option>
                    <option value="orderByOldest">Más antiguos</option>
                    <option value="orderByPriceDESC">Mayor precio primero</option>
                    <option value="orderByPriceASC">Menor precio primero</option>
                </select>

                <model-select
                    class="mb-3"
                    :options="categories"
                    v-model="category"
                    placeholder="Búsqueda por categoría"
                ></model-select>

                <div class="d-grid g-2">
                    <button
                        class="btn btn-info"
                        type="button"
                        v-on:click="resetFilters"
                    >
                        Reiniciar Búsqueda
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <div v-if="!loading">
                    <div v-if="total">
                        <div class="d-md-flex justify-content-between align-items-end">
                            <p>
                                <span class="text-primary">{{ total }}</span>
                                Resultados
                            </p>
                            <Bootstrap5Pagination
                                :data="products"
                                @pagination-change-page="getProducts"
                                :limit="1"
                                align="right"
                            />
                        </div>
                        <div class="row">
                            <customer-product-list-item
                                v-for="product in products.data"
                                :key="product.id"
                                :product="product"
                            ></customer-product-list-item>
                        </div>
                        <Bootstrap5Pagination
                            :data="products"
                            @pagination-change-page="getProducts"
                            :limit="1"
                            align="right"
                        />
                    </div>
                    <div class="d-flex justify-content-center align-items-center" v-else>
                        <p class="fw-semibold" v-if="queryString">
                            No hay productos que coincidan con su búsqueda.
                        </p>
                        <p class="fw-semibold" v-else>
                            No hay productos para mostrar.
                        </p>
                    </div>
                </div>
                <div class="d-flex justify-content-center align-items-center" v-else>
                    <div class="spinner-border text-primary mt-4" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerProductListItem from './CustomerProductListItem.vue';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';
    import { ModelSelect } from 'vue-search-select';

    export default {
        components: {
            Bootstrap5Pagination,
            CustomerProductListItem,
            ModelSelect,
        },
        data() {
            return {
                products: [],
                categories: [],
                total: 0,
                loading: false,
                filters: {
                    name: '',
                    order: 'orderByLatest',
                    categoryId: ''
                },
                category: {
                    value: '',
                    text: '',
                }
            }
        },
        created() {
            this.getCategories().then(() => {
                this.categories = this.categories.map(c => ({ value: c.id, text: c.name }))
            })
        },
        mounted() {
            this.getProducts()
        },
        methods: {
            async getProducts(page = 1) {
                this.loading = true;

                await axios.get(`/api/products?page=${page}&${this.queryString}`)
                    .then(res => {
                        this.products = res.data
                        this.loading = false
                        this.total = res.data.meta.total
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            },
            async getCategories() {
                await axios.get('/api/products/categories')
                    .then(res => {
                        this.categories = res.data
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            },
            resetFilters() {
                this.filters = {
                    name: '',
                    order: 'orderByLatest',
                    categoryId: '',
                };

                this.category = {
                    value: '',
                    text: '',
                };
            },
        },
        computed: {
            queryString() {
                return Object.keys(this.filters)
                    .map(key => {
                        if (this.filters[key]) {
                            return encodeURIComponent(key) + '=' + encodeURIComponent(this.filters[key])
                        }
                    })
                    .filter(param => param)
                    .join('&');
            }
        },
        watch: {
            filters: {
                handler(){
                    this.getProducts()
                },
                deep: true
            },
            category(newCat) {
                this.filters.categoryId = newCat.value;
            }
        }
    }
</script>

<style>

</style>
