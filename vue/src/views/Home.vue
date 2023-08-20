<template>
  <div class="home">
    <div class="Navbar">
      <div class="g-header">
        <div class="navleft">
          <div class="app-line" @click="showList = !showList">
            <span :style="showList ? 'color: #ff7d20;' : ''">{{
              activeList == 0 ? $t("xianlu") + "1" : $t("xianlu") + "2"
            }}</span>
            <van-icon
              name="arrow-down"
              color="#fff"
              size="16px"
              style="margin: 0.2rem 0 0 0.1rem"
              :class="showList ? 'angleRotestow' : 'icon-arrow-bottoms'"
            />
          </div>
        </div>
        <div class="middle fs-18"></div>
        <div class="navright" @click="changeLanguage">
          <span>{{ texType == 0 ? $t("gensuixitong") : $t("jiantizhongwen") }} </span>
        </div>
      </div>
      <div class="block"></div>
    </div>
    <div class="g-content flex-column">
      <div class="mescroll">
        <!-- 轮播 -->
        <swi-pe></swi-pe>
        <div class="broadcast">
          <div class="broadcast-icon">
            <div class="sprite-icon_gonggao"></div>
          </div>
          <div class="c_a0a0a0">{{ $t("dangqianbanben") }}：21.04.02</div>
        </div>
        <!-- 横向滑动 -->
        <lt-list></lt-list>
        <!-- 资产 -->
        <div class="cont-head">
          <div class="cont-left">
            <div>
              <div class="en-text">{{ $t("wodezichan") }}</div>
              <div class="left-text">
                {{ money ? money : $t("dengluhouchakan") }}
              </div>
            </div>
            <img src="@/assets/image/zichan.png" class="icon" alt="" />
          </div>
          <div class="cont-right" @click="changeNews">
            <div class="huobin">
              <img src="@/assets/image/touzi.png" class="rightcion" alt="" />
              <div>{{ $t("touzikuaixun") }}</div>
            </div>
          </div>
        </div>
        <!---横线块无颜色 -->
        <div class="menu-container"></div>
        <!-- 跑马灯 -->
        <div class="prize-news-wrap">
          <div class="sub-title">
            <span>{{ $t("jiaoyiyonghu") }}</span>
            <span>{{ $t("jiaoyipinglei") }}</span>
            <span>{{ $t("danlunhuoli") }}</span>
            <span>{{ $t("jiaoyiriqi") }}</span>
          </div>
          <div class="prize-news">
            <!-- <div  class="vux-marquee prize-marquee">
                   <ul class="vux-marquee-box">
                <li
                  class="prize-marquee-item"
                  v-for="(item, index) in infoList"
                  :key="index"
                >
                  <span class="name">{{ item.nickname }}</span>
                  <span class="type">{{ item.game_name }}</span>
                  <span class="money">{{ item.profit }}</span>
                  <span class="date">12/28</span>
                </li>
              </ul>
            </div> -->
            <vue-seamless-scroll
              class="vux-marquee prize-marquee"
              :class-option="classOption"
              :data="infoList"
            >
              <ul class="vux-marquee-box">
                <li
                  class="prize-marquee-item"
                  v-for="(item, index) in infoList"
                  :key="index"
                >
                  <span class="name">{{ item.nickname }}</span>
                  <span class="type">{{ item.game_name }}</span>
                  <span class="money">{{ item.profit }}</span>
                  <span class="date">{{time}}</span>
                </li>
              </ul>
            </vue-seamless-scroll>
          </div>
        </div>
      </div>
    </div>
    <tab-bar></tab-bar>
    <!-- 线路遮罩层 -->
    <van-overlay v-model:show="showList" @click="showList = false">
      <div class="content">
        <ul class="line-content">
          <li class="line-item boderBottom" @click="activechange(0)">
            <p>{{ $t("xianlu") }}1</p>
            <img
              src="@/assets/image/gou.png"
              class="icon"
              alt=""
              v-if="activeList == 0"
            />
          </li>
          <li class="line-item boderBottom" @click="activechange(1)">
            <p>
              {{ $t("xianlu") }}2
              <span style="color: green">({{ $t("zuiyou") }})</span>
            </p>
            <img
              src="@/assets/image/gou.png"
              class="icon"
              alt=""
              v-if="activeList == 1"
            />
          </li>
        </ul>
      </div>
    </van-overlay>
  </div>
</template>

