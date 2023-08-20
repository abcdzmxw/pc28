import store from "../index";
import userStore from "../user";
export function setToken(token) {
    store.commit("updateToken", token);
    userStore.setToken(token);
}
export function setsessionStorage(key,vlaue) {
    userStore.setsessionStorage(key,vlaue);
  }
  export function delsessionStorage(key) {
    userStore. delsessionStorage(key);
  }
  
export function clearToken() {
    store.commit("updateToken", null);
    userStore.clearToken();
  }
  export function setCurrentUserId(userId) {
    store.commit("updateCurrentUserId", userId);
  }
  
  export function clearCurrentUserId() {
    store.commit("updateCurrentUserId", null);
  }
  export function setCurrentUser(user) {
    store.commit("updateCurrentUser", user);
  }
  export function setCurrentUserMoney(money) {
    store.commit("updateCurrentUserMoney", money);
  }
  export function setCurrentUseractiveList(money) {
    store.commit("updateActiveList", money);
  }
  export function setCurrentUserGroup_id(group_id) {
    store.commit("updateGroup_id", group_id);
  }
  export function setCurrentUserusercrud(usercrud) {
    store.commit("updateusercrud", usercrud);
  }