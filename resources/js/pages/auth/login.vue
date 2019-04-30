<template>
    <v-app class="blue-grey lighten-5">
        <v-content class="items-center">
            <v-container fluid grid-list-md>
                <v-flex class="m-auto" xs12 sm5 md4 lg4>
                    <v-form
                        lazy-validation
                        ref="form"
                    >
                        <v-card>
                            <div class="justify-center relative lar-img">
                                <v-img
                                    :src="require('@/public/images/logo/logo-tanmnt.png')"
                                    width="200"
                                    :lazy-src="require('@/public/images/logo/logo-tanmnt.png')"
                                ></v-img>
                            </div>
                            <div class="absolute pin-t pin-r">
                                <v-menu
                                    offset-y
                                    transition="slide-y-transition"
                                >
                                    <template v-slot:activator="{ on }">
                                        <div class="h-16 w-16 items-center justify-center display-1 flex">
                                            <i v-on="on" class="fa fa-language text-5xl"></i>
                                        </div>
                                    </template>
                                    <v-list>
                                        <v-list-tile
                                            v-for="(item, index) in languages"
                                            :key="index"
                                            @click="changeLanguage(item.value)"
                                            :class="{'bg-blue-light text-white font-bold': $store.getters['lang/lang'] === item.value}"
                                        >
                                            <v-list-tile-title>
                                                <i v-if="index === 0 " class="flag-icon flag-icon-vn"></i>
                                                <i v-if="index === 1 " class="flag-icon flag-icon-my"></i>
                                                {{ item.title }}
                                            </v-list-tile-title>
                                        </v-list-tile>
                                    </v-list>
                                </v-menu>
                            </div>
                            <!--                        <v-img :src="logo"></v-img>-->
                            <v-card-title>
                                <v-flex xs12>
                                    <v-text-field
                                        :label="$t('auth.login.email')"
                                        v-model="form.email"
                                        counter="255"
                                        :autofocus="true"
                                        :rules="rules.email"
                                        :error-messages="errors.email ? errors.email[0] : ''"
                                        @input="errors.email = ''"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12>
                                    <v-text-field
                                        :label="$t('auth.login.password')"
                                        type="password"
                                        counter="255"
                                        v-model="form.password"
                                        :rules="rules.password"
                                        :error-messages="errors.password ? errors.password[0] : ''"
                                        @input="errors.password = ''"
                                        @keyup.enter.prevent="login"
                                    ></v-text-field>
                                </v-flex>
                                <v-flex xs12 class="justify-center">
                                    <v-btn
                                        large
                                        class="white--text font-bold rounded-sm"
                                        color="indigo darken-4"
                                        :block="true"
                                        :loading="loading"
                                        @click.prevent="login"
                                    >{{$t('auth.login.login')}}
                                    </v-btn>
                                </v-flex>
                                <v-flex xs6>
                                    <v-checkbox
                                        :label="$t('auth.login.remember')"
                                        class="ljs-checkbox"
                                    ></v-checkbox>
                                </v-flex>
                                <v-flex xs6 class="justify-end">
                                    <a class="text-black">{{$t('auth.login.forgot_password')}}</a>
                                </v-flex>
                            </v-card-title>
                        </v-card>
                    </v-form>
                </v-flex>
            </v-container>
        </v-content>
<!--        <v-footer app></v-footer>-->
    </v-app>
</template>

<script>
    import {
        SET_LANG,
        LOGIN
    } from '@/store/muation-types';
    import {
        isEmailValid
    } from '@/utils/validate';

    export default {
        data() {
            return {
                logo: require('@/public/images/logo/logo-tanmnt.png'),
                form: {
                    email: '',
                    password: ''
                },
                rules: {
                    email: [
                        v => !!v || this.$t('auth.error.email'),
                        v => isEmailValid(v) || this.$t('auth.error.email_valid')
                    ],
                    password: [
                        v => !!v || this.$t('auth.error.password'),
                    ]
                },
                loading: false,
                languages: [
                    {
                        value: "vn",
                        title: 'Việt Nam',
                    },
                    {
                        value: "en",
                        title: 'English',
                    }
                ],
                redirect: undefined
            }
        },
        methods: {
            changeLanguage(lang) {
                localStorage.setItem('lang', lang);
                this.$store.dispatch(`lang/${SET_LANG}`, lang);
                fetch(`api/v1/language/${lang}`);
            },
            login() {
                this.loading = true;
                this.$refs.form.validate();
                this.$store.dispatch(`auth/${LOGIN}`, this.form)
                    .then(res => {
                        this.loading = false;
                        this.$router.push({ path: this.redirect || '/users' })
                    })
                    .catch(err => {
                        console.log(err);
                        this.loading = false;
                    });
            }
        },
        watch: {
            $route: {
                handler: function(route) {
                    this.redirect = route.query && route.query.redirect
                },
                immediate: true
            }
        },
    }
</script>

<style lang="scss">
    .lar-img {
        .v-image {
            margin: 0 auto;
        }
    }
    .ljs-checkbox {
        .v-label {
            margin: 0 !important;
        }
    }
</style>
