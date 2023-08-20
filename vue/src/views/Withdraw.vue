<template>
  <div class="withdraw">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(70, 105, 154)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18" @click="alertTypetitle = 2">
            {{ $t("zhuanchu") }}
          </div>
          <div class="right">
            <div class="btn-save fs-16"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
        <div class="g-content flex-column">
          <div class="mescroll">
            <div>
              <div class="scroll-wrapper">
                <div class="balance">
                  <div class="money-box">
                    <p class="num" v-if="infoList">{{ infoList.money }}</p>
                    <p>{{ $t("zongzichan") }}(u)</p>
                  </div>
                </div>
                <div class="withdraw-count">
                  <div class="title">{{ $t("zhuanchujine") }}</div>
                  <van-field
                    v-model="money"
                    type="number"
                    :placeholder="$t('plzhuanchu')"
                  >
                    <template #left-icon>
                      <img
                        src="@/assets/image/jinbi.png"
                        class="left-form-icon"
                        alt=""
                      />
                    </template>
                  </van-field>
                  <div class="tips">
                    {{ $t("zhuanchuTps") }}
                  </div>
                </div>
                <div class="pay-box">
                  <!-- 虚拟钱包 -->
                  <div class="pay-type">
                    <div class="type-catalog">
                      <div class="bank-title">{{ $t("xuanzehb") }}</div>
                    </div>
                    <ul>
                      <li>
                        <div
                          class="conetnt"
                          @click="selectActive = 1"
                          v-if="
                            infoList &&
                            infoList.pay_list &&
                            infoList.pay_list.length != 0 &&
                            infoList.pay_list[0]
                          "
                        >
                          <div class="title">
                            <h2>{{ infoList.pay_list[0].acc_name }}</h2>
                            <p class="fs-12">{{ infoList.pay_list[0].acc }}</p>
                          </div>
                          <div class="radio">
                            <label
                              class="advice"
                              :class="
                                selectActive == 1 ? 'advice-hl' : 'advice-nor'
                              "
                            ></label>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
					<div class="pay-type">
                    <div class="type-catalog">
                      <div class="bank-title">{{ $t("xuanzeyh") }}</div>
                    </div>
                    <ul>
                      <li>
                        <div
                          class="conetnt"
                          @click="selectActive = 0"
                          v-if="
                            infoList &&
                            infoList.pay_list &&
                            infoList.pay_list.length != 0 &&
                            infoList.pay_list[1]
                          "
                        >
                          <div class="title">
                            <h2 v-if="infoList">
                              {{ infoList.pay_list[1].acc_bank }}
                            </h2>
                            <p class="fs-12">
                              {{ infoList.pay_list[1].acc_name }}
                            </p>
                          </div>
                          <div class="radio">
                            <label
                              class="advice"
                              :class="
                                selectActive == 0 ? 'advice-hl' : 'advice-nor'
                              "
                            ></label>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- 提交 -->
          <van-button
            type="primary"
            block
            class="Btnsub"
            @click="changeSubmit"
            >{{ $t("tijiaoshenqing") }}</van-button
          >
        </div>
      </header>
    </div>
    <alert-pop
      :alertType="alertType"
      :Tips="TipsTxt"
      v-if="PopAlert"
      @showAletfalse="showAletfalse"
    ></alert-pop>
    <!-- 安全码弹出框提交申请的时候弹出 -->
    <van-popup v-model:show="showsecurity" class="securityalert">
      <div>
        <div class="safe-code">
          <div class="title ta-c c_fff">{{ $t("shuruanquanma") }}</div>
          <div class="paycode">
            <van-password-input
              :value="showsecurityValue"
              :focused="showKeyboard"
              @focus="showKeyboard = true"
            />
          </div>
        </div>
      </div>
    </van-popup>
    <van-number-keyboard
      v-model="showsecurityValue"
      :show="showsecurity"
      @blur="showsecurity = false"
    />
  </div>
  <!-- 基本逻辑,1判断是否游客-2要完成基本资料-3如果没有银行卡就绑定银行卡,4未设置安全码设置安全码,5提交申请输入安全码 -->
</template>

