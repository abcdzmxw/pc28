import request from "@/utils/request";
export default {
  // 首页
  homeindex() {
    return request.get("/api/index/index");
  },
  // 交易接口
  Lotteryinfo(game_id) {
    return request.post("/api/Lottery/index", {
      game_id: game_id,
    });
  },
  //心跳
  heart(plan_id, game_id) {
    return request.post("/api/Lottery/heart", {
      plan_id: plan_id,
      game_id: game_id,
    });
  },
  //文章接口
  articleList() {
    return request.get("/api/index/articleList");
  },
  //文章接口
  oddsList(plan_id) {
    return request.post("/api/Lottery/oddsList", {
      plan_id: plan_id,
    });
  },
  //下注蜜桃源码网--www.mitaobo.com
  playOrder(issue, plan_id, game_id, play_type, money) {
    return request.post("/api/Lottery/playOrder", {
      issue: issue,
      plan_id: plan_id,
      game_id: game_id,
      play_type: play_type,
      money: money,
    });
  },
  issueList(game_id) {
    return request.post("/api/Lottery/issueList", {
      game_id: game_id,

    });
  },
  cand(game_id) {
    return request.post("/api/Lottery/cand", {
      game_id: game_id,

    });
  },
};
