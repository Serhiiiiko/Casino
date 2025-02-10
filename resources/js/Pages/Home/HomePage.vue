<template>
    <BaseLayout>
        <LoadingComponent :isLoading="isLoading">
            <div class="text-center">
                <span>{{ $t('Loading data from the platform') }}</span>
            </div>
        </LoadingComponent>
        <div v-if="!isLoading">
            <div class="carousel-banners">
                <div class="md:w-4/6 2xl:w-4/6 mx-auto p-4">
                    <div class="mb-5">
                        <Carousel v-bind="settings" :breakpoints="breakpoints" ref="carouselBanner">
                            <Slide v-for="(banner, index) in banners" :key="index">
                                <div class="carousel__item rounded w-full">
                                    <a :href="banner.link" class="w-full h-full bg-blue-800 rounded">
                                        <img :src="`/storage/`+banner.image" alt="" class="h-full w-full rounded">
                                    </a>
                                </div>
                            </Slide>
                            <template #addons>
                                <navigation>
                                    <template #next>
                                        <i class="fa-solid fa-chevron-right text-white"></i>
                                    </template>
                                    <template #prev>
                                        <i class="fa-solid fa-chevron-left text-white"></i>
                                    </template>
                                </navigation>
                                <Pagination />
                            </template>
                        </Carousel>
                    </div>
                    <div>
                        <Carousel v-bind="settingsRecommended" :breakpoints="breakpointsRecommended" ref="carouselSubBanner">
                            <Slide v-for="(banner, index) in bannersHome" :key="index">
                                <div class="carousel__item min-h-[60px] md:min-h-[150px] rounded w-full mr-4">
                                    <a :href="banner.link" class="w-full h-full rounded">
                                        <img :src="`/storage/`+banner.image" alt="" class="h-full w-full rounded">
                                    </a>
                                </div>
                            </Slide>
                            <template #addons>
                                <Pagination />
                            </template>
                        </Carousel>
                    </div>
                </div>
            </div>
            <div class="md:w-4/6 2xl:w-4/6 mx-auto p-4">
                <div class="mb-5 cursor-pointer w-full">
                    <div class="flex">
                        <div class="relative w-full">
                            <input @click.prevent="toggleSearch" type="search" readonly class="block dark:focus:border-green-500 p-2.5 w-full z-20 text-sm text-gray-900 rounded-e-lg input-color-primary border-none focus:outline-none dark:border-s-gray-800 dark:border-gray-800 dark:placeholder-gray-400 dark:text-white" placeholder="Поиск по играм" required>
                            <button type="submit" class="absolute top-0 end-0 h-full p-2.5 text-sm font-medium text-white rounded-e-lg dark:bg-[#1C1E22]">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="categories" class="category-list">
                    <div class="flex mb-5 gap-4" style="max-height: 200px; overflow-x: auto; overflow-y: hidden;">
                        <div class="flex flex-row justify-between items-center w-full" style="min-width: 100%; white-space: nowrap;">
                            <RouterLink v-for="(category, index) in categories" :key="index" :to="{ name: 'casinosAll', params: { provider: 'all', category: category.slug }}" class="flex flex-col justify-center items-center min-w-[80px] text-center">
                                <div class="category-img">
                                    <img :src="`/storage/`+category.image" alt="" width="35">
                                </div>
                                <p class="mt-3">{{ $t(category.name) }}</p>
                            </RouterLink>
                        </div>
                    </div>
                </div>
                <div v-if="featured_games">
                    <div class="w-full flex justify-between mb-4">
                        <h2 class="text-xl font-bold">{{ $t('Featured') }}</h2>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-5">
                        <CassinoGameCard
                            v-for="(game, index) in featured_games"
                            :key="index"
                            :index="index"
                            :title="game.game_name"
                            :cover="game.cover"
                            :gamecode="game.game_code"
                            :type="game.distribution"
                            :game="game"
                        />
                    </div>
                </div>
                <div class="mt-5">
                    <h2 class="text-xl font-bold mb-4">Все игры</h2>
                    <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                        <CassinoGameCard
                            v-for="(game, index) in allGames"
                            :key="game.id || index"
                            :index="index"
                            :title="game.game_name"
                            :cover="game.cover"
                            :gamecode="game.game_code"
                            :type="game.distribution"
                            :game="game"
                        />
                    </div>
                </div>
                <div v-if="isAuthenticated" class="flex items-center justify-center h-48 mb-4">
                    <div class="grid py-4">
                        <h1 class="text-lg font-bold mb-3 text-center">{{ $t('PREFERRED PAYMENT METHOD') }}</h1>
                        <div class="payment-list flex items-center">
                            <img :src="`/assets/images/icons/pix.svg`" alt="" width="128">
                            <div class="divider"></div>
                            <div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BaseLayout>
