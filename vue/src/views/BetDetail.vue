<template>
  <div class="betRecord">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('buyxq')}}</div>
          <div class="right">
            <div class="slide-btn"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <div>
          <div class="bet-detail-type">
            <!-- 未中奖 -->
            <img src="@/assets/image/type.png" class="" alt="" />
            <!-- 已中奖 -->
            <div class="middle">
              <div>
                <span>SINGAPORE</span>
                <span class="fs-12 issue c_a0a0a0">{{$t('di')}}20211223186{{$t('lun')}}</span>
              </div>
              <div>
                <div class="order-count">
                  <p class="c_a0a0a0">{{$t('jine')}}</p>
                  <p class="count">{{ item.quantity }}u</p>
                </div>
                <div class="prize">
                  <p class="c_a0a0a0">{{$t('yingli')}}</p>
                  <p class="count c_ff7d20">
                    {{ item.status == 0 ? "----" : item.profit + "u" }}
                  </p>
                </div>
              </div>
            </div>
            <!-- 已经开奖 -->
            <div
              class="logo"
              v-if="item.status == 1 && item.whether_hit == 1"
            ></div>
            <div
              class="logo2"
              v-if="item.status == 1 && item.whether_hit == 0"
            ></div>
            <div class="logo1" v-if="item.status == 0"></div>
          </div>
          <div class="bet-detail-msg">
            <div class="list">
              <div class="list-item">
                <div class="title">{{ $t('orderNo') }}</div>
                <div class="content">{{ item.orderNo }}</div>
              </div>
              <div class="list-item">
                <div class="title">{{$t('plan_name')}}</div>
                <div class="content">{{ $t(item.plan_name) }}</div>
              </div>
              <div class="list-item">
                <div class="title">{{ $t('kaihaojieguo') }}</div>
                <div class="content" v-if="item.status != 0">
                  <div class="result-wrap">
                    <div class="result">
                      <div class="ball ball-orange">{{item.lottery_code?splitStr(item.lottery_code)[0]:''}}</div>
                      <span class="fs-16">+</span>
                      <div class="ball ball-orange">{{item.lottery_code?splitStr(item.lottery_code)[1]:''}}</div>
                      <span class="fs-16">+</span>
                      <div class="ball ball-orange">{{item.lottery_code?splitStr(item.lottery_code)[2]:''}}</div>
                      <span class="fs-16">=</span>
                      <div class="ball ball-red">{{item.lottery_code?splitStr(item.lottery_code)[3]:''}}</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="list-item">
                <div class="title">{{ $t('buyxx') }}</div>
                <div class="content">
                  <van-collapse v-model="activeNames">
                    <van-collapse-item name="1">
					<template #title><div>{{ $t('haoxx') }} <van-icon name="" /></div></template>
                      <ul class="fs-12 select-num-list">
                        <li class="select-num-item boderTop">
                          <span>{{ $t(item.play_method) }}</span>
                          <span>{{ item.quantity }}</span>
                          <span class="c_ff7d20">{{item.status == 0?'----': item.profit }}</span>
                        </li>
                      </ul>
                    </van-collapse-item>
                  </van-collapse>
                </div>
              </div>
              <div class="list-item">
                <div class="title">{{ $t('buysj') }}</div>
                <div class="content">{{ timeStr(item.createtime) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import time from "@/utils/time";
export default {
  name: "BetRecord",
  components: {},
  data() {
    return {
      activeNames: ["1"],
      item: "",
    };
  },
  methods: {
    timeStr(items) {
      return time.formatDatethree(items * 1000);
    },
    changego() {
      this.$router.go(-1);
    },
    splitStr(str) {
      return str.split(/\s+/);
    },
  },
  mounted() {
    console.log(JSON.parse(this.$route.query.item));
    this.item = JSON.parse(this.$route.query.item);
  },
};
</script>
<style lang="less" scoped>
.settingwarp {
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.g-content {
  padding: 0.3rem;
}
.bet-detail-type {
  position: relative;
  background-color: #1a243f;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 0.5rem;
  border-radius: 5px;
  img {
    width: 1.3rem;
    height: 1.3rem;
    border-radius: 50%;
  }
}
.middle {
  margin-left: 0.2rem;
  margin-right: 0.48rem;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
}
.middle > div:first-child {
  padding-bottom: 0.4rem;
  text-align: left;
  font-size: 0.35rem;
  border-bottom: 1px solid #3d5179;
}
.middle > div:last-child > div {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  text-align: center;
}
.middle > div:first-child .issue {
  margin-left: 0.1rem;
}
.middle > div:last-child {
  margin-top: 0.2rem;
  display: flex;
  font-size: 0.35rem;
}
.order-count {
  position: relative;
}
.count {
  margin-top: 0.2rem;
}
.order-count::after {
  content: "";
  display: block;
  width: 1px;
  height: 80%;
  background-color: #3d5179;
  position: absolute;
  right: 0;
  top: 50%;
  -webkit-transform: translateY(-50%);
  transform: translateY(-50%);
}
.prize {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  text-align: center;
}
.c_ff7d20 {
  color: #ff7d20;
}
.logo {
  background-image: url(../assets/image/yingli.png);
  position: absolute;
  top: 0;
  right: 0;
  width: 1.2rem;
  height: 1.52rem;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
  margin-top: 0;
}
.logo1 {
  background-image: url(../assets/image/shouli.png);
  position: absolute;
  top: 0;
  right: 0;
  width: 0.7rem;
  height: 1.52rem;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
  margin-top: 0;
}
.logo2 {
  background-image: url(../assets/image/weiyingli.png);
  position: absolute;
  top: 0;
  right: 0;
  width: 1.2rem;
  height: 1.52rem;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
  margin-top: 0;
}
.bet-detail-msg {
  border-radius: 5px;
  background-color: #1a243f;
  margin-top: 0.2rem;
}
.list {
  font-size: 0.38rem;
  padding-bottom: 0.4rem;
}
.list-item {
  text-align: left;
  padding-top: 0.4rem;
  .title {
    display: inline-block;
    color: #fff;
    padding: 0 0.4rem;
    height: 0.68rem;
    line-height: 0.68rem;
    border-top-right-radius: 0.35rem;
    border-bottom-right-radius: 0.35rem;
    background-color: #30385d;
  }
  .content {
    padding: 0.2rem 0.4rem;
    color: #a0a0a0;
  }
}
.result {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.ball {
  display: inline-block;
  border-radius: 50%;
  margin: 0.04rem;
  width: 0.6rem;
  height: 0.6rem;
  text-align: center;
  line-height: 0.6rem;
  color: #fff;
  background-position: center center;
  background-size: cover;
  font-size: 0.28rem;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.ball-orange {
  background: linear-gradient(to bottom, #ffb658, #ff6045);
}
.ball-red {
  background: linear-gradient(to bottom, #f8872c, #ff0000);
}
.select-num-item {
  text-align: center;
  padding: 5px 0;
}
.boderTop:before {
  content: " ";
  position: absolute;
  left: 0;
  top: 35px;
  right: 0;
  height: 1px;
  border-top: 1px solid #3d5179;
  color: #3d5179;
  -webkit-transform-origin: 0 0;
  transform-origin: 0 0;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.select-num-item span {
  display: inline-block;
  width: 33.33%;
}
</style>