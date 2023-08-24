<template>
  <div class="betRoom">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
          <van-icon name="arrow-left" color="#fff" size="20px" />
        </div>
        <div class="middle fs-18">
          <van-notice-bar :scrollable="false" color="#fff">
            <van-swipe
              vertical
              class="notice-swipe"
              :autoplay="3000"
              :duration="1000"
              :show-indicators="false"
            >
              <van-swipe-item>{{ name }}</van-swipe-item>
              <van-swipe-item>{{ title }}</van-swipe-item>
              <!-- <van-swipe-item>内容 3</van-swipe-item> -->
            </van-swipe>
          </van-notice-bar>
        </div>
        <div class="right" @click="showPopoverChange">
          <van-icon
            name="plus"
            class="icon-arrow-bottom"
            :class="showPopover ? 'angleRote' : ''"
            color="#fff"
            size="20px"
          />
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="g-content">
      <div class="bet-head f-a-c">
        <div class="left">
          <p class="cut-off">
            {{ $t("juli") }} {{ issue.issue }} {{ $t("lunjiezhi") }}
          </p>
          <template v-if="game_status == 1 && !whether_close">
            <van-count-down :time="time">
              <template #default="timeData">
                <span class="block">{{ timeData.minutes }}</span>
                <span class="colon">:</span>
                <span class="block">{{ timeData.seconds }}</span>
              </template>
            </van-count-down>
          </template>
          <template v-else-if="game_status == 1 && whether_close"
            ><p class="stopBet">{{ $t("yifengpan") }}</p></template
          >
          <template v-else
            ><p class="stopBet">{{ $t("yiguanb") }}</p></template
          >
        </div>
        <div class="right">
          <van-loading type="spinner" v-if="user_quantityShow" />
          <div v-else @click="heart(), (user_quantityShow = true)">
            <div class="jine f-a-c">
              <span @click="aa">{{ $t("zongzichan") }}</span>
              <img
                src="@/assets/image/yuanbao.png"
                class="icon icon-gold"
                alt=""
              />
              <span>{{ user_quantity.money }}</span>
            </div>
            <div class="caijin f-a-c">
              <span>{{ $t("chucunjin") }}</span>
              <img
                src="@/assets/image/cunchu.png"
                class="icon icon-gold"
                alt=""
              />
              <span>{{ user_quantity.score }}</span>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="previousList">
          <div
            v-if="last_issue"
            class="pre-item Pc28"
            @click="(showIssue = !showIssue), showIssueChange()"
          >
            <p class="issue">
              {{ $t("di") }}<span class="c_fe5050">{{ last_issue.issue }}</span
              >{{ $t("qi") }}
            </p>
            <div class="result">
              <span>{{ last_issue.site_1 }}</span>
              <span class="fs-16">+</span>
              <span>{{ last_issue.site_2 }}</span>
              <span class="fs-16">+</span>
              <span>{{ last_issue.site_3 }}</span>
              <span class="fs-16">=</span>
              <span>{{ last_issue.code }}</span>
            </div>
            <p>
              {{ "(" + $t(last_issue.base[0]) + "," + $t(last_issue.base[1]) + ")" }}
            </p>
            <van-icon
              name="arrow-down"
              color="#fff"
              size="16px"
              :class="showIssue ? 'angleRotestow' : 'icon-arrow-bottoms'"
            />
          </div>
        </div>
      </div>
      <div class="scroll-thead">
        <span>{{ $t("yonghu") }}</span>
        <span>{{ $t("lunshu") }}</span>
        <span>{{ $t("jiaoyipinglei") }}</span>
        <span>{{ $t("danlunjiaoyi") }}</span>
        <span style="flex: none; width: 15%">{{ $t("caozuo") }}</span>
      </div>
      <div class="swiper-container scroller-box" id="swiperBox" ref="swiperBox">
        <!--  :class="transformSwiper" -->
        <div class="swiper-wrapper">
          <div
            class="swiper-slide slide-text"
            id="liswiper"
            style="transition-duration: 2000ms"
            :style="transformSwiper"
          >
            <!-- :style="transformSwiper" -->

            <ul class="msg-box fs-12" ref="swipercontant">
              <li class="msg-item">
                <div class="msg-type_1">
                  <div>
                    <span v-if="game_status == 1">{{ $t("pljoin") }}</span>
                    <span v-else>{{ $t("pltingshou") }}</span>
                  </div>
                </div>
              </li>

              <li
                class="msg-item"
                v-for="(item, index) in message"
                :key="index"
              >
                <div class="bet-msg">
                  <div class="flex1">{{ item.nickname }}</div>
                  <div class="flex1">{{ item.issue }}</div>
                  <div class="flex1">{{ $t(item.play_method) }}</div>
                  <div class="flex1">{{ item.quantity }}</div>
                  <div class="flex1 follow-btn" @click="showFollowPayFun(item)">
                    {{ $t("gengou") }}
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="footer-chat">
        <div class="input-box" @click="ChatTps">
          <input type="text" disabled="disabled" name="" id="" />
        </div>
        <div class="btn-bet" @click="changeShowPay">{{ $t("gouru") }}</div>
      </div>
    </div>
    <!-- 右上角遮罩层 -->
    <van-overlay
      v-model:show="showPopover"
      @click="showPopover = false"
      style="z-index: 2005"
    >
      <div class="head-menu">
        <ul>
          <li class="boderBottom" @click="changeRecharge">
            <img src="@/assets/image/tab1.png" alt="" />
            <span>{{$t('lijicunru')}}</span>
          </li>
          <li class="boderBottom" @click="changeDetail">
            <img src="@/assets/image/jieguo.png" alt="" />
            <span>{{$t('kaihaojieguo')}}</span>
          </li>
          <li class="boderBottom" @click="changeTrendChart">
            <img src="@/assets/image/qushi.png" alt="" />
            <span>{{$t('kaihaoqushi')}}</span>
          </li>
          <li class="boderBottom" @click="changeLotteryExplain">
            <img src="@/assets/image/chanpin.png" alt="" />
            <span>{{$t('chanpingshuoming')}}</span>
          </li>
          <li class="boderBottom" @click="changebetRecord">
            <img src="@/assets/image/lishi.png" alt="" />
            <span>{{$t('lishizhangdan')}}</span>
          </li>
          <li class="boderBottom" @click="changehuancun">
            <img src="@/assets/image/huancun.png" alt="" />
            <span>{{$t('qingchuhuancun')}}</span>
          </li>
        </ul>
      </div>
    </van-overlay>
    <!-- 前几期遮罩层 -->
    <van-overlay v-model:show="showIssue" @click="showIssue = false">
      <div class="issuecontant">
        <div class="previousList">
          <template v-if="issueList">
            <div
              class="pre-item Pc28"
              @click="showIssue = !showIssue"
              v-for="(item, index) in issueList"
              :key="index"
            >
              <p class="issue">
                {{$t('di')}}<span>{{ item.issue }}</span
                >{{$t('qi')}}
              </p>
              <div class="result">
                <span>{{ item.site_1 }}</span>
                <span class="fs-16">+</span>
                <span>{{ item.site_2 }}</span>
                <span class="fs-16">+</span>
                <span>{{ item.site_3 }}</span>
                <span class="fs-16">=</span>
                <span>{{ item.code }}</span>
              </div>
              <p>
                {{ "(" + $t(item.base[0]) + "," + $t(item.base[1]) + ")" }}
              </p>
            </div>
          </template>
        </div>
      </div>
    </van-overlay>
    <!-- 购入键盘 -->
    <div style="z-index: 2003;!important">
      <van-popup
        v-model:show="showPay"
        position="bottom"
        :style="{ height: '60%', width: '100%' }"
      >
        <div class="bet-panel">
          <div class="panel-head">
            <ul class="category">
              <li
                class="category-item"
                :class="swiperKeySlide == 0 ? 'PayActive' : ''"
                @click="(swiperKeySlide = 0), changeTabPay(0)"
              >
                {{$t('changgui')}}
              </li>
              <li
                class="category-item"
                :class="swiperKeySlide == 1 ? 'PayActive' : ''"
                @click="(swiperKeySlide = 1), changeTabPay(1)"
              >
                {{$t('shuzi')}}
              </li>
              <!-- <li
                class="category-item"
                :class="swiperKeySlide == 2 ? 'PayActive' : ''"
                @click="(swiperKeySlide = 2), changeTabPay(2)"
              >
                特殊
              </li> -->
            </ul>
          </div>
          <div class="panel-body">
            <div class="oddsExplain">
              <div class="tips" v-if="num_code">
                {{$t('yinglihezhi')}}:{{
                  oddsList.num_code[num_code]
                    ? oddsList.num_code[num_code]
                    : num_code
                }}
              </div>
              <div class="odds">
                <img src="@/assets/image/deng.png" alt="" />
                <span>{{$t('shouyijieshao')}}</span>
              </div>
            </div>
            <div class="swiper-container" id="PaySwiper">
              <div class="swiper-wrapper">
                <div class="swiper-slide" style="overflow-y: auto">
                  <ul class="clear select-list">
                    <li
                      class="item"
                      :class="activePay == index ? 'active' : ''"
                      @click="
                        (activePay = index),
                          changeShowPayInput(item.class, item.class_name)
                      "
                      v-for="(item, index) in oddsList.rule_base"
                      :key="index"
                    >
                      <div class="square">
                        <div class="text">{{ $t(item.class_name) }}</div>
                        <div class="oddsPay">{{ item.odds }}</div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="swiper-slide" style="overflow-y: auto">
                  <ul class="clear select-list">
                    <li
                      class="item"
                      :class="activePay == index ? 'active' : ''"
                      @click="
                        (activePay = index),
                          changeShowPayInput(item.class, item.class_name)
                      "
                      v-for="(item, index) in oddsList.rule_num"
                      :key="index"
                    >
                      <div class="square">
                        <div class="text">{{ item.class_name }}</div>
                        <div class="oddsPay">{{ item.odds }}</div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <div class="panel-foot">
            <div class="leftCount">
              <div class="icon-cart">
                <img src="@/assets/image/payu.png" alt="" />
              </div>
              <div class="bet-status">{{$t('zanweigouru')}}</div>
            </div>
            <div class="rightBetButton">
              <div>1{{$t('qigou')}}</div>
            </div>
          </div>
        </div>
      </van-popup>
    </div>
    <!-- 购买列表里的遮罩层，最后输入的数量 -->
    <div style="z-index: 2004;!important">
      <van-popup
        v-model:show="showPayInput"
        position="bottom"
        :style="{ height: '18%', width: '100%' }"
      >
        <div class="bet-sub-panel">
          <!--
          <ul>
            <li
              :class="betselect == 0 ? 'betactive' : ''"
              @click="(betselect = 0), InputValeue(0)"
            >
              <img src="@/assets/image/bet5.png" alt="" />
            </li>
            <li
              :class="betselect == 1 ? 'betactive' : ''"
              @click="(betselect = 1), InputValeue(1)"
            >
              <img src="@/assets/image/bet10.png" alt="" />
            </li>
            <li
              :class="betselect == 2 ? 'betactive' : ''"
              @click="(betselect = 2), InputValeue(2)"
            >
              <img src="@/assets/image/bet50.png" alt="" />
            </li>
            <li
              :class="betselect == 3 ? 'betactive' : ''"
              @click="(betselect = 3), InputValeue(3)"
            >
              <img src="@/assets/image/bet300.png" alt="" />
            </li>
            <li
              :class="betselect == 4 ? 'betactive' : ''"
              @click="(betselect = 4), InputValeue(4)"
            >
              <img src="@/assets/image/bet500.png" alt="" />
            </li>
            <li
              :class="betselect == 5 ? 'betactive' : ''"
              @click="(betselect = 5), InputValeue(5)"
            >
              <img src="@/assets/image/betall.png" alt="" />
            </li>
          </ul>
           -->
          <div class="bottom-form">
            <span class="c_a0a0a0">{{$t('gourushuliang')}}：</span>
            <input type="number" v-model="sendValue" class="" />
            <div class="ripple bet-btn" @click="playOrder">{{$t('gouru')}}</div>
          </div>
        </div>
      </van-popup>
    </div>
    <!-- 跟购-->
    <van-overlay
      v-model:show="showFollowPay"
      @click="showFollowPay = false"
      style="z-index: 2005"
    >
      <div class="bet fs-12">
        <div class="bet-title fs-14">{{$t('quedinggengou')}}?</div>
        <div class="bet-content">
          <div>
            <span class="c_a0a0a0">{{$t('wanjia')}}：</span>
            <span>{{ FollPayItem.nickname }}</span>
          </div>
          <div>
            <span class="c_a0a0a0">{{$t('lunshu')}}：</span>
            <span>{{ FollPayItem.issue }}</span>
          </div>
          <div class="c_a0a0a0">
            <span>{{$t('leibe')}}</span>
            <span>{{$t('jine')}}</span>
          </div>
          <ul>
            <li>
              <span>{{ $t(item.FollPayItem.play_method) }}</span>
              <span>{{ FollPayItem.quantity }}</span>
            </li>
          </ul>
        </div>
        <div class="bet-footer">
          <van-button
            type="primary"
            class="btn-confirm"
            size="small"
            round
            @click="FollmePay(FollPayItem)"
            >{{$t('queren')}}</van-button
          >
          <van-button type="primary" class="btn-canle" size="small" round
            >{{$t('quxiao')}}</van-button
          >
        </div>
      </div>
    </van-overlay>
    <alert-pop
      :alertType="alertType"
      :Tips="TipsTxt"
      v-if="PopAlert"
      @showAletfalse="showAletfalse"
    ></alert-pop>
    <!-- 中奖消息 -->
    <div
      class="Xwarp"
      id="xwarpdonghua"
      v-if="Myprize.length != 0 && donghuaShow"
      :style="transformXwarp"
      ref="boxdonghua"
    >
      <div>
        {{$t('gongxi')}}{{ Myprize[0].nickname }}{{$t('zai')}}{{ Myprize[0].game_name }}{{$t('yingli')}}{{
          Myprize[0].profit
        }}U
      </div>
    </div>
  </div>