</template>

<script>
import { Carousel, Navigation, Pagination, Slide } from 'vue3-carousel'
import { onMounted, ref } from 'vue'
import BaseLayout from '@/Layouts/BaseLayout.vue'
import MakeDeposit from '@/Components/UI/MakeDeposit.vue'
import { RouterLink } from 'vue-router'
import { useAuthStore } from '@/Stores/Auth.js'
import LanguageSelector from '@/Components/UI/LanguageSelector.vue'
import CassinoGameCard from '@/Pages/Cassino/Components/CassinoGameCard.vue'
import HttpApi from '@/Services/HttpApi.js'
import ShowCarousel from '@/Pages/Home/Components/ShowCarousel.vue'
import { useSettingStore } from '@/Stores/SettingStore.js'
import LoadingComponent from '@/Components/UI/LoadingComponent.vue'
import { searchGameStore } from '@/Stores/SearchGameStore.js'
import CustomPagination from '@/Components/UI/CustomPagination.vue'

export default {
  components: {
    CustomPagination,
    Pagination,
    LoadingComponent,
    ShowCarousel,
    CassinoGameCard,
    Carousel,
    Navigation,
    Slide,
    LanguageSelector,
    MakeDeposit,
    BaseLayout,
    RouterLink
  },
  data() {
    return {
      isLoading: true,
      settings: {
        itemsToShow: 1,
        snapAlign: 'center',
        autoplay: 6000,
        wrapAround: true
      },
      breakpoints: {
        700: {
          itemsToShow: 1,
          snapAlign: 'center'
        },
        1024: {
          itemsToShow: 1,
          snapAlign: 'center'
        }
      },
      settingsRecommended: {
        itemsToShow: 2,
        snapAlign: 'start'
      },
      breakpointsRecommended: {
        700: {
          itemsToShow: 3,
          snapAlign: 'center'
        },
        1024: {
          itemsToShow: 3,
          snapAlign: 'start'
        }
      },
      banners: null,
      bannersHome: null,
      providers: null,
      allGames: [],
      featured_games: null,
      categories: null
    }
  },
  setup() {
    const ckCarouselOriginals = ref(null)
    onMounted(() => {})
    return {
      ckCarouselOriginals
    }
  },
  computed: {
    searchGameStore() {
      return searchGameStore()
    },
    userData() {
      const authStore = useAuthStore()
      return authStore.user
    },
    isAuthenticated() {
      const authStore = useAuthStore()
      return authStore.isAuth
    },
    setting() {
      const settingStore = useSettingStore()
      return settingStore.setting
    }
  },
  methods: {
    async getCasinoCategories() {
      try {
        const response = await HttpApi.get('categories')
        this.categories = response.data.categories
      } catch (error) {
        console.error(error)
      }
    },
    toggleSearch() {
      this.searchGameStore.setSearchGameToogle()
    },
    async getBanners() {
      try {
        const response = await HttpApi.get('settings/banners')
        const allBanners = response.data.banners
        this.banners = allBanners.filter(banner => banner.type === 'carousel')
        this.bannersHome = allBanners.filter(banner => banner.type === 'home')
      } catch (error) {
        console.error(error)
      }
    },
    async getAllGames() {
      try {
        const response = await HttpApi.get('games/all')
        if (response.data !== undefined) {
          this.providers = response.data.providers
          let mergedGames = []
          this.providers.forEach(provider => {
            mergedGames = mergedGames.concat(provider.games)
          })
          this.allGames = mergedGames
          this.isLoading = false
        }
      } catch (error) {
        console.error(error)
        this.isLoading = false
      }
    },
    async getFeaturedGames() {
      try {
        const response = await HttpApi.get('featured/games')
        this.featured_games = response.data.featured_games
      } catch (error) {
        console.error(error)
      } finally {
        this.isLoading = false
      }
    },
    async initializeMethods() {
      await this.getCasinoCategories()
      await this.getBanners()
      await this.getAllGames()
      await this.getFeaturedGames()
    }
  },
  async created() {
    await this.initializeMethods()
  }
}
</script>

<style scoped></style>
