<template>
    <!-- If you don't need a dropdown anymore, you can remove this outer div entirely -->
    <div class="relative inline-block text-left">
        <button
            id="dropdownButton"
            class="w-8 h-8 font-medium leading-none flex items-center justify-center text-sm transition-all border-white rounded-full outline-none ring-opacity-20 dark:ring-0 ring-2 ring-white focus:ring-offset-white bg-gray-100 text-violet-700 dark:bg-gray-600"
        >
            <!-- icon only, or rename it to indicate dark mode is active -->
            <svg
                width="24"
                height="24"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class="w-6 h-6 fill-gray-300 dark:fill-gray-400"
            >
                <path
                    d="M2.03009 12.42C2.39009 17.57 6.76009 21.76 11.9901 21.99C15.6801 22.15 18.9801 20.43 20.9601 17.72C21.7801 16.61 21.3401 15.87 19.9701 16.12C19.3001 16.24 18.6101 16.29 17.8901 16.26C13.0001 16.06 9.00009 11.97 8.98009 7.13996C8.97009 5.83996 9.24009 4.60996 9.73009 3.48996C10.2701 2.24996 9.62009 1.65996 8.37009 2.18996C4.41009 3.85996 1.70009 7.84996 2.03009 12.42Z"
                ></path>
            </svg>
        </button>
        <!-- Dropdown menu (optional to keep) -->
        <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownButton">
                <!-- Only one item, purely decorative or to reaffirm the user is on dark theme -->
                <li
                    id="theme-toggle-dark-icon"
                    class="transition duration-700 flex w-full px-4 py-2 text-sm leading-5 outline-none text-gray-600 dark:text-gray-200 hover:bg-gray-200/30 focus:bg-gray-100 focus:text-gray-900 cursor-pointer"
                >
                    <div class="mr-2">
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 stroke-gray-400 block dark:hidden"
                        >
                            <path
                                d="M2.03009 12.42C2.39009 17.57 6.76009 21.76 11.9901 21.99C15.6801 22.15 18.9801 20.43 20.9601 17.72C21.7801 16.61 21.3401 15.87 19.9701 16.12C19.3001 16.24 18.6101 16.29 17.8901 16.26C13.0001 16.06 9.00009 11.97 8.98009 7.13996C8.97009 5.83996 9.24009 4.60996 9.73009 3.48996C10.2701 2.24996 9.62009 1.65996 8.37009 2.18996C4.41009 3.85996 1.70009 7.84996 2.03009 12.42Z"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            ></path>
                        </svg>
                        <svg
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 dark:fill-gray-400 hidden dark:block"
                        >
                            <path
                                d="M21.5287 15.9294C21.3687 15.6594 20.9187 15.2394 19.7987 15.4394C19.1787 15.5494 18.5487 15.5994 17.9187 15.5694C15.5887 15.4694 13.4787 14.3994 12.0087 12.7494C10.7087 11.2994 9.90873 9.40938 9.89873 7.36938C9.89873 6.22938 10.1187 5.12938 10.5687 4.08938C11.0087 3.07938 10.6987 2.54938 10.4787 2.32938C10.2487 2.09938 9.70873 1.77938 8.64873 2.21938C4.55873 3.93938 2.02873 8.03938 2.32873 12.4294C2.62873 16.5594 5.52873 20.0894 9.36873 21.4194C10.2887 21.7394 11.2587 21.9294 12.2587 21.9694C12.4187 21.9794 12.5787 21.9894 12.7387 21.9894C16.0887 21.9894 19.2287 20.4094 21.2087 17.7194C21.8787 16.7894 21.6987 16.1994 21.5287 15.9294Z"
                            ></path>
                        </svg>
                    </div>
                    <span>Dark theme</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DropdownDarkLight',
    mounted() {
        // Force dark theme on mount
        document.documentElement.classList.add('dark');
        localStorage.setItem('color-theme', 'dark');

        // If you'd like the 'Dark theme' menu item to do something on click, add an event listener:
        const darkItem = document.getElementById('theme-toggle-dark-icon');
        darkItem.addEventListener('click', () => {
            // For example, re-apply dark theme in case user changed something
            document.documentElement.classList.remove('light');
            document.documentElement.classList.add('dark');
            localStorage.setItem('color-theme', 'dark');
        });
    },
};
</script>

<style scoped>
/* You can keep any dark-specific styles here */
</style>
