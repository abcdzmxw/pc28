import request from "@/utils/request";
export default {
  // 用户注册
  register(username, password, group_id, inviter_id) {
    return request.post("/api/user/register", {
      username: username,
      password: password,
      group_id: group_id,
      inviter_id: inviter_id || "",
    });
  },
  // 用户登录
  login(account, password) {
    return request.post("/api/user/login", {
      account: account,
      password: password,
    });
  },
};
