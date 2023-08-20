<template>
  <div class="lottery">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left"></div>
        <div class="middle fs-18">{{ $t('jiaoyidating') }}</div>
        <div class="right">
          <div class="bg-icon bg-icon-setting"></div>
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="g-content mescroll">
      <div>
        <div class="tab-box-wrap">
          <ul class="tab-box">
            <li
              v-for="(item, index) in gameList"
              :key="index"
              class="tab"
              :class="curr == index ? 'active' : ''"
              @click="curr = index + 1"
            >
              {{ item.name }}
            </li>

            <div class="tab_lines" :style="activeLines"></div>
          </ul>
          <div>
            <div class="swiper-container">
              <div class="swiper-wrapper">
                <div
                  class="swiper-slide"
                  v-for="(item, index) in gameList"
                  :key="index"
                >
                  <ul class="level-box">
                    <!-- 根据房间类型换图 -->
                    <template
                      v-for="(liitem, Liindex) in item.planList"
                      :key="Liindex"
                    >
                      <li
                        class="level"
                        @click="changeroom(liitem.id, liitem.game_id,item.name,liitem.title,liitem.join_limit)"
                        v-if="liitem.type == 0"
                      >
                        <div class="title">
                          <div class="level-name">{{ $t('jichulicai') }}</div>
                          <div class="people-box">
                            <img
                              src="@/assets/image/iconline.png"
                              class="icon"
                              alt=""
                            />
                            <span>{{ liitem.robot_num }}</span>
                          </div>
                        </div>
                        <div class="text-box">
                          <div class="text">
                            <span>{{ $t('zuiditouzi') }}</span>
                            <span class="price">{{ liitem.min_limit }}</span>
                          </div>
                          <div class="text">
                            <span>{{ $t('zuidijinru') }}</span>
                            <span class="price">{{ liitem.join_limit }}</span>
                          </div>
                        </div>
                      </li>
                      <li
                        class="level1"
                        @click="changeroom(liitem.id, liitem.game_id,item.name,liitem.title,liitem.join_limit)"
                        v-if="liitem.type == 1"
                      >
                        <div class="title">
                          <div class="level-name">{{ $t('zishenlicai') }}</div>
                          <div class="people-box">
                            <img
                              src="@/assets/image/iconline.png"
                              class="icon"
                              alt=""
                            />
                            <span>{{ liitem.robot_num }}</span>
                          </div>
                        </div>
                        <div class="text-box">
                          <div class="text">
                            <span>{{ $t('zuiditouzi') }}</span>
                            <span class="price">{{ liitem.min_limit }}</span>
                          </div>
                          <div class="text">
                            <span>{{ $t('zuidijinru') }}</span>
                            <span class="price">{{ liitem.join_limit }}</span>
                          </div>
                        </div>
                      </li>
                      <li
                        class="level2"
                        @click="changeroom(liitem.id, liitem.game_id,item.name,liitem.title,liitem.join_limit)"
                        v-if="liitem.type == 2"
                      >
                        <div class="title">
                          <div class="level-name">{{ $t('dashilicai') }}</div>
                          <div class="people-box">
                            <img
                              src="@/assets/image/iconline.png"
                              class="icon"
                              alt=""
                            />
                            <span>{{ liitem.robot_num }}</span>
                          </div>
                        </div>
                        <div class="text-box">
                          <div class="text">
                            <span>{{ $t('zuiditouzi') }}</span>
                            <span class="price">{{ liitem.min_limit }}</span>
                          </div>
                          <div class="text">
                            <span>{{ $t('zuidijinru') }}</span>
                            <span class="price">{{ liitem.join_limit }}</span>
                          </div>
                        </div>
                      </li>
                      <li
                        class="level3"
                        @click="changeroom(liitem.id, liitem.game_id,item.name,liitem.title,liitem.join_limit)"
                        v-if="liitem.type == 3"
                      >
                        <div class="title">
                          <div class="level-name">{{ $t('zhuanjialicai') }}</div>
                          <div class="people-box">
                            <img
                              src="@/assets/image/iconline.png"
                              class="icon"
                              alt=""
                            />
                            <span>{{ liitem.robot_num }}</span>
                          </div>
                        </div>
                        <div class="text-box">
                          <div class="text">
                            <span>{{ $t('zuiditouzi') }}</span>
                            <span class="price">{{ liitem.min_limit }}</span>
                          </div>
                          <div class="text">
                            <span>{{ $t('zuidijinru') }}</span>
                            <span class="price">{{ liitem.join_limit }}</span>
                          </div>
                        </div>
                      </li>
                      <!-- <li
                        class="level"
                        @click="changeroom(liitem.id, liitem.game_id)"
                        v-else
                      >
                        <div class="title">
                          <div class="level-name">{{ liitem.title }}</div>
                          <div class="people-box">
                            <img
                              src="@/assets/image/iconline.png"
                              class="icon"
                              alt=""
                            />
                            <span>{{ liitem.robot_num }}</span>
                          </div>
                        </div>
                        <div class="text-box">
                          <div class="text">
                            <span>{{ $t('zuiditouzi') }}</span>
                            <span class="price">{{ liitem.min_limit }}</span>
                          </div>
                          <div class="text">
                            <span>最低进入</span>
                            <span class="price">{{ liitem.join_limit }}</span>
                          </div>
                        </div>
                      </li> -->
                    </template>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <tab-bar></tab-bar>
  </div>
