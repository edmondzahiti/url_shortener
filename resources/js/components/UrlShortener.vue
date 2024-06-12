<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-900">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <form @submit.prevent="shortenUrl" class="flex flex-col items-center">
                <input type="text" v-model="originalUrl" placeholder="Enter URL" required
                       class="w-full p-3 border border-gray-300 rounded mb-4 text-black">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
                    Shorten
                </button>
            </form>
            <div v-if="validationError" class="mt-4 text-center text-red-500">
                {{ validationError }}
            </div>
            <div v-if="shortUrl" class="mt-4 text-center text-green-500">
                Shortened URL: <a :href="shortUrl" target="_blank" class="text-blue-500">{{ shortUrl }}</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            originalUrl: '',
            shortUrl: '',
            validationError: ''
        };
    },
    methods: {
        async shortenUrl() {
            this.validationError = '';
            this.shortUrl = '';
            try {
                const response = await axios.post('/shorten', {original_url: this.originalUrl});
                this.shortUrl = response.data.data.short_url;
            } catch (error) {
                if (error.response && error.response.data.errors && error.response.data.errors.original_url) {
                    this.validationError = error.response.data.errors.original_url[0];
                } else {
                    console.error(error);
                }
            }
        }
    }
};
</script>
