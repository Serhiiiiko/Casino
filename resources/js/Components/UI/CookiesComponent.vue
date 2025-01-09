<template>
    <div v-if="!cookieAccepted" class="flex justify-between items-center fixed left-0 md:left-10 bottom-10 z-[999] p-4 dark:bg-gray-900 shadow-lg">
        <p>Мы используем файлы cookie, чтобы обеспечить лучший опыт для вас.</p>
        <button @click="acceptCookie" class="ui-button-blue ml-3">Принять</button>
    </div>
</template>

<script>
    export default {
        props: {

        },
        data() {
            return {
                cookieAccepted: localStorage.getItem('cookieAccepted') === 'true',
            };
        },
        methods: {
            acceptCookie() {
                localStorage.setItem('cookieAccepted', 'true');
                this.cookieAccepted = true;
            },
        },
        watch: {

        }
    };
</script>
