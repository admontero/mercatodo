<template>
    <div>
        <div v-if="!loading">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th scope="col">{{ $t('Name') }}</th>
                        <th scope="col">{{ $t('Code') }}</th>
                        <th scope="col">{{ $t('Category') }}</th>
                        <th scope="col">{{ $t('Price') }} ($)</th>
                        <th scope="col">{{ $t('Created') }}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <admin-product-list-item
                        v-for="product in products.data"
                        :key="product.id"
                        :product="product"
                    ></admin-product-list-item>
                </tbody>
            </table>
            <Bootstrap5Pagination
                class="mt-4"
                :data="products"
                @pagination-change-page="getProducts"
                :limit="2"
                align="center"
            />
        </div>
        <div class="d-flex justify-content-center align-items-center" v-else>
            <div class="spinner-border text-primary mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import AdminProductListItem from './AdminProductListItem.vue';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    export default {
        components: {
            Bootstrap5Pagination,
            AdminProductListItem,
        },
        data() {
            return {
                products: [],
                loading: false,
            }
        },
        mounted() {
            this.getProducts()
        },
        methods: {
            async getProducts(page = 1) {
                this.loading = true;
                await axios.get(`/api/admin/products?page=${page}`)
                    .then(res => {
                        this.products = res.data
                        this.loading = false
                    }).catch(err => {
                        console.log(err.response.data)
                    })
            }
        }
    }
</script>

<style>

</style>
