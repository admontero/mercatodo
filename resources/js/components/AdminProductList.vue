<template>
    <div>
        <div class="d-flex justify-content-between my-2">
            <div></div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <IconFileArrowLeft :size="16" stroke-width="2" />
                    {{ $t('Import') }} Excel
                </button>
                <button class="btn btn-sm btn-outline-info" @click.prevent="exportExcel">
                    <IconFileArrowRight :size="16" stroke-width="2" />
                    {{ $t('Export') }} Excel
                </button>
            </div>
        </div>
        <div v-if="!loading">
            <table class="table table-hover">
                <thead>
                    <tr class="table-light">
                        <th scope="col">{{ $t('Name') }}</th>
                        <th scope="col">{{ $t('Code') }}</th>
                        <th scope="col">{{ $t('Price') }} ($)</th>
                        <th scope="col">{{ $t('Stock') }}</th>
                        <th scope="col">{{ $t('Status') }}</th>
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

        <!-- Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form @submit.prevent="importExcel" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="importModalLabel">{{ $t('Import') }} Excel</h1>
                            <button ref="Close" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formFile" class="form-label">{{ $t('File') }}</label>
                                <input
                                    :class="{'form-control' : !this.errors?.file, 'form-control is-invalid' : this.errors?.file }"
                                    accept=".csv"
                                    type="file"
                                    aria-label="File"
                                    name="file"
                                    id="file"
                                    ref="inputFile"
                                    @change="onFileChange($event)"
                                >
                                <span class="text-danger small" role="alert" v-if="this.errors?.file">
                                    <strong>{{ this.errors.file[0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">{{ $t('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ $t('Import') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
    </div>
</template>

<script>
    import AdminProductListItem from './AdminProductListItem.vue';
    import { Bootstrap5Pagination } from 'laravel-vue-pagination';
    import { IconFileArrowRight, IconFileArrowLeft } from '@tabler/icons-vue';
    import { trans } from 'laravel-vue-i18n';
    import { useToast } from "vue-toastification";

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        components: {
            Bootstrap5Pagination,
            AdminProductListItem,
            IconFileArrowRight,
            IconFileArrowLeft,
        },
        data() {
            return {
                products: [],
                loading: false,
                file: null,
                errors: [],
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
            },
            exportExcel() {
                axios.get('/api/admin/products/export')
                    .then(res => {
                        this.toast.success(trans(res.data.message), {
                            position: "bottom-left",
                            timeout: 3000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: false,
                            hideProgressBar: false,
                            closeButton: "button",
                            icon: true,
                            rtl: false
                        });
                    }).catch(err => {
                        console.log(err.response.data)
                        this.toast.error(trans(err.response.data.message), {
                            position: "bottom-left",
                            timeout: 3000,
                            closeOnClick: true,
                            pauseOnFocusLoss: true,
                            pauseOnHover: true,
                            draggable: true,
                            draggablePercent: 0.6,
                            showCloseButtonOnHover: false,
                            hideProgressBar: false,
                            closeButton: "button",
                            icon: true,
                            rtl: false
                        });
                    })
            },
            importExcel() {
                const formData = new FormData();
                if (this.file) formData.append('file', this.file);

                axios.post('/api/admin/products/import', formData).then(res => {
                    this.toast.success(trans(res.data.message), {
                        position: "bottom-left",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: false,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });

                    this.closeModal()
                    this.$refs.Close.click();
                }).catch(err => {
                    this.errors = err.response.data.errors;
                })
            },
            onFileChange(event) {
                var input = event.target;
                if (input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                    }
                    this.file = event.target.files[0];
                    reader.readAsDataURL(input.files[0]);

                    return ;
                }
            },
            closeModal() {
                this.file = null
                this.$refs.inputFile.value = null
                this.errors = [];
            }
        }
    }
</script>

<style>

</style>
