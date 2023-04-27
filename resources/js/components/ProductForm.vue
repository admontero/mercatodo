<template>
    <form class="my-4" @submit.prevent="submit" enctype="multipart/form-data" v-if="!loading">
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="name" class="form-label">{{ $t('Name') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.name, 'form-control is-invalid' : this.errors?.name }"
                    type="text"
                    aria-label="Name"
                    name="name"
                    id="name"
                    v-model="product.name"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.name">
                    <strong>{{ this.errors.name[0] }}</strong>
                </span>
            </div>
            <div class="col">
                <label for="code" class="form-label">{{ $t('Code') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.code, 'form-control is-invalid' : this.errors?.code }"
                    type="text"
                    aria-label="Code"
                    name="code"
                    id="code"
                    v-model="product.code"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.code">
                    <strong>{{ this.errors.code[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="price" class="form-label">{{ $t('Price') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.price, 'form-control is-invalid' : this.errors?.price }"
                    type="text"
                    aria-label="Price"
                    name="price"
                    id="price"
                    v-model="product.price"
                >
                <span class="invalid-feedback" role="alert" v-if="this.errors?.price">
                    <strong>{{ this.errors.price[0] }}</strong>
                </span>
            </div>
            <div class="col">
                <label for="category" class="form-label">{{ $t('Category') }}</label>
                <model-select
                    :options="categories"
                    v-model="category"
                    :is-error="!!this.errors?.category_id"
                    placeholder="Buscar categoría"
                ></model-select>
                <span class="text-danger small" role="alert" v-if="this.errors?.category_id">
                    <strong>{{ this.errors.category_id[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-3 mb-3">
            <div class="col">
                <label for="image" class="form-label">{{ $t('Image') }}</label>
                <input
                    :class="{'form-control' : !this.errors?.image, 'form-control is-invalid' : this.errors?.image }"
                    type="file"
                    aria-label="Image"
                    name="image"
                    id="image"
                    @change="onFileChange($event)"
                >
                <span class="text-danger small" role="alert" v-if="this.errors?.image">
                    <strong>{{ this.errors.image[0] }}</strong>
                </span>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label for="description" class="form-label">{{ $t('Description') }}</label>
                <textarea
                    :class="{'form-control' : !this.errors?.description, 'form-control is-invalid' : this.errors?.description }"
                    aria-label="Description"
                    name="description"
                    id="description"
                    rows="3"
                    v-model="product.description"
                ></textarea>
                <span class="text-danger small" role="alert" v-if="this.errors?.description">
                    <strong>{{ this.errors.description[0] }}</strong>
                </span>
            </div>
        </div>


        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                {{ $t('Save') }}
            </button>
        </div>
    </form>
    <div class="row" v-else>
        <div class="col d-flex justify-content-center align-items-center">
            <div class="spinner-border text-primary mt-4" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</template>

<script>
    import { useToast } from "vue-toastification";
    import { trans } from 'laravel-vue-i18n';
    import { ModelSelect } from 'vue-search-select';

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        props: {
            productSlug: {
                type: String,
            }
        },
        components: {
            ModelSelect,
        },
        data() {
            return {
                product: {},
                categories: [],
                category: {
                    value: '',
                    text: '',
                },
                errors: [],
                image: null,
                loading: false,
            }
        },
        created() {
            this.getCategories().then(() => {
                this.categories = this.categories.map(c => ({ value: c.id, text: c.name }))
            })
            if (this.productSlug) {
                this.getProduct()
            }
        },
        methods: {
            submit() {
                let method = this.productSlug ? 'put' : 'post';
                let url = this.productSlug ? `/api/admin/products/${this.productSlug}` : '/api/admin/products' ;
                let message = this.productSlug ? 'Product updated' : 'Product created';

                axios.post(url, this.getFormData(method)).then(res => {
                    this.toast.success(trans(message), {
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
                    this.errors = [];
                }).catch(err => {
                    this.errors = err.response.data.errors;
                    console.log(this.errors)
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
            async getProduct() {
                this.loading = true;
                await axios.get(`/api/admin/products/${this.productSlug}`)
                    .then(res => {
                        this.product.name = res.data.name;
                        this.product.code = res.data.code;
                        this.product.price = res.data.price;
                        this.product.description = res.data.description;
                        this.product.category_id = res.data.category.id;
                        this.product.image = null;
                        this.category.value = res.data.category.id;
                        this.category.name = this.categories.filter(c => c.value === res.data.category.id).name
                        this.loading = false;
                    })
                    .catch(err => {
                        console.log(err)
                        this.loading = false;
                    })
            },
            onFileChange(event) {
                this.product.image = event.target.files[0];
            },
            getFormData(method) {
                const formData = new FormData();
                formData.append('name', this.product.name);
                formData.append('code', this.product.code);
                formData.append('price', this.product.price);
                formData.append('category_id', this.product.category_id);
                if (this.product.description) formData.append('description', this.product.description);
                if (this.product.image) formData.append('image', this.product.image);
                if (method === 'put') formData.append('_method', 'PUT');

                return formData;
            }
        },
        watch: {
            category(newCat) {
                this.product.category_id = newCat.value;
            }
        }
    }
</script>

<style>

</style>
