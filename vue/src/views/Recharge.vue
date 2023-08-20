<template>
  <div class="recharge">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(70, 105, 154)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{ $t("cunru") }}</div>
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
                    <p class="num">{{ money }}</p>
                    <p>{{$t('zongzichan')}}(u)</p>
                  </div>
                </div>
                <div class="pay-box">
                  <!-- 虚拟钱包 -->
                  <div class="pay-type" v-if="usdt">
                    <div class="type-catalog">
                      <div class="bank-title">{{$t('saomacunru')}}</div>
                    </div>
                    <ul>
                      <li>
                        <div class="conetnt">
                          <div class="title">
                            <p class="fs-12">{{$t('TRC20')}}</p>
                            <h2>{{ usdt }}</h2>
                          </div>
                          <div class="radio">
                            <label class="advice"></label>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                  <!-- 银行卡 -->
                  <div class="pay-type" v-if="yinhang">
                    <div class="type-catalog">
                      <div class="bank-title">{{$t('wangyincunru')}}</div>
                    </div>
                    <ul>
                      <li><div class="conetnt"><div class="title"><h2>{{ yinhang }}</h2></div></div></li>
                      <li><div class="conetnt"><div class="title"><h2>{{ xingming }}</h2></div></div></li>
                      <li><div class="conetnt"><div class="title"><h2>{{ kahao }}</h2></div></div></li>
                    </ul>
                    <!-- <ul>
                      <li>
                        <div class="conetnt" @click="selectActive = 1">
                          <div class="title">
                            <h2>平安银行</h2>
                            <p class="fs-12">123</p>
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
                    </ul> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <alert-pop
        :alertType="alertType"
        :Tips="TipsTxt"
        v-if="true"
        @showAletfalse="showAletfalse"
      ></alert-pop>
    </div>
  </div>
</template>

<script>
import AlertPop from "@/components/alert/AlertPop.vue";
import userApi from "@/api/user";
export default {
  name: "recharge",
  components: {
    AlertPop,
  },
  data() {
    return {
      money: this.$t('jiazaizhong'),
      usdt: null,
      yinhang: null,
      kahao: null,
      xingming: null,
      alertType: null,
      TipsTxt: this.$t('Tips.youke'),
      PopAlert: false,
    };
  },
   computed: {
    group_id() {
      return this.$store.state.group_id;
    },
  },
  methods: {
    showAletfalse() {
      this.PopAlert = false;
      this.$router.go(-1);
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
        .recharge()
        .then((data) => {
          console.log(data.data);
          this.money = data.data.money;
          this.usdt = data.data.usdt;
          this.yinhang = data.data.bankname;
          this.kahao = data.data.banknum;
          this.xingming = data.data.bankxm;
          this.$toast.clear();
          this.PopAlert = true;
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
  },
  mounted() {
	console.log(this.group_id);
  },
  created() {
    this.info();
      if (this.group_id != 1) {
      this.alertType = 1;
    }
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
  margin-top: 0.15rem;
}
ul li .conetnt .title p {
  color: #a0a0a0;
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
.bg_init {
  background-color: inherit;
}
.qrcode-body {
  width: 7rem;
  min-height: 7rem;
  margin: 0 auto;
  margin-top: 1rem;
  padding: 0.4rem 0;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  border-radius: 0.1rem;
  background: #30385d;
}
#qrcode {
  display: inline-block;
  img{
    width: 100%;
  height:100%
  }
}
</style>