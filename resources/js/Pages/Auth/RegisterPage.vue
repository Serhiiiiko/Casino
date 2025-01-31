<template>
    <AuthLayout>
      <LoadingComponent :isLoading="isLoading">
        <div class="text-center">
          <span>{{ $t('Loading') }}</span>
        </div>
      </LoadingComponent>
  
      <div v-if="!isLoading" class="my-auto">
        <div class="px-4 py-5">
          <div class="min-h-[calc(100vh-565px)] text-center flex flex-col items-center justify-center">
            <div class="w-full bg-white rounded-lg shadow-lg md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-700 dark:border-gray-700">
              <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="mb-8 text-3xl text-center">{{ $t('Register') }}</h1>
                
                <!-- Форма без поля для CPF -->
                <div class="mt-4 px-4">
                  <form @submit.prevent="registerSubmit" method="post" action="" class="">
                    <div class="relative mb-3">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-regular fa-user text-success-emphasis"></i>
                      </div>
                      <input
                        type="text"
                        name="name"
                        v-model="registerForm.name"
                        class="input-group"
                        :placeholder="$t('Enter name')"
                        required
                      />
                    </div>
  
                    <div class="relative mb-3">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-regular fa-envelope text-success-emphasis"></i>
                      </div>
                      <input
                        type="email"
                        name="email"
                        v-model="registerForm.email"
                        class="input-group"
                        :placeholder="$t('Enter email or phone')"
                        required
                      />
                    </div>
  
                    <!-- Поле CPF убрано/закомментировано, но значение генерируется автоматически -->
  
                    <div class="relative mb-3">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-regular fa-lock text-success-emphasis"></i>
                      </div>
                      <input
                        :type="typeInputPassword"
                        name="password"
                        v-model="registerForm.password"
                        class="input-group pr-[40px]"
                        :placeholder="$t('Type the password')"
                        required
                      />
                      <button
                        type="button"
                        @click.prevent="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 "
                      >
                        <i
                          v-if="typeInputPassword === 'password'"
                          class="fa-regular fa-eye"
                        ></i>
                        <i
                          v-if="typeInputPassword === 'text'"
                          class="fa-sharp fa-regular fa-eye-slash"
                        ></i>
                      </button>
                    </div>
  
                    <div class="relative mb-3">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-regular fa-lock text-success-emphasis"></i>
                      </div>
                      <input
                        :type="typeInputPassword"
                        name="password_confirmation"
                        v-model="registerForm.password_confirmation"
                        class="input-group pr-[40px]"
                        :placeholder="$t('Confirm the Password')"
                        required
                      />
                      <button
                        type="button"
                        @click.prevent="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5"
                      >
                        <i
                          v-if="typeInputPassword === 'password'"
                          class="fa-regular fa-eye"
                        ></i>
                        <i
                          v-if="typeInputPassword === 'text'"
                          class="fa-sharp fa-regular fa-eye-slash"
                        ></i>
                      </button>
                    </div>
  
                    <div class="relative mb-3">
                      <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                        <i class="fa-regular fa-phone"></i>
                      </div>
                      <input
                        type="text"
                        name="phone"
                        v-maska
                        data-maska="[
                          '(##) ####-####',
                          '(##) #####-####'
                        ]"
                        v-model="registerForm.phone"
                        class="input-group"
                        :placeholder="$t('Enter your phone')"
                        required
                      />
                    </div>
  
                    <div class="mb-3 mt-5">
                      <button
                        @click.prevent="isReferral = !isReferral"
                        type="button"
                        class="flex justify-between w-full"
                      >
                        <p>{{ $t('Enter Referral/Promo Code') }}</p>
                        <div>
                          <i
                            v-if="isReferral"
                            class="fa-solid fa-chevron-up"
                          ></i>
                          <i
                            v-if="!isReferral"
                            class="fa-solid fa-chevron-down"
                          ></i>
                        </div>
                      </button>
  
                      <div v-if="isReferral" class="relative mb-3 mt-1">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                          <i class="fa-regular fa-user text-success-emphasis"></i>
                        </div>
                        <input
                          type="text"
                          name="name"
                          v-model="registerForm.reference_code"
                          class="input-group"
                          :placeholder="$t('Code')"
                        />
                      </div>
                    </div>
  
                    <hr class="mb-3 mt-2 dark:border-gray-600" />
  
                    <div class="mb-3 mt-11">
                      <div class="flex">
                        <input
                          id="term-a"
                          v-model="registerForm.term_a"
                          name="term_a"
                          required
                          type="checkbox"
                          value=""
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                        <label
                          for="term-a"
                          class="ml-2 text-sm font-medium text-left text-gray-900 dark:text-gray-300"
                        >
                          {{
                            $t(
                              'I agree to the User Agreement & confirm I am at least 18 years old'
                            )
                          }}
                        </label>
                      </div>
                    </div>
  
                    <div class="mb-3">
                      <div class="flex items-center">
                        <input
                          id="term-agreement"
                          v-model="registerForm.agreement"
                          name="term_b"
                          required
                          type="checkbox"
                          value=""
                          class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                        <label
                          for="term-agreement"
                          class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                        >
                          {{
                            $t('I agree with the')
                          }}
                          <a href="#" class="text-primary hover:underline"
                            >{{ $t('terms and conditions') }}</a
                          >.
                        </label>
                      </div>
                    </div>
  
                    <div class="mt-5 w-full">
                      <button type="submit" class="ui-button-blue rounded w-full mb-3">
                        {{ $t('Register') }}
                      </button>
                    </div>
                  </form>
                </div>
                <!-- end form -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthLayout>
  </template>
  
  <script>
  import { onMounted, reactive } from "vue";
  import { useToast } from "vue-toastification";
  import { useRoute, useRouter } from "vue-router";
  import { useAuthStore } from "@/Stores/Auth.js";
  import HttpApi from "@/Services/HttpApi.js";
  import AuthLayout from "@/Layouts/AuthLayout.vue";
  import LoadingComponent from "@/Components/UI/LoadingComponent.vue";
  
  export default {
    components: {
      LoadingComponent,
      AuthLayout,
    },
    data() {
      return {
        isLoading: false,
        typeInputPassword: "password",
        isReferral: false,
  
        // Обратите внимание: поле cpf есть, но не отображается в форме.
        registerForm: {
          name: "",
          email: "",
          password: "",
          password_confirmation: "",
          phone: "",
          cpf: "", // Будем генерировать автоматически
          reference_code: "",
          term_a: false,
          agreement: false,
          spin_data: null,
        },
      };
    },
    setup() {
      const router = useRouter();
      const routeParams = reactive({
        code: null,
      });
  
      onMounted(() => {
        // Проверяем GET-параметр ?code= в URL
        const params = new URLSearchParams(window.location.search);
        if (params.has("code")) {
          routeParams.code = params.get("code");
        }
      });
  
      return {
        routeParams,
        router,
      };
    },
    computed: {
      isAuthenticated() {
        const authStore = useAuthStore();
        return authStore.isAuth;
      },
    },
    created() {
      // Генерируем случайный CPF при создании компонента
      this.registerForm.cpf = this.generateRandomCPF();
    },
    mounted() {
      if (this.isAuthenticated) {
        this.$router.push({ name: "home" });
      }
  
      if (this.router.currentRoute.value.params.code) {
        try {
          const str = atob(this.router.currentRoute.value.params.code);
          JSON.parse(str);
          this.registerForm.spin_token = this.router.currentRoute.value.params.code;
        } catch (e) {
          this.registerForm.reference_code = this.routeParams.code;
          this.isReferral = true;
        }
      } else if (this.routeParams.code) {
        this.registerForm.reference_code = this.routeParams.code;
        this.isReferral = true;
      }
    },
    methods: {
      /**
       * Пример генерации валидного случайного CPF.
       * Если вам достаточно просто случайного 11-значного числа,
       * можете упростить логику, сгенерировав Math.random() * 10^11.
       */
      generateRandomCPF() {
        const randomInt = (max) => Math.floor(Math.random() * max);
        
        // Сгенерируем первые 9 цифр
        let cpf = "";
        for (let i = 0; i < 9; i++) {
          cpf += randomInt(10);
        }
  
        // Вычислим первые два проверочных разряда (d1, d2)
        let d1 = 0;
        let d2 = 0;
        for (let i = 0; i < 9; i++) {
          // Преобразуем символ в число
          const digit = parseInt(cpf.charAt(i));
          d1 += digit * (10 - i);
          d2 += digit * (11 - i);
        }
        d1 = (d1 * 10) % 11;
        if (d1 === 10) d1 = 0;
        d2 = d2 + d1 * 2;
        d2 = (d2 * 10) % 11;
        if (d2 === 10) d2 = 0;
  
        // Собираем финальный 11-значный CPF
        cpf += `${d1}${d2}`;
        return cpf;
      },
  
      togglePassword() {
        this.typeInputPassword =
          this.typeInputPassword === "password" ? "text" : "password";
      },
  
      async registerSubmit() {
        const _toast = useToast();
        this.isLoading = true;
  
        const authStore = useAuthStore();
        try {
          const response = await HttpApi.post("auth/register", this.registerForm);
  
          if (response.data.access_token !== undefined) {
            authStore.setToken(response.data.access_token);
            authStore.setUser(response.data.user);
            authStore.setIsAuth(true);
  
            // Сбрасываем форму
            this.registerForm = {
              name: "",
              email: "",
              password: "",
              password_confirmation: "",
              phone: "",
              cpf: "",
              reference_code: "",
              term_a: false,
              agreement: false,
              spin_data: null,
            };
  
            this.$router.push({ name: "home" });
            _toast.success(this.$t("Your account has been created successfully"));
          }
        } catch (error) {
          // В случае ошибок с бэкенда
          if (error.request?.responseText) {
            const errors = JSON.parse(error.request.responseText);
            Object.values(errors).forEach((message) => {
              _toast.error(message);
            });
          } else {
            _toast.error(this.$t("An error occurred"));
          }
        } finally {
          this.isLoading = false;
        }
      },
    },
  };
  </script>
  
  <style scoped>
  </style>
  