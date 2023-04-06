<template>
    <tr>
        <th scope="row">{{ category.name }}</th>
        <td>{{ category.slug }}</td>
        <td>{{ category.ago }}</td>
        <td>
            <div class="d-flex gap-2">
            </div>
        </td>
    </tr>
</template>

<script>
    import { useToast } from "vue-toastification";
    import { IconEdit } from '@tabler/icons-vue';

    export default {
        setup() {
            const toast = useToast();

            return { toast }
        },
        components: {
            IconEdit
        },
        props: {
            category: {
                type: Object,
                required: true,
            }
        },
    }
</script>

<style>

</style>