</template>
<script>
import TabBar from "@/components/tabbar/TabBar.vue";
import Swiper from "swiper";
import "swiper/dist/css/swiper.css";
import homeApi from "@/api/home";
export default {
  name: "Lottery",
  components: {
    TabBar,
  },
  data() {
    return {
      curr: 1,
      activeLines: "left: 0%; width: 25%",
      SweipeIndex: 0,
      gameList: "",
    };
  },
    computed: {
    money() {
      console.log(this.$store.state.money);
      return this.$store.state.money;
    },
    },
  watch: {
    curr(newVal) {
      if (newVal == 1) {
        this.activeLines = "left: 0%; width: 25%";
      } else if (newVal == 2) {
        this.activeLines = "left: 25%; width: 25%";
      } else if (newVal == 3) {
        this.activeLines = "left: 50%; width: 25%";
      } else if (newVal == 4) {
        this.activeLines = "left: 75%; width: 25%";
      }
    },
  },
  methods: {
    handleClickSlide(index) {
      console.log(index);
    },
    tabIndex(target, nodeList) {
      for (let i = 0; i < nodeList.length; i++) {
        if (target === nodeList[i]) {
          return i;
        }
      }
    },
    changeroom(id, game_id,name,title,joinmoeny) {
      // 判断进入金额和自己的金额
      console.log(this.money*1,joinmoeny*1)
      if(this.money*1<joinmoeny*1){
        // alert(1)
        this.$toast(this.$t('Tips.zcbz'))
        return
      }
      this.$router.push({
        path: "/betRoom",
        query: {
          id: id,
          game_id: game_id,
          name:name,
          title:title
        },
      });
    },
    info() {},
  },
  mounted() {
    this.$toast.loading({
      message: this.$t('jiazaizhong'),
      forbidClick: true,
      duration: 0,
    });
    homeApi
      .Lotteryinfo("", "")
      .then((data) => {
        console.log(data.data);
        this.gameList = data.data.gameList;
        document
          .querySelector(".tab-box")
          .addEventListener("click", function (e) {
            let target = e.target; //事件发生的元素
            let nodeList = e.target.parentNode.children; //同级元素集合
            let targetIndex = that.tabIndex(target, nodeList); //调用tabIndex方法，返回值便是元素下标
            mySwiper.slideTo(targetIndex, 300, false);
          });
        let that = this;
        let mySwiper = new Swiper(".swiper-container", {
          // 如果需要分页器
          pagination: ".swiper-pagination",
          // 如果需要前进后退按钮
          nextButton: ".swiper-button-next",
          observer: true,
          observerParents: false,
          prevButton: ".swiper-button-prev",
          onSlideChangeEnd: function (swiper) {
            this.SweipeIndex = swiper.activeIndex;
            console.log(this.SweipeIndex, swiper.activeIndex);
            if (swiper.activeIndex == 0) {
              that.activeLines = "left: 0%; width: 25%";
            } else if (swiper.activeIndex == 1) {
              that.activeLines = "left: 25%; width: 25%";
            } else if (swiper.activeIndex == 2) {
              that.activeLines = "left: 50%; width: 25%";
            } else if (swiper.activeIndex == 3) {
              that.activeLines = "left: 75%; width: 25%";
            }
          },
        });
        this.$toast.clear();
      })
      .catch((err) => {
        this.$toast.clear();
        this.$toast.fail(err.msg);
      });
  },
  created() {},
};
</script>
<style lang="less" scoped>
.lottery {
  height: 100%;
  display: flex;
  padding-bottom: 1rem;
  padding-bottom: calc(1rem + constant(safe-area-inset-bottom));
  padding-bottom: calc(1rem + env(safe-area-inset-bottom));
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
}
.mescroll {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  overflow-y: auto;
}
.tab-box-wrap {
  padding: 0 0.24rem;
}
.tab-box {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin: 0.36rem 0 0.54rem;
  border: 1px solid #4bb3e8;
  border-radius: 4px;
  position: relative;
}
.tab {
  flex: 1;
  padding: 0.4em 0;
  font-size: 0.35rem;
  font-weight: 700;
  text-align: center;
}
.active {
}
.tab_lines {
  position: absolute;
  height: 100%;
  background: #4bb3e8;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  z-index: -1;
}
.swiper-container {
  // height: 300px;
  width: 100%;
  .swiper-wrapper {
    .swiper-slide {
    }
  }
}
.level-box {
}
.level {
  height: 3rem;
  margin-bottom: 0.2rem;
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-image: url(../assets/image/bg.png);
  text-align: left;
}
.level1 {
  height: 3rem;
  margin-bottom: 0.2rem;
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-image: url(../assets/image/bg2.png);
  text-align: left;
}
.level2 {
  height: 3rem;
  margin-bottom: 0.2rem;
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-image: url(../assets/image/bg3.png);
  text-align: left;
}
.level3 {
  height: 3rem;
  margin-bottom: 0.2rem;
  background-repeat: no-repeat;
  background-size: 100% 100%;
  background-image: url(../assets/image/bg4.png);
  text-align: left;
}
.title {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 0.5rem 0.56rem 0;
  font-size: 0.45rem;
}
.level-name {
  width: 2.5rem;
}
.people-box {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;

  margin-left: 0.2rem;
  font-size: 0.45rem;
  .icon {
    margin-right: 0.06rem;
    width: 0.5rem;
    height: 0.5rem;
  }
}
.text-box {
  padding: 0.3rem 0.56rem 0;
  font-size: 0.3rem;
  text-align: left;
}
.text {
  min-width: 2.1rem;
}
.price {
  padding-left: 0.1rem;
}
.text:last-child {
  margin-top: 0.2rem;
}
</style>