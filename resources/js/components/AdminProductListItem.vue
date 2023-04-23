<template>
    <tr>
        <th scope="row">{{ product.name }}</th>
        <td>{{ product.code }}</td>
        <td>{{ product.category.name }}</td>
        <td>{{ productPrice }}</td>
        <td>{{ product.ago }}</td>
        <td>
            <div class="d-flex gap-2">

            </div>
        </td>
    </tr>
</template>

<script>
    export default {
        props: {
            product: {
                type: Object,
                required: true,
            }
        },
        mounted() {
            console.log(this.product)
        },
        computed: {
            productPrice() {
                return new Intl.NumberFormat('es-CO').format(this.product.price)
            }
        }
    }
</script>

<style>

</style>
