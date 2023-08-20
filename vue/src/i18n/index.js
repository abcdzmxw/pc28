import { createI18n } from 'vue-i18n'
import zh from './config/zh'
import tw from './config/tw'
import en from './config/en'
console.log(createI18n)
const i18n  = createI18n({
    legacy: false, // composition API
    globalInjection:true,
    locale:localStorage.getItem('locale')||'tw',
    messages:{
        zh,
        tw,
		en,
    },
 })
 export default i18n;