</template>
<script>
import Swiper from "swiper";
import "swiper/dist/css/swiper.css";
import homeApi from "@/api/home";
import AlertPop from "@/components/alert/AlertPop.vue";
export default {
  name: "BetRoom",
  components: { AlertPop },
  data() {
    return {
      time: "",
      showPopover: false,
      showIssue: false,
      TopPX: 0,
      transformSwiper: "transform:translate3d(0px, -" + 0 + "px, 0px)",
      showPay: false,
      activePay: null,
      showPayInput: false,
      swiperKeySlide: 0,
      PaySwiperexample: null,
      betselect: null,
      id: null,
      game_id: null,
      oddsList: null,
      num_code: null,
      sendValue: "",
      last_issue: "",
      issueList: null,
      issue: "",
      name: "",
      title: "",
      TouchStart: false,
      showFollowPay: false,
      message: [],
      game_status: 1,
      PopAlert: false,
      TipsTxt: "",
      alertType: 1,
      user_quantity: "",
      user_quantityShow: true,
      class: "",
      FollPayItem: "",
      whether_close: null,
      timeDataFun: null,
      daodibu: false,
      Myprize: [],
      NOprize: [],
      preizeIsPush: false,
      issuePreize: "",
      timedonghua: null,
      transformXwarp: "translate3d(0, 0px, 0px);",
      donghuaShow: true,
    };
  },
  watch: {
    issuePreize(newv, old) {
      if (newv != old) {
        this.preizeIsPush = false;
        // transform: translate3d(0, 0px, 0px);
      }
    },
    // donghuaShow() {
    //   let that=this
    //   setInterval(() => {
    //     that.donghuaShow = false;
    //     console.log(that.donghuaShow)
    //   }, 5000);
    // },
  },
  computed: {
    money() {
      console.log(this.$store.state.money);
      return this.$store.state.money;
    },
    currentUserId() {
      console.log(this.$store.state.currentUserId);
      return this.$store.state.currentUserId;
    },
  },
  methods: {
    InputValeue(type) {
      if (type == 0) {
        this.sendValue = 5;
      } else if (type == 1) {
        this.sendValue = 10;
      } else if (type == 2) {
        this.sendValue = 50;
      } else if (type == 3) {
        this.sendValue = 300;
      } else if (type == 4) {
        this.sendValue = 500;
      } else {
        // 全下
        this.sendValue = this.user_quantity.money * 1 - 1;
      }
    },
    // 下注接口
    playOrder() {
      // if (this.sendValue * 1 > this.user_quantity.money * 1) {
      //   this.$toast("您的资产不足请存入");
      //   return;
      // }
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .playOrder(
          this.issue.issue,
          this.id,
          this.game_id,
          this.class,
          this.sendValue
        )
        .then((data) => {
          this.$toast.clear();
          this.showPay = false;
          this.showPayInput = false;
          this.num_code = null;
          this.message.push(data.data);
          this.daodibu = false;
          this.$refs.swiperBox.scrollTop = 99999;
          this.$toast(this.$t('xiadanchenggong'));
          //  localStorage.setItem("money", (this.user_quantity.money*1)- ( this.sendValue*1));
          this.user_quantity.money = data.data.after_quantity;
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
    changego() {
      this.$router.go(-1);
    },
    showFollowPayFun(item) {
      if (this.game_status != 1 || this.whether_close) {
        this.TipsTxt = this.$t('guanbitg');
        this.PopAlert = true;
        return;
      }
      if (item.issue == this.issue.issue) {
        this.FollPayItem = item;
        this.showFollowPay = true;
      } else {
        this.TipsTxt = this.$t('xiangoudl');
        this.PopAlert = true;
      }
    },
    showAletfalse() {
      this.PopAlert = false;
    },
    changeDetail() {
      this.$router.push({
        path: "/lotteryDetail",
        query: {
          game_id: this.game_id,
          name: this.name,
        },
      });
    },
    changeRecharge() {
      this.$router.push({
        path: "/recharge",
      });
    },
    changebetRecord() {
      this.$router.push({
        path: "/betRecord",
      });
    },
    changeLotteryExplain() {
      this.$router.push({
        path: "/lotteryExplain",
      });
    },
    changeTrendChart() {
      this.$router.push({
        path: "/trendChart",
        query: {
          game_id: this.game_id,
          name: this.name,
        },
      });
    },
    showPopoverChange() {
      if (!this.showIssue) {
        this.showPopover = !this.showPopover;
      } else {
        this.showIssue = false;
      }
    },
    showIssueChange() {
      homeApi
        .issueList(this.game_id)
        .then((data) => {
          console.log(data.data);
          let list = data.data.slice(1, 5);
          this.issueList = list;
        })
        .catch((err) => {
          this.$toast.fail(err.msg);
        });
    },
    changeShowPay() {
      if (this.game_status == 1 && !this.whether_close) {
        this.showPay = true;
      } else {
        this.TipsTxt = this.$t('guanbitg');
        this.PopAlert = true;
      }
      let that = this;
      setTimeout(() => {
        that.PaySwiperexample = new Swiper("#PaySwiper", {
          observer: true, //修改swiper自己或子元素时，自动初始化swiper
          observeParents: true, //修改swiper的父元素时，自动初始化swiper
          // 如果需要分页器
          pagination: ".swiper-pagination",
          // 如果需要前进后退按钮
          nextButton: ".swiper-button-next",
          prevButton: ".swiper-button-prev",
          onSlideChangeEnd: function (swiper) {
            swiper.activeIndex;
            that.swiperKeySlide = swiper.activeIndex;
            that.activePay = null;
            that.num_code = null;
            that.class = "";
            that.sendValue = "";
          },
        });
      }, 300);
    },
    changeShowPayInput(classname, itemName) {
      this.class = classname;
      this.betselect = null;
      this.num_code = itemName;
      this.showPayInput = true;
      this.sendValue = "";
    },
    changeTabPay(index) {
      this.sendValue = "";
      this.activePay = null;
      this.class = "";
      this.num_code = null;
      this.PaySwiperexample.slideTo(index, 300, false);
    },
    heart() {
      homeApi
        .heart(this.id, this.game_id)
        .then((data) => {
          this.last_issue = data.data.last_issue;
          this.issue = data.data.issue;
          this.game_status = data.data.game_status;
          this.user_quantity = data.data.user_quantity;
          this.time = data.data.issue.openTime1 * 1000;
          this.whether_close = data.data.issue.whether_close;
          this.user_quantityShow = false;
          this.$toast.clear();
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
    difference(aArray, bArray) {
      const bArraySet = new Set(bArray);
      const resultArray = aArray.filter((item) => !bArraySet.has(item));
      return Array.from(resultArray);
    },
    getResultArray(key, aArray, bArray) {
      const aArraySimple = aArray.map((i) => i[key]); // 根据唯一标识对象数组转换为简单数组
      const bArraySimple = bArray.map((i) => i[key]);
      const allData = [...aArray, ...bArray];
      const resultArraySimple = Array.from(
        this.difference(aArraySimple, bArraySimple)
      );
      console.log(
        resultArraySimple.map((i) => allData.find((j) => j[key] === i))
      );
      return resultArraySimple.map((i) => allData.find((j) => j[key] === i));
    },
    getDifferenceSet(arr1, arr2) {
      return arr1
        .map(JSON.stringify)
        .concat(arr2.map(JSON.stringify))
        .filter((v, i, arr) => {
          return arr.indexOf(v) === arr.lastIndexOf(v);
        })
        .map(JSON.parse);
    },
    FollmePay(item) {
      console.log("跟购");
      homeApi
        .playOrder(
          item.issue,
          this.id,
          this.game_id,
          item.play_type,
          item.quantity
        )
        .then((data) => {
          this.$toast.clear();
          this.message.push(data.data);
          this.$toast(this.$t('xiadanchenggong'));
          this.user_quantity.money = this.user_quantity.money =
            data.data.after_quantity;
          this.$refs.swiperBox.scrollTop = 99999;
          this.daodibu = false;
        })
        .catch((err) => {
          this.$toast.fail(err.msg);
        });
    },
    ChatTps() {
      this.$toast(this.$t('lttip'));
    },
    changehuancun(){
         location.reload();
    },
  },

  mounted() {
    // let that = this;
    // new Swiper("#listSwiper", {
    //   //竖屏滚动
    //   direction: "vertical",
    //   // 如果需要分页器
    //   pagination: ".swiper-pagination",
    //   // 如果需要前进后退按钮
    //   nextButton: ".swiper-button-next",
    //   prevButton: ".swiper-button-prev",
    //   speed: 300, //滚动速度
    // onTouchStart() {
    //   if (
    //     that.$refs.liRef.offsetHeight >
    //     that.$refs.swiperWapperRef.offsetHeight
    //   ) {
    //     that.TouchStart = true;
    //     console.log(that.$refs.liRef.style.transform)
    //     console.log("滚动了");
    //   }
    // },
    // });

    // if (this.Myprize) {
    //   that.donghuaShow = true;
    //   setInterval(() => {
    //     that.Myprize.shift();
    //   }, 5000);
    // }
  },
  onUpdated() {},
  unmounted() {
    clearInterval(this.timeDataFun);
    this.timeDataFun = null;
  },
  created() {
    let that = this;
    this.$toast.loading({
      message: this.$t('jiazaizhong'),
      forbidClick: true,
      duration: 0,
    });

    this.id = this.$route.query.id;
    this.game_id = this.$route.query.game_id;
    this.name = this.$route.query.name;
    this.title = this.$t(this.$route.query.title);
    homeApi
      .oddsList(this.id)
      .then((data) => {
        this.oddsList = data.data;
        this.$toast.clear();
      })
      .catch((err) => {
        this.$toast.clear();
        this.$toast.fail(err.msg);
      });
    this.heart();
    that.$nextTick(() => {
      this.timeDataFun = setInterval(() => {
        // 对接接口就是轮询后有新数据就进行滚动
        //需要定时执行的代码
        homeApi
          .heart(this.id, this.game_id)
          .then((data) => {
            // this.Myprize = [];
            // this.NOprize = [];
            this.last_issue = data.data.last_issue;
            if (!this.preizeIsPush && data.data.hit_notice) {
              this.Myprize = [];
              this.Myprize.push(...data.data.hit_notice);
              // for (let i = 0; i < data.data.hit_notice.length; i++) {
              //   if (data.data.hit_notice[i].user_id == that.currentUserId) {
              //     this.Myprize.push(data.data.hit_notice[i]);
              //   } else {
              //     this.NOprize.push(data.data.hit_notice[i]);
              //   }
              // }
              console.log(this.NOprize, this.preizeIsPush, "中奖消息");
              this.preizeIsPush = true;
            }
            if (that.message.length != 0) {
              this.game_status = data.data.game_status;
              this.time = data.data.issue.openTime1 * 1000;
              this.issue = data.data.issue;
              this.issuePreize = data.data.issue.issue;
                 this.user_quantity = data.data.user_quantity;
              this.whether_close = data.data.issue.whether_close;
              console.log(this.whether_close, "this.whether_close");

              if (data.data.message.length != 0 && data.data.message) {
                // let newlist = that.getResultArray(
                //   "id",
                //   that.message,
                //   data.data.message
                // );
                let newlist = that.getDifferenceSet(
                  that.message,
                  data.data.message
                );
                if (newlist.length != 0) {
                  //  取相差有问题
                  if(that.message.length>300){
                    that.message.splice(1,30)  
                  }
                  that.message.push(...newlist);
                  console.log(newlist);
                  that.daodibu = false;
                }
              }
            } else {
              if (
                that.message.length == 0 &&
                data.data.message.length != 0 &&
                data.data.message
              ) {
                that.message = data.data.message;
              }
            }

            this.$toast.clear();
          })
          .catch(() => {
            this.$toast.clear();
            // this.$toast.fail(err.msg);
          });
        // 判断元素高度是不是大于容器高度
        if (
          that.$refs.swiperBox.offsetHeight <
            that.$refs.swipercontant.offsetHeight &&
          !that.daodibu
        ) {
          console.log(
            that.$refs.swiperBox.offsetHeight,
            that.$refs.swipercontant.offsetHeight,
            that.TopPX,
            // that.scrollHeight,
            that.$refs.swipercontant.scrollHeight
          );
          that.TopPX = that.TopPX + 50;

          that.transformSwiper =
            "transform:translate3d(0px, -" + that.TopPX + "px, 0px)";
        }
      }, 2000);
      document
        .querySelector("#swiperBox")
        .addEventListener("scroll", function () {
          console.log(that.$refs.swiperBox.scrollTop);
          // if (
          //   parseInt(this.scrollHeight - that.$refs.swiperBox.scrollTop - 5) <
          //   this.clientHeight
          // ) {
          //   console.log("到底部了");
          //   that.daodibu=true
          // }else{
          console.log("没到底部");
          that.daodibu = true;
          // }
        });
    });
  },
};
</script>
<style lang="less" scoped>
.betRoom {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  height: 100%;
}
.van-notice-bar {
  background: none;
  overflow: hidden;
}
.bet-head {
  overflow: hidden;
  text-align: center;
  background: #1a243f;
  padding: 0.4rem;
  margin-bottom: 0.2rem;
  font-size: 0.35rem;
  z-index: 2003;
  .left {
    flex: 1;
    position: relative;
  }
  .left ::after {
    content: "";
    display: block;
    width: 1px;
    height: 0.8rem;
    position: absolute;
    background-color: #e5e5e5;
    right: 0;
    top: 50%;
    -webkit-transform: translateY(-50%);
    transform: translateY(-50%);
  }
  .right {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    padding-left: 0.25rem;
    font-size: 0.4rem;
    position: relative;
  }
}
.cut-off {
  margin-bottom: 0.2rem;
}
.colon {
  color: #fff;
  font-size: 14px;
  line-height: 20px;
}
.block {
  width: 0.52rem;
  height: 0.52rem;
  line-height: 0.52rem;
  background-color: #30385d;
  border-radius: 5px;
  color: #fff;
  margin: 0 0.08rem;
  display: inline-block;
  padding-top: 0.02rem;
  border: 1px solid #3d5179;
}
.icon-gold {
  width: 0.3rem;
  height: 0.3rem;
  margin: 0 0.2rem;
}
.caijin {
  margin-top: 0.2rem;
}
.pre-item {
  background-color: #1a243f;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 0.3rem;
  font-size: 0.35rem;
  position: relative;
  z-index: 2003;
}
p .issue {
  white-space: nowrap;
}
.result {
  min-width: 1.44rem;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin: 0 0.4rem;
}
.pre-item:after {
  content: " ";
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  height: 1px;
  border-bottom: 1px solid #3d5179;
  color: #3d5179;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.scroll-thead {
  display: flex;
  padding: 0.2rem 0;
  text-align: center;
  background-color: #1a243f;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  span {
    flex: 1;
    font-size: 0.35rem;
  }
}
.scroller-box {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  width: 100%;
}

.msg-box {
  // min-height: calc(100% + 1px);
}
.msg-item {
}
.bet-msg {
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;

  text-align: center;
  background-color: #1a243f;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  min-height: 1rem;
  padding: 0.4rem 0;
}
.flex1 {
  flex: 1;
  word-break: break-all;
}
.footer-chat {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  padding: 0.3rem 0.2rem;
  background-color: #30385d;
  border-top: 1px solid #3d5179;
  padding-bottom: calc(0.1rem + constant(safe-area-inset-bottom));
}
.input-box {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  height: 1rem;
  margin-right: 0.2rem;
}
.input-box input {
  width: 100%;
  height: 100%;
  border: none;
  border-radius: 5px;
}
.input-box input:disabled {
  background: #1a243f;
  opacity: 1;
}
.btn-bet {
  background-color: #7d58c6;
  height: 1rem;
  line-height: 1rem;
  width: 1.6rem;
  text-align: center;
  border-radius: 5px;
}
.icon-arrow-bottom {
  transition: transform 0.3s;
}
.angleRote {
  transform: rotate(135deg);
  transition: transform 0.3s;
}
.head-menu {
  position: absolute;
  right: 0.2rem;
  top: 1.18rem;
  li:active {
    background-color: #30385d;
  }
  ul {
    background-color: #374c7c;
    padding: 0.1rem 0.16rem;
    border-radius: 0.1rem;
    li {
      padding: 0.3rem 0.2rem;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      font-size: 0.35rem;
      img {
        width: 0.5rem;
        height: 0.5rem;
        margin-right: 0.2rem;
      }
    }
  }
}

.head-menu::before {
  display: block;
  content: "";
  width: 0px;
  height: 0px;
  border-width: 0.2rem;
  border-bottom-width: 0.32rem;
  border-style: solid;
  margin-left: 66%;
  border-color: transparent transparent #374c7c transparent;
}
.boderBottom {
  position: relative;
}
.boderBottom:after {
  content: " ";
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  height: 1px;
  border-bottom: 1px solid #3d5179;
  color: #3d5179;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.g-content {
  flex: 1;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  overflow-y: hidden;
}
.swiper-container {
  margin: 0 auto;
  width: 100%;
  transition: all 0.3s;
}
.scroller-box {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  width: 100%;
  overflow: auto;
}
.swiper-slide {
  -webkit-flex-shrink: 0;
  -ms-flex-negative: 0;
  flex-shrink: 0;
  width: 100%;
  height: 100%;
  position: relative;
  -webkit-transition-property: -webkit-transform;
  transition-property: -webkit-transform;
  -o-transition-property: transform;
  transition-property: transform;
  transition-property: transform, -webkit-transform;
  transition-property: transform, -webkit-transform;
}
.slide-text {
  height: auto;
}
.swiper-slide-active {
  pointer-events: auto;
}
.issuecontant {
  top: 4.49rem;
  position: absolute;
  width: 100%;
  background: #1a243f;
}
.angleRotestow {
  transform: rotate(180deg);
  transition: transform 0.3s, -webkit-transform 0.3s;
}
.icon-arrow-bottoms {
  transform: rotate(0);
  transition: transform 0.3s;
}
.bet-panel {
  font-size: 0.24rem;
  // height: 8rem;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  height: 100%;
}
.panel-head {
  background-color: #5078b2;
  padding: 0.3rem;
}
.category {
  display: flex;
  -ms-flex-flow: wrap;
  flex-flow: wrap;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}
.category-item {
  width: 21%;
  text-align: center;
  color: #fff;
  line-height: 2;
  font-size: 0.35rem;
}
.PayActive {
  background-color: #fff;
  color: #4b77ff;
  border-radius: 5px;
}
.panel-body {
  flex: 1;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  overflow-y: auto;
}
.oddsExplain {
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  padding: 10px 10px 20px;
  overflow-x: hidden;
  .tips {
    color: #999;
  }
}
.odds {
  margin-left: auto;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  min-width: 1.6rem;
  img {
    width: 0.32rem;
    height: 0.32rem;
    margin-right: 0.1rem;
  }
  span {
    color: #ff7d20;
  }
}
.panel-body {
  flex: 1;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  overflow-y: auto;
}
.swiperKey {
  margin: 0 auto;
  position: relative;
  overflow: hidden;
  list-style: none;
  padding: 0;
  z-index: 1;
  width: 100%;
}
.select-list {
  padding: 0 0.12rem;
}
.item {
  width: 25%;
  float: left;
  text-align: center;
  margin-bottom: 0.4rem;
}
.square {
  width: 50%;
  margin: 0 auto;
  border-radius: 3px;
  width: 1.3rem;
  height: 1.3rem;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  .text {
    font-size: 0.4rem;
  }
  .oddsPay {
    color: #ff7d20;
  }
}
.select-list .item.active .square {
  border: 2px solid orange;
}
.panel-foot {
  height: 1.18rem;
  line-height: 1.18rem;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  color: #fff;
  margin-top: 0.2rem;
}
.leftCount {
  background-color: #404040;
  flex: 1;
  position: relative;
}
.rightBetButton {
  div {
    height: 100%;
    background-color: #666;
    padding: 0 0.1rem;
    min-width: 1.6rem;
    text-align: center;
  }
}
.icon-cart {
  width: 1.5rem;
  height: 1.5rem;
  position: absolute;
  left: 0.2rem;
  bottom: 0.2rem;
}
.bet-status {
  text-align: left;
  margin-left: 2rem;
  font-size: 0.35rem;
}
.bet-sub-panel {
  padding: 0.4rem 0.2rem 0.3rem;
  ul {
    display: flex;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    li {
      margin: 0 0.1rem;
      position: relative;
    }
  }
  ul li img {
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    -o-object-fit: cover;
    object-fit: cover;
  }
}
.bottom-form {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-top: 0.4rem;
  font-size: 0.4rem;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  height: 1rem;
  line-height: 1rem;
  input {
    -webkit-box-flex: 1;
    -ms-flex: 1;
    flex: 1;
    height: 100%;
    padding: 0.3rem;
    border-radius: 0.2rem;
    border: 1px solid #ccc;
  }
  .ripple.bet-btn {
    background-color: #7d58c6;
    width: 1.8rem;
  }
  .ripple {
    margin-left: 0.12rem;
    text-align: center;
    border-radius: 0.1rem;
    height: 100%;
  }
}
.betactive {
  top: -10px;
}
.msg-type_1 {
  div {
    background-color: #1a243f;
    width: 50%;
    margin: 0.2rem auto;
    padding: 0.1rem;
    text-align: center;
    border-radius: 3px;
    min-height: 0.8rem;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}
.bet {
  width: 7rem;
  background-color: #374c7c;
  border-radius: 0.2rem;
  overflow: hidden;
  top: 35%;
  position: absolute;
  left: 15%;
}
.bet-title {
  background: #374c7c;
  text-align: center;
  color: #fff;
  font-size: 0.35rem;
  line-height: 0.6rem;
}
.bet-content {
  padding: 0 0.2rem;
}
.bet-content div:nth-child(-n + 2) {
  padding: 0.16rem;
  border-bottom: 1px solid #5078b2;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}
.bet-content div:nth-child(-n + 2) {
  padding: 0.16rem;
  border-bottom: 1px solid #5078b2;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}
.bet-content div:nth-child(3) {
  padding: 0.16rem 0;
  border-bottom: 1px solid #5078b2;
}
.bet-content div:nth-child(3) span {
  display: inline-block;
  width: 50%;
  text-align: center;
}
.bet-content ul li {
  padding: 0.1rem 0;
}
.bet-footer {
  padding: 0.2rem;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}
.bet-content ul li span {
  display: inline-block;
  width: 50%;
  text-align: center;
}
.btn-confirm {
  margin-right: 0.3rem;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
  width: 100%;
}
.btn-canle {
  background-color: rgb(191, 191, 191);
  border: 1px solid rgb(191, 191, 191);
  width: 100%;
}
.follow-btn {
  background-color: #7d58c6;
  width: 1.2rem;
  padding: 0.1rem 0.1rem;
  border-radius: 0.3rem;
  flex: none;
  width: 15%;
}
.stopBet {
  height: 0.48rem;
  font-size: 0.4rem;
  color: #ff4f5b;
}
.Xwarp {
  background: url(../assets/image/xi.png);
  width: 100%;
  height: 2.92rem;
  position: absolute;
  top: 6rem;
  z-index: 999;
  transform: translate3d(0, 0px, 0px);
  transition-duration: 2000ms;
  animation: righttoleftpiaopping 5s linear both;
  background-size: 100% 100%;
  div {
    margin-top: 1.6rem;
    font-size: 0.35rem;
    margin-left: 2.35rem;
    width: 7.5rem;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
  }
}
@keyframes righttoleftpiaopping {
  /*设置内容由显示变为隐藏*/
  0% {
    transform: translate3d(-300px, 0px, 0px);
    opacity: 1;
  }
  50% {
    transform: translate3d(0, 0px, 0px);
    opacity: 1;
  }
  50% {
    transform: translate3d(200px, 0px, 0px);
    opacity: 1;
  }
  100% {
    transform: translate3d(500px, 0px, 0px);
    opacity: 0;
  }
}
</style>