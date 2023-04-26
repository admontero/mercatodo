<template>
    <form class="my-4" @submit.prevent="submit" enctype="multipart/form-data">
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
            }
        },
        created() {
            this.getCategories().then(() => {
                this.categories = this.categories.map(c => ({ value: c.id, text: c.name }))
            })
        },
        methods: {
            submit() {
                axios.post(`/api/admin/products`, this.getFormData()).then(res => {
                    this.toast.success(trans('Product created'), {
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
            onFileChange(event) {
                this.product.image = event.target.files[0];
            },
            getFormData() {
                const newFormData = () => Object.keys(this.product).reduce((formData, key) => {
                    formData.append(key, this.product[key]);
                    return formData;
                }, new FormData());

                return newFormData();
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
