<template>
  <div class="personal">
    <div class="p-account">
      <header>
        <div class="g-header" style="background: rgb(70, 105, 154)">
          <div class="left"></div>
          <div class="middle fs-18"></div>
          <div class="right" @click="changestting(1)">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <van-pull-refresh v-model="isLoading" @refresh="onRefresh">
        <div class="mescroll p-account_scroll">
          <div>
            <div class="scroll-content">
              <div class="cont-head">
                <div class="user-head">
                  <div class="user-avatar" @click="changebaseinfo">
                    <img src="@/assets/image/auth.png" class="avatar" alt="" />
                  </div>
                  <div class="grade-box">
                    <div class="member-benefit">
                      <img
                        src="@/assets/image/vip.png"
                        class="icon icon-vip-equity"
                        alt=""
                      />
                      <span>{{ $t('huiyuanquanyi') }}</span>
                    </div>
                    <div class="nickName">
                      <span>{{ user.username }}</span>
                      <img
                        src="@/assets/image/vip1.png"
                        class="icon-vip-grade"
                        alt=""
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="cont-box">
                <div class="asset">
                  <div class="asset-item">
                    <span>{{ $t('zuoriyingli') }}</span>
                    <span>{{ user.today_profit }}</span>
                  </div>
                  <div class="asset-item">
                    <span>{{ $t('zongzichan') }}</span>
                    <span>{{ user.money }}</span>
                  </div>
                  <div class="asset-item">
                    <span>{{ $t('chucunjin') }}</span>
                    <span>{{ user.score }}</span>
                  </div>
                  <div class="asset-item">
                    <span>{{ $t('jinriyingli') }}</span>
                    <span>{{ user.profit }}</span>
                  </div>
                </div>
                <ul class="tx-box">
                  <li class="tx" @click="changeWithdraw">
                    <img
                      src="@/assets/image/zhuanchu.png"
                      class="icon"
                      alt=""
                    />
                    <span class="text">{{ $t('zhuanchu') }}</span>
                  </li>
                  <li class="tx" @click="changeRecharge">
                    <img src="@/assets/image/cunru.png" class="icon" alt="" />
                    <span class="text">{{ $t('cunru') }}</span>
                  </li>
                </ul>
                <ul class="list-box">
                  <li class="list" @click="changestting(2)">
                    <div class="list-text">{{ $t('zhanghuxinxni') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list" @click="changeBet">
                    <div class="list-text">{{ $t('lishizhangdan') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list" @click="changeCapitalDetail">
                    <div class="list-text">{{ $t('zijinminxi') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list">
                    <div class="list-text">{{ $t('renwuzhongxin') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list" @click="changeNotice">
                    <div class="list-text">{{ $t('xiaoxigonggao') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list" @click="changeShareFriend">
                    <div class="list-text">{{ $t('tuijianfenxiang') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                  <li class="list" @click="changeActionCenter">
                    <div class="list-text">{{ $t('huodongzhongxin') }}</div>
                    <img src="@/assets/image/you.png" class="arrow" alt="" />
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </van-pull-refresh>
    </div>
    <tab-bar></tab-bar>
  </div>
</template>
<script>
import TabBar from "@/components/tabbar/TabBar.vue";
import userApi from "@/api/user";
import storeAction from "@/store/typed-actions";
export default {
  name: "Personal",
  components: {
    TabBar,
  },
  data() {
    return {
      isLoading: false,
      user: "",
    };
  },
  methods: {
    onRefresh() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      userApi.userInfo().then((data) => {
        console.log(data.msg);
        this.user = data.msg;
        storeAction.setCurrentUserMoney(data.msg.money);
         storeAction.setCurrentUserGroup_id(data.msg.group_id);
        storeAction.setsessionStorage("money", data.msg.money);
        storeAction.setsessionStorage("group_id", data.msg.group_id);
        storeAction.setsessionStorage("idcard", data.msg.idcard);
          storeAction.setCurrentUserusercrud(data.msg.idcard);
        this.isLoading = false;
        this.$toast.clear();
      });
    },
    changestting(type) {
      this.$router.push({
        path: "/setting",
        query: {
          type: type,
        },
      });
    },
    changebaseinfo() {
      this.$router.push({
        path: "/baseinfo",
        query: {
          name: this.user.username,
        },
      });
    },
    changeActionCenter() {
      this.$router.push({
        path: "/actionCenter",
      });
    },
    changeShareFriend() {
      this.$router.push({
        path: "/shareFriend",
      });
    },
    changeNotice() {
      this.$router.push({
        path: "/Notice",
      });
    },
    changeBet() {
      this.$router.push({
        path: "/BetRecord",
      });
    },
    changeCapitalDetail() {
      this.$router.push({
        path: "/CapitalDetail",
      });
    },
    changeWithdraw() {
      this.$router.push({
        path: "/Withdraw",
        query: {
          group_id: this.user.group_id,
        },
      });
    },
    changeRecharge() {
      this.$router.push({
        path: "/Recharge",
        query: {
          group_id: this.user.group_id,
        },
      });
    },
    changego() {
      this.$router.go(-1);
    },
  },
  created() {
    this.onRefresh();
  },
};
</script>
<style lang="less" scoped>
.personal {
  height: 100%;
}
.p-account {
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  background: linear-gradient(to bottom, #46699a 80%, #040925 0%);
  height: 100%;
}
.bg-icon-setting {
  background: url("../assets/image/shezhi.png");
  background-size: 60%;
  width: 1.35rem;
  height: 1.35rem;
}
.bg-icon {
  width: 0.68rem;
  height: 0.68rem;
  margin-left: 0.1rem;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
}
.mescroll {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  -webkit-box-flex: 1;
  flex: 1;
}
.scroll-content {
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  padding-bottom: 1rem;
  padding-bottom: calc(1rem + constant(safe-area-inset-bottom));
  padding-bottom: calc(1rem + env(safe-area-inset-bottom));
}
.cont-head {
  padding-bottom: 0.8rem;
}
.user-head {
  text-align: center;
}
.user-avatar {
  position: relative;
  display: inline-block;
}
.avatar {
  width: 1.46rem;
  height: 1.46rem;
}
.grade-box {
}
.member-benefit {
  margin: 0.16rem 0;
  display: inline-block;
  span {
    color: #fff;
    font-size: 0.4rem;
  }
}
.icon-vip-equity {
  width: 0.5rem;
  height: 0.4rem;
  margin-right: 0.08rem;
}
.nickName {
  font-size: 0.4rem;
  color: #fff;
}
.icon-vip-grade {
  margin-left: 5px;
  width: 1rem;
}
.cont-box {
  background: #040925;
  padding-bottom: 0.4rem;
  flex: 1;
}
.asset {
  display: flex;
  padding: 0.2rem 0;
  border-radius: 4px;
  background-color: #1a243f;
  text-align: center;
  margin: 0 0.2rem;
  min-height: 1.2rem;
  margin-top: -0.6rem;
}
.asset-item {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  position: relative;
  border-right: 1px solid #3d5179;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  span {
    font-size: 0.38rem;
  }
}
.asset .asset-item span:first-child {
  margin-bottom: 0.1rem;
}
.tx-box {
  margin: 0.26rem;
  padding: 0.45rem 0;
  border-radius: 10px;
  background: #1a243f;
}
.tx-box,
.tx {
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.p-account .tx-box .tx:first-child {
  border-right: 1px solid #3d5179;
}
.tx {
  width: 50%;
}
.text {
  margin-left: 0.32rem;
  font-size: 0.4rem;
}
.list-box {
  border-radius: 0.1rem;
  background: #1a243f;
  margin: 0 0.2rem;
  padding: 0 0.24rem 0 0.3rem;
}
.list {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 0.5rem 0 0.35rem;
  border-bottom: 1px solid #3d5179;
}
.list-text {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  text-align: left;
  font-size: 0.4rem;
}
.arrow {
  width: 0.2rem;
  height: 0.4rem;
}
.p-account .list-box .list:last-child {
  border-bottom: none;
}
</style>