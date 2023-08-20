import storeAction from "../typed-actions";
import router from "@/router";
import { Toast } from "vant";
// 登录成功 - 初始化信息
export function loginDone(res) {
  console.log("res", res);
  const userId = res.userinfo.user_id;
  const id = res.userinfo.id;
  storeAction.setToken(res.userinfo.token);
  storeAction.setCurrentUserId(userId);
  storeAction.setCurrentUser(res.userinfo);
  storeAction.setsessionStorage("userId", userId);
  storeAction.setsessionStorage("id", id);
  storeAction.setCurrentUserMoney(res.userinfo.money);
  storeAction.setsessionStorage("money", res.userinfo.money);
  Toast.loading({
    message: "loading...",
    forbidClick: true,
    duration: 500,
    onClose() {
      router.push(`/`);
    },
  });
}
