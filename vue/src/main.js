import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vant from 'vant';
import 'vant/lib/index.css';
import { Lazyload } from 'vant';
import 'lib-flexible/flexible'
import './utils/style.less';
// import i18n from './i18n';
import VueI18n from './i18n'
import animate from 'animate.css'
createApp(App).use(store).use(router).use(Lazyload).use(vant).use(VueI18n).use(animate).mount('#app')
