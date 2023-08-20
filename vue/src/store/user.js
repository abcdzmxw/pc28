import store from "@/utils/store";

export default {
  setToken(token) {
    store.set("token", token);
  },
  setsessionStorage(key,vlaue) {
    store.set(key, vlaue);
  },
  delsessionStorage(key) {
    store.delete(key);
  },
  getToken() {
    return store.get("token");
  },
  clearToken() {
    return store.delete("token");
  }
};
