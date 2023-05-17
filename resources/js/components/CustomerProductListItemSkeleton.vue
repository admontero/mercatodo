<template>
    <div class="col-md-4 col-sm-6 mb-4">
        <div class="bg-white rounded shadow-sm">
            <AnimatedPlaceholder
                width="100%"
                aspectRatio="3 / 2"
            ></AnimatedPlaceholder>
            <div class="p-4">
                <div class="d-flex justify-content-end mb-3">
                    <AnimatedPlaceholder
                        width="50px"
                        height="15px"
                        borderRadius="3px"
                    ></AnimatedPlaceholder>
                </div>
                <AnimatedPlaceholder
                    class="mb-2"
                    width="100%"
                    height="15px"
                    borderRadius="3px"
                ></AnimatedPlaceholder>
                <AnimatedPlaceholder
                    width="100%"
                    height="15px"
                    borderRadius="3px"
                ></AnimatedPlaceholder>
            </div>
            <div class="d-flex justify-content-center align-items-center h5 fw-bold">
                <AnimatedPlaceholder
                    width="100%"
                    height="30px"
                ></AnimatedPlaceholder>
            </div>
            <div class="px-4 pb-3 pt-2">
                <AnimatedPlaceholder
                    width="100%"
                    height="30px"
                    borderRadius="3px"
                ></AnimatedPlaceholder>
            </div>
        </div>
    </div>
</template>

<script>
    import AnimatedPlaceholder from './AnimatedPlaceholder.vue';

    export default {
        components: {
            AnimatedPlaceholder,
        }
    }
</script>

<style>

</style>
