<template>
    <div class="px-lg-4">
        <div class="row g-3">
            <div class="col-md-3">
                <h4 class="py-1">{{ $t('Products') }}</h4>

                <hr class="d-none d-md-block py-1">

                <input
                    class="form-control mb-3"
                    type="search"
                    :placeholder="$t('Search by name')"
                    v-model="filters.name"
                >

                <multiselect
                    class="mb-3"
                    v-model="selectedCategory"
                    :options="categories"
                    label="name"
                    track-by="id"
                    :placeholder="$t('Search by category')"
                    @update:modelValue="onChangeCategory"
                ></multiselect>

                <div v-if="sliderEnabled">
                    <p class="text-muted">
                        <small class="mb-3">{{ $t('By price') }}:</small>
                    </p>

                    <Slider
                        class="mb-4"
                        :min="this.minPrice"
                        :max="this.maxPrice"
                        showTooltip="drag"
                        @update="sliderUpdate(value)"
                        v-model="value"
                    />
                </div>

                <div class="d-grid g-2">
                    <button
                        class="btn btn-info"
                        type="button"
                        @click="resetFilters"
                    >
                        {{ $t('Reset Search') }}
                    </button>
                </div>
            </div>
            <div class="col-md-9">
                <div>
                    <div class="d-md-flex justify-content-between align-items-end">
                        <div>
                            <p v-if="total !== null">
                                <span class="text-primary">{{ total }}</span>
                                {{ $t('Results') }}
                            </p>
                        </div>
                        <div>
                            <select class="form-select mb-3" v-model="filters.order">
                                <option value="" selected>{{ $t('Newest') }}</option>
                                <option value="orderByOldest">{{ $t('Oldest') }}</option>
                                <option value="orderByPriceDESC">{{ $t('Highest price first') }}</option>
                                <option value="orderByPriceASC">{{ $t('Lowest price first') }}</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="row">
                            <customer-product-list-item
                                v-for="product in products"
                                :key="product.id"
                                :product="product"
                            ></customer-product-list-item>
                            <template v-if="loading">
                                <customer-product-list-item-skeleton
                                    v-for="i in 15"
                                    :key="'ske-' + i"
                                ></customer-product-list-item-skeleton>
                            </template>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" v-if="moreExists">
                            <button class="btn btn-dark" @click="loadMore">{{ $t('Load more') }}...</button>
                        </div>
                        <div class="d-flex justify-content-center align-items-center" v-else-if="total > 0 && !moreExists && !loading">
                            <p>{{ $t('No more products available') }}.</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center align-items-center" v-if="total === 0 && !loading">
                        <p v-if="queryString">
                            {{ $t('There are no products matching your search') }}.
                        </p>
                        <p v-else>
                            {{ $t('There are no products to display') }}.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CustomerProductListItem from './CustomerProductListItem.vue'
    import CustomerProductListItemSkeleton from './CustomerProductListItemSkeleton.vue'
    import Slider from '@vueform/slider'
    import Multiselect from 'vue-multiselect'
    import _ from 'lodash'

    export default {
        components: {
            CustomerProductListItem,
            CustomerProductListItemSkeleton,
            Multiselect,
            Slider,
        },
        data() {
            return {
                products: [],
                categories: [],
                page: 1,
                total: null,
                loading: false,
                filters: {
                    name: '',
                    order: '',
                    categoryId: '',
                },
                selectedCategory: null,
                moreExists: false,
                value: null,
                minPrice: null,
                maxPrice: null,
            }
        },
        created() {
            this.getCategories().then(() => {
                this.categories = this.categories
            })

            this.getPriceRange()
        },
        mounted() {
            this.getProducts()
        },
        methods: {
            async getProducts() {
                this.loading = true
                await axios.get(`/api/products?page=${this.page}&${this.queryString}`)
                    .then(res => {
                        this.products = [...this.products, ...res.data.data]
                        this.total = res.data.meta.total

                        if (res.data.meta.last_page > res.data.meta.current_page)
                            this.moreExists = true

                        this.loading = false
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
            async getPriceRange() {
                await axios.get('/api/products/price-range')
                    .then(res => {
                        if (res.data.min_price !== null && res.data.max_price !== null) {
                            this.minPrice = parseInt(res.data.min_price)
                            this.maxPrice = parseInt(res.data.max_price)
                            this.value = [this.minPrice, this.maxPrice]
                        }
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            },
            sliderUpdate(value) {
                this.filters.minPrice = value[0]
                this.filters.maxPrice = value[1]
            },
            onChangeCategory(value) {
                if (!value) {
                    this.selectedCategory = null
                    this.filters.categoryId = null
                    return
                }

                this.filters.categoryId = value.id
            },
            debouncedGetProducts: _.debounce(function () {
                this.loading = true
                this.page = 1
                this.products = []
                this.moreExists = false
                this.getProducts()
            }, 500),
            loadMore() {
                this.page++
                this.moreExists = false
                this.getProducts()
            },
            resetFilters() {
                this.filters = {
                    name: '',
                    order: '',
                    categoryId: '',
                };

                this.selectedCategory = null;

                this.getPriceRange()

                this.page = 1
                this.products = []
                this.moreExists = false
                this.loading = true
            },
        },
        computed: {
            queryString() {
                this.loading = true
                return Object.keys(this.filters)
                    .map(key => {
                        if (this.filters[key] || this.filters[key] === 0) {
                            return encodeURIComponent(key) + '=' + encodeURIComponent(this.filters[key])
                        }
                    })
                    .filter(param => param)
                    .join('&');
            },
            sliderEnabled() {
                if (this.minPrice !== null && this.maxPrice !== null) {
                    return true;
                }

                return false;
            }
        },
        watch: {
            filters: {
                handler: function (){
                    this.debouncedGetProducts()
                },
                deep: true
            },
        }
    }
</script>

<style src="@vueform/slider/themes/default.css"></style>
