import request from "@/utils/request";
export default {
  // 积分日志
  quantityLogList(begin_date, end_date, type) {
    if (type != undefined) {
      return request.post("/api/user/quantityLogList", {
        begin_date: begin_date,
        end_date: end_date,
        type: type,
      });
    } else {
      return request.post("/api/user/quantityLogList", {
        begin_date: begin_date,
        end_date: end_date,
      });
    }
  },
  orderList(begin_date, end_date) {
    return request.post("/api/user/orderList", {
      begin_date: begin_date,
      end_date: end_date,
    });
  },
  //   个人中心
  userInfo() {
    return request.post("/api/user/userInfo");
  },
  //   收款信息
  payInfo(type) {
    return request.post("/api/user/payInfo", {
      type: type,
    });
  },
  //   收款信息更新
  setPayInfo(acc, acc_name, acc_bank, mark, type) {
    return request.post("/api/user/setPayInfo", {
      acc: acc,
      acc_name: acc_name,
      acc_bank: acc_bank,
      mark: mark || "",
      type: type,
    });
  },
  //   安全码
  safetyInfo() {
    return request.post("/api/user/safetyInfo", {});
  },
  //   安全码更新
  setSafety(new_code, after_code) {
    return request.post("/api/user/setSafety", {
      new_code: new_code,
      after_code: after_code,
    });
  },
  //   密码更新
  newPass(new_pass, after_pass) {
    return request.post("/api/user/newPass", {
      new_pass: new_pass,
      after_pass: after_pass,
    });
  },
  //   提现页面
  withdrawInfo() {
    return request.post("/api/user/withdrawInfo", {});
  },
  //   提现请求
  withdrawAction(type, money, securityCode) {
    return request.post("/api/user/withdrawAction", {
      type: type,
      money: money,
      securityCode: securityCode,
    });
  },
  //   充值页面
  recharge() {
    return request.post("/api/user/recharge", {});
  },
    //   充值页面
    profile(idcard) {
      return request.post("/api/user/profile", {idcard:idcard});
    },
};
