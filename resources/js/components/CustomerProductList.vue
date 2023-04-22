<template>
    <div class="px-lg-4">
        <div class="row g-4">
            <div class="col-md-3">
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
                        Reset
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <div v-if="!loading">
                    <div v-if="this.total">
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
                            :limit="3"
                            align="center"
                        />
                    </div>
                    <div class="d-flex justify-content-center align-items-center" v-else>
                        <p class="fw-semibold" v-if="queryString">
                            Actualmente no hay productos que coincidan con su búsqueda.
                        </p>
                        <p class="fw-semibold" v-else>
                            Actualmente no hay productos para mostrar.
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
                handler(val){
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
    .ui.fluid.search {
        max-height: 2.5rem !important;
    }

    .ui.fluid.search > .search {
        display: block !important;
        width: 100% !important;
        padding: .375rem 2.25rem .375rem .75rem !important;
        -moz-padding-start: calc(0.75rem - 3px) !important;
        font-size: 1rem !important;
        font-weight: 400 !important;
        line-height: 1.5 !important;
        color: #333 !important;
        background-color: #fff !important;
        background-repeat: no-repeat !important;
        background-position: right .75rem center !important;
        background-size: 16px 12px !important;
        border: 1px solid #ced4da !important;
        border-radius: .375rem !important;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out !important;
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
    }

    .ui.fluid.search > .search:focus {
        color: #333 !important;
        background-color: #fff !important;
        border-color: #f4aa90 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 0.25rem rgba(233,84,32,.25) !important;
    }

    .ui.fluid.search > .text.default {
        display: block !important;
        color: #919397 !important;
        background-color: #fff !important;
        font-size: 1rem !important;
        overflow: hidden !important;
        white-space: nowrap !important;
    }

    .ui.fluid.search > .menu.visible {
        margin-top: .25rem !important;
    }
</style>