<script>
import AlertPop from "@/components/alert/AlertPop.vue";
import userApi from "@/api/user";
export default {
  name: "Withdraw",
  components: {
    AlertPop,
  },
  data() {
    return {
      selectActive: 0,
      showsecurity: false,
      showsecurityValue: "",
      alertType: null,
      infoList: [],
      isTrue: null,
      TipsTxt: this.$t('Tips.youke'),
      PopAlert: false,
      money: "",
      alertTypetitle: 2,
    };
  },
  watch: {
    showsecurityValue(value) {
      if (value.length === 6) {
        // 调用接口
        this.showsecurity = false;
        this.Withdrawal();
      }
    },
  },

  methods: {
    showAletfalse() {
      this.PopAlert = false;
      this.$router.go(-1);
    },
    changeSubmit() {
      // 个人资料完整验证废除
      //判断是否游客
      if (!this.usercrud) {
        userApi.userInfo().then((data) => {
          this.user = data.msg;
          this.$router.push({
            path: "/baseinfo",
            query: {
              name: data.msg.username,
            },
          });
        });
        return;
      }
      if (!this.infoList.pay_list) {
        // 是否有银行卡

        this.$router.push({
          path: "/bankCardManage",
          query: {
            type: 0,
          },
        });
      } else if (!this.isTrue) {
        // 是否有安全码
        this.$router.push({
          path: "/setSecurityCode",
          query: {
            isTrue: this.isTrue,
          },
        });
      } else {
        // 提现请求
        if (!this.money) {
          this.$toast(this.$t('Tips.pltxje'));
        } else if (this.money < 100) {
          this.$toast(this.$t('Tips.zdtx'));
        } else {
          this.showsecurity = true;
        }
      }
    },
    Withdrawal() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      userApi
        .withdrawAction(this.selectActive, this.money, this.showsecurityValue)
        .then((data) => {
          console.log(data.data);
          this.infoList = data.data;
          this.$toast.clear();
          this.alertType = 3;
          this.TipsTxt = this.$t('Tips.tjcg');
          this.PopAlert = true;
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
    ChangeAuth() {
      this.$router.push({
        path: "/defaultAvatar",
      });
    },
    changego() {
      this.$router.go(-1);
    },
    info() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      userApi
        .withdrawInfo()
        .then((data) => {
          console.log(data.data);
          this.infoList = data.data;
          this.$toast.clear();
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
  },
  computed: {
    group_id() {
      return this.$store.state.group_id;
    },
    usercrud() {
      console.log(this.$store.state.usercrud, "12");
      return this.$store.state.usercrud;
    },
  },
  created() {
    this.info();
    // this.group_id = this.$route.query.group_id;
    if (this.group_id != 1) {
      // 打开弹窗
      this.alertType = 1;
      this.PopAlert = true;
    }
    userApi.safetyInfo().then((data) => {
      this.isTrue = data.data.isTrue;
    });
  },
};
</script>
<style lang="less" scoped>
.g-content {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  overflow: scroll;
  text-align: center;
  color: #a0a0a0;
}
.mescroll {
  width: 100%;
  height: 100%;
  overflow-y: auto;
}
.balance {
  position: relative;
  height: 3.5rem;
  text-align: center;
  background-color: #46699a;
}
.money-box {
  color: #fff;
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  font-size: 0.4rem;
}
.num {
  font-size: 0.7rem;
  margin-bottom: 0.1rem;
}
.withdraw-count {
  background-color: #1a243f;
  padding: 0.4rem 0;
  .title {
    font-weight: bold;
    padding: 0 16px;
    text-align: left;
    color: #fff;
    font-size: 0.4rem;
  }
}

.tips {
  color: #a0a0a0;
  margin-top: 0.1rem;
  font-size: 0.3rem;
  padding: 0 16px;
  white-space: pre-wrap;
  text-align: left;
}
.van-cell::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #3d5179;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.left-form-icon {
  width: 20px;
  margin-right: 5px;
}
.pay-box {
}
.pay-type {
}
.type-catalog {
  height: 1.1rem;
  line-height: 1.1rem;
  position: relative;
}
.type-catalog::before {
  content: "";
  display: block;
  position: absolute;
  width: 0.1rem;
  height: 60%;
  background-color: #4b77ff;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}
.bank-title {
  font-weight: bold;
  padding-left: 0.3rem;
  text-align: left;
  color: #fff;
  font-size: 0.4rem;
}
li {
  background-color: #1a243f;
  padding: 0 0.2rem;
}
ul li:last-child .conetnt {
  border: none;
}
.conetnt {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  border-bottom: 1px solid #ccc;
  padding: 0.3rem 0;
}
li .conetnt .title {
  margin-left: 0.3rem;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  text-align: left;
}
.title h2 {
  font-size: 0.35rem;
  color: #fff;
}
ul li .conetnt .title p {
  color: #a0a0a0;
  margin-top: 0.1rem;
}
.conetnt .radio {
  padding-left: 0.4rem;
}
.advice {
  height: 0.6rem;
  width: 0.6rem;
  display: inline-block;
  background-repeat: no-repeat;
  background-position: center;
  vertical-align: middle;
  background-size: contain;
}
.advice-hl {
  background-image: url(../assets/image/xuanze.png);
}
.advice.advice-nor {
  background-image: url(../assets/image/weixuanze.png);
}
.Btnsub {
  color: rgb(255, 255, 255);
  background: rgb(125, 88, 198);
  border-color: rgb(125, 88, 198);
  position: absolute;
  bottom: 0;
}
</style>