<script>
// @ is an alias to /src
import TabBar from "@/components/tabbar/TabBar.vue";
import LtList from "@/components/ltlist/LtList.vue";
import SwiPe from "@/components/swipe/SwiPe.vue";
import homeApi from "@/api/home";
import vueSeamlessScroll from "vue-seamless-scroll/src";
import storeAction from "@/store/typed-actions";
export default {
  name: "Home",
  components: {
    TabBar,
    SwiPe,
    LtList,
    vueSeamlessScroll,
  },
  computed: {
    classOption() {
      return {
        step: 0.2, // 数值越大速度滚动越快
        // limitMoveNum: 10, // 开始无缝滚动的数据量 this.dataList.length
        hoverStop: true, // 是否开启鼠标悬停stop
        direction: 1, // 0向下 1向上 2向左 3向右
        // openWatch: true, // 开启数据实时监控刷新dom
        singleHeight: 0, // 单步运动停止的高度(默认值0是无缝不停止的滚动) direction => 0/1
        singleWidth: 0, // 单步运动停止的宽度(默认值0是无缝不停止的滚动) direction => 2/3
        waitTime: 1000, // 单步运动停止的时间(默认值1000ms)
      };
    },
    money() {
      console.log(this.$store.state.money);
      return this.$store.state.money;
    },
    activeList() {
      console.log(this.$store.state.activeList);
      return this.$store.state.activeList;
    },
  },
  data() {
    return {
      showList: false,
      infoList: [],
      isShow: true,
      texType: localStorage.getItem("localetype"),
      time:''
    };
  },
  methods: {
    changeLanguage() {
      this.$router.push({
        path: "/language",
      });
    },
    activechange(active) {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 1000,
      });
      storeAction.setCurrentUseractiveList(active);
      storeAction.setsessionStorage("activeList", active);
      console.log(active);
    },
    info() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .heart("", "")
        .then((data) => {
          console.log(data.data);
          this.infoList = data.data.hit_notice;
          this.$toast.clear();
        })
        .catch((err) => {
          // alert(err)
          // this.$toast.clear();
          this.$toast.fail(err);
        });
    },
    changeNews() {
      this.$router.push({
        path: "/moreNews",
      });
    },
  },
  mounted() {

  },
  created() {
        let  myDate = new Date();
       let time=  myDate.toLocaleDateString();
       this.time=time.slice(5)
    this.info();
  },
};
</script>
<style lang="less" scoped>
.home {
  height: 100%;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.g-header {
  position: absolute;
  width: 100%;
  height: 1.18rem;
  text-align: center;
  // background: red;
  background: rgb(14, 21, 48);
  z-index: 9999;
  font-size: 0.35rem;
  color: #fff;
}
.block {
  height: 1.18rem;
}
.navleft,
.navright {
  display: flex;
  z-index: 11;
  position: absolute;
  height: 100%;
  align-items: center;
}
.middle {
  z-index: 10;
  max-width: 80%;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
.fs-18 {
  font-size: 0.36rem;
}
.navright {
  right: 0.2rem;
}
.app-line {
  padding: 0 0.2rem;
}
.broadcast {
  min-height: 30px;
  padding: 0.2rem 0.4rem;
  display: flex;
  align-items: center;
}
.broadcast-icon {
  margin-right: 0.2rem;
}
.sprite-icon_gonggao {
  background: url("../assets/image/duo.png");
  width: 0.49rem;
  height: 0.49rem;
  background-size: 2.53913043rem 2.3026087rem;
  background-position: -1rem 0.9rem;
}
.c_a0a0a0 {
  color: #a0a0a0;
}
.cont-head {
  display: flex;
  justify-content: space-between;
  align-items: stretch;
  margin-top: 0.2rem;
  padding: 0 0.2rem;
}
.cont-left {
  width: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-right: 0.3rem;
  padding: 0.6rem 0.2rem;
  color: #fff;
  background: #1a243f;
  border-radius: 4px;
}
.en-text {
  text-align: left;
  font-size: 0.37rem;
}
.left-text {
  padding-top: 0.22rem;
  color: #999;
  font-size: 0.35rem;
}
.icon {
  margin-left: 0.4rem;
  width: 1.29rem;
  height: 1.32rem;
}
.cont-right {
  width: 50%;
}
.huobin {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 0.28rem;
  background: #1a243f;
  border-radius: 4px;
  color: #fff;
  font-size: 0.37rem;
}
.rightcion {
  width: 0.77rem;
  height: 0.65rem;
  margin-right: 0.2rem;
}
.menu-container {
  display: flex;
  justify-content: space-between;
  margin-top: 0.2rem;
  padding: 0 0.2rem;
}
.prize-news-wrap {
  padding-bottom: 50px;
  margin-top: 0.2rem;
}
.sub-title {
  display: flex;
  text-align: center;
  font-size: 14px;
  color: #afafaf;
  background: #0f1634;
  span {
    flex: 1;
    padding: 0.1rem 0 0.2rem;
  }
}
.prize-news {
  height: 150px;
}
.prize-marquee {
  height: 150px !important;
  font-size: 14px;
}
.vux-marquee {
  width: 100%;
  overflow: hidden;
  height: 300px;
}
.vux-marquee-box {
  padding: 0;
  margin: 0;
  width: 100%;
  height: auto;
}
.prize-marquee-item {
  height: 30px;
  line-height: 30px;
  display: flex;
  align-items: center;
  -ms-flex-pack: distribute;
  justify-content: space-around;
  span {
    display: inline-block;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
  }
  .name,
  .money {
    color: #b99318;
  }
  .type,
  .date {
    color: #fff;
  }
}
.content {
  position: absolute;
  width: 100%;
  background: #1a243f;
  top: 1.18rem;
  z-index: 12;
}
.line-content {
  background-color: #040925;
}
.line-content .line-item {
  height: 1rem;
  font-size: 0.35rem;
  padding: 0 0.4rem 0 0.2rem;
  display: flex;
  align-items: center;
}
.line-item :after {
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
.line-item .icon {
  margin-left: auto;
  width: 0.6rem;
  height: 0.6rem;
}
.angleRotestow {
  transform: rotate(180deg);
  transition: transform 0.3s, -webkit-transform 0.3s;
}
.icon-arrow-bottoms {
  transform: rotate(0);
  transition: transform 0.3s;
}
</style>