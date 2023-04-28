<template>
    <div>
        <div v-if="!loading">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th scope="col">{{ $t('Name') }}</th>
                        <th scope="col">{{ $t('Slug') }}</th>
                        <th scope="col">{{ $t('Created') }}</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <category-list-item
                        v-for="category in categories.data"
                        :key="category.id"
                        :category="category"
                    ></category-list-item>
                </tbody>
            </table>
            <Bootstrap5Pagination
                class="mt-4"
                :data="categories"
                @pagination-change-page="getCategories"
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
    import CategoryListItem from './CategoryListItem.vue';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';

    export default {
        components: {
            Bootstrap5Pagination,
            CategoryListItem,
        },
        data() {
            return {
                categories: [],
                loading: false,
            }
        },
        mounted() {
            this.getCategories()
        },
        methods: {
            async getCategories(page = 1) {
                this.loading = true;
                await axios.get(`/api/admin/categories?page=${page}`)
                    .then(res => {
                        this.categories = res.data
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
