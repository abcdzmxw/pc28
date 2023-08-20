import { createRouter, createWebHistory } from "vue-router";
import storeToken from "@/utils/store";
import { Toast } from "vant";
import Home from "../views/Home.vue";
import Login from "../views/Login.vue";
import Register from "../views/Register.vue";
import Personal from "../views/Personal.vue";
import Setting from "../views/Setting.vue";
import Changeline from "../views/Changeline.vue";
import About from "../views/About.vue";
import System from "../views/System.vue";
import Language from "../views/Language.vue";
import Baseinfo from "../views/Baseinfo.vue";
import DefaultAvatar from "../views/DefaultAvatar.vue";
import BankCardManage from "../views/BankCardManage.vue";
import AddBankCard from "../views/AddBankCard.vue";
import AddVirtualWallet from "../views/AddVirtualWallet.vue";
import SetSecurityCode from "../views/SetSecurityCode.vue";
import ChangePassword from "../views/ChangePassword.vue";
import ActionCenter from "../views/ActionCenter.vue";
import ShareFriend from "../views/ShareFriend.vue";
import Notice from "../views/Notice.vue";
import BetRecord from "../views/BetRecord.vue";
import CapitalDetail from "../views/CapitalDetail.vue";
import Withdraw from "../views/Withdraw.vue";
import Recharge from "../views/Recharge.vue";
import Find from "../views/Find.vue";
import Lottery from "../views/Lottery.vue";
import BetDetail from "../views/BetDetail.vue";
import BetRoom from "../views/BetRoom.vue";
import LotteryDetail from "../views/LotteryDetail.vue";
import LotteryExplain from "../views/LotteryExplain.vue";
import TrendChart from "../views/TrendChart.vue";
import MoreNews from "../views/MoreNews.vue";
import AticleDetail from "../views/AticleDetail.vue";
const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
    meta: { index: 5 },
  },
  
 
  {
    path: "/login",
    name: "Login",
    component: Login,
    meta: { index: 4 },
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
    meta: { index: 4 },
  },
  {
    path: "/personal",
    name: "Personal",
    component: Personal,
    meta: { index: 5, requireAuth: true },
  },
  {
    path: "/setting",
    name: "Setting",
    component: Setting,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/changeline",
    name: "Changeline",
    component: Changeline,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/about",
    name: "About",
    component: About,
    meta: { index: 5, requireAuth: true },
  },
  {
    path: "/system",
    name: "System",
    component: System,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/language",
    name: "Language",
    component: Language,
    meta: { index: 0 },
  },
  {
    path: "/baseinfo",
    name: "Baseinfo",
    component: Baseinfo,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/defaultAvatar",
    name: "DefaultAvatar",
    component: DefaultAvatar,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/bankCardManage",
    name: "BankCardManage",
    component: BankCardManage,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/addBankCard",
    name: "AddBankCard",
    component: AddBankCard,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/addVirtualWallet",
    name: "AddVirtualWallet",
    component: AddVirtualWallet,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/setSecurityCode",
    name: "SetSecurityCode",
    component: SetSecurityCode,
  },
  {
    path: "/changePassword",
    name: "ChangePassword",
    component: ChangePassword,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/actionCenter",
    name: "ActionCenter",
    component: ActionCenter,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/shareFriend",
    name: "ShareFriend",
    component: ShareFriend,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/notice",
    name: "Notice",
    component: Notice,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/betRecord",
    name: "BetRecord",
    component: BetRecord,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/capitalDetail",
    name: "CapitalDetail",
    component: CapitalDetail,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/withdraw",
    name: "Withdraw",
    component: Withdraw,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/recharge",
    name: "Recharge",
    component: Recharge,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/find",
    name: "Find",
    component: Find,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/lottery",
    name: "Lottery",
    component: Lottery,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/betDetail",
    name: "BetDetail",
    component: BetDetail,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/betRoom",
    name: "BetRoom",
    component: BetRoom,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/lotteryDetail",
    name: "LotteryDetail",
    component: LotteryDetail,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/lotteryExplain",
    name: "LotteryExplain",
    component: LotteryExplain,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/trendChart",
    name: "TrendChart",
    component: TrendChart,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/moreNews",
    name: "MoreNews",
    component: MoreNews,
    meta: { index: 0, requireAuth: true },
  },
  {
    path: "/aticleDetail",
    name: "AticleDetail",
    component: AticleDetail,
    meta: { index: 0, requireAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});
router.beforeEach((to, from, next) => {
  if (to.meta.requireAuth) {
    //如果需要跳转 ，往下走（1）
    if (storeToken.get("token")) {
      //判断是否登录过，如果有登陆过，说明有token,或者token未过期，可以跳过登录（2）
      if (to.path === "/login") {
        //判断下一个路由是否为要验证的路由（3）
        next(); // 如果是直接跳到首页，
      } else {
        next();
      }
    } else {
      console.log("没有"); //如果没有登陆过，或者token 过期， 那么跳转到登录页
      Toast.loading({
        message: "loading",
        forbidClick: true,
        duration: 500,
        onClose() {
          next("/login");
        },
      });
    }
  } else {
    //不需要跳转，直接往下走
    next();
  }
});
export default router;
