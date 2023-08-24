<template>
  <div class="betRecord">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('lishizhangdan')}}</div>
          <div class="right">
            <div class="slide-btn" @click="show = !show">
              <div class="bg-icon bg-icon-filtrate"></div>
            </div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="mescroll g-content">
        <van-pull-refresh v-model="refreshing" @refresh="onRefresh">
          <van-list
            v-model="loading"
            :finished="finished"
            finished-text="--END--"
            @load="onLoad"
          >
            <!-- 空数据的时候显示 -->
            <ul class="capital-list" v-if="list.length == []">
              <div class="mescroll-empty">
                <p class="empty-tip">{{$t('wuTps')}}~</p>
                <p class="empty-btn"  @click="BtnOk">{{$t('jiazaicx')}}</p>
              </div>
            </ul>
            <!-- 有数据显示他 -->
            <ul class="capital-list" v-else>
              <li class="capital-list-item" @click="changeDetail(item)"  v-for="(item, index) in list"
                  :key="index">
                <div class="item-top">
                  <div class="time">{{$t('shijian')}}：{{timeStr(item.createtime)}}</div>
                </div>
                <div class="item-middle">
                  <span>{{item.name}}</span>
                  <span class="c_a0a0a0" style="flex:1">{{$t(item.plan_name)}}</span>
                   <span class="c_fe5050" v-if="item.status==0">{{$t('yishouli')}}</span>
                     <span class="c_fe5050" v-if="item.status==1">{{item.whether_hit==0?$t('weiyingli'):item.profit}}</span>
                </div>
                
                <div class="item-bottom">
                  <span class="c_a0a0a0">{{$t('di')}}{{item.issue}}{{$t('lun')}}</span>
                  <span class="c_fe5050">{{item.quantity}}</span>
                  <span></span>
                </div>
              </li>
            </ul>
          </van-list>
        </van-pull-refresh>
      </div>
    <!-- 弹出层 -->
    <van-popup
      v-model:show="show"
      position="right"
      :style="{ width: '5.88rem', height: '100%' }"
    >
      <div class="drawer-content is-open">
        <div class="my-drawer-content">
          <div>
            <p class="title">{{$t('shijian')}}</p>
            <ul class="opt-time">
              <li @click="(showTime = !showTime), (showTimeType = 0)">
                   {{ begin_date ? begin_date :$t('upshijian')}}
              </li>
              <li @click="(showTime = !showTime), (showTimeType = 1)">
                  {{ end_date ? end_date : $t('downshijian') }}  
              </li>
            </ul>
          </div>
          <div v-if="false">
            <p class="title">{{$t('zhuangtai')}}</p>
            <ul class="opt-time">
              <template v-for="(item, index) in statueList" :key="index">
                <li
                  style="color: #fff"
                  :class="index == statueActive ? 'active' : ''"
                  @click="statueActive = index"
                >
                  {{ item }}
                </li>
              </template>
            </ul>
          </div>
          <div v-if="false">
            <p class="title">分类</p>
            <ul class="opt-time">
              <template v-for="(item, index) in statueList" :key="index">
                <li
                  style="color: #fff"
                  :class="index == statueActive ? 'active' : ''"
                  @click="statueActive = index"
                >
                  {{ item }}
                </li>
              </template>
            </ul>
          </div>
        </div>
      </div>
      <div class="my-drawer-btns">
        <van-button class="bg-fff" @click="Reset">{{$t('chongzhi')}}</van-button>
        <van-button class="bg-red" @click="BtnOk">{{$t('queren')}}</van-button>
      </div>
    </van-popup>
    <!-- 时间选择 -->
    <van-popup
      v-model:show="showTime"
      @click="showTime = false"
      :style="{ width: '100%' }"
      position="bottom"
    >
      <van-datetime-picker
        v-model="currentDate"
        type="date"
        :title="showGourp"
        :min-date="minDate"
        :max-date="maxDate"
        @confirm="changeTime"
      />
    </van-popup>
  </div>
</template>
<script>
import userApi from "@/api/user";
import time from "@/utils/time";
export default {
  name: "BetRecord",
  components: {},
  data() {
    return {
      show: false,
      list: [],
      loading: false,
      finished: false,
      refreshing: false,
      statueList: ["全部", "未盈利", "已盈利", "已受理", "未受理", "已取消"],
      statueActive: 0,
      minDate: new Date(2018, 0, 1),
      maxDate: new Date(),
      currentDate: new Date(),
      begin_date: "",
      end_date: "",
      showTime: false,
    };
  },
  methods: {
       timeStr(items) {
      return time.formatDatethree(items * 1000);
    },
    Reset() {
      this.statueActive = 0;
      this.begin_date = "";
      this.end_date = "";
    },
      BtnOk() {
      this.show = false;
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        duration: 0,
        forbidClick: true,
      });
        userApi
          .orderList(
            this.begin_date ? this.begin_date : this.formatDate(new Date()),
            this.end_date ? this.end_date : this.formatDate(new Date())
          )
          .then((data) => {
            if (data.data.length) {
              this.list = data.data;
            } else {
               this.list =[]
              this.$toast(this.$t('Tips.zanwu'));
            }
          });
      this.loading = false;
      this.finished = true;
      this.$toast.clear();
    },
      formatDate(date) {
      let yy = date.getFullYear();
      let mm = date.getMonth() + 1;
      mm = mm.toString();
      let dd = date.getDate().toString();
      return yy + "-" + mm.padStart(2, 0) + "-" + dd.padStart(2, 0);
    },
    changeTime(value) {
      console.log(this.formatDate(value));
      if (this.showTimeType == 0) {
        this.begin_date = this.formatDate(value);
      } else {
        this.end_date = this.formatDate(value);
      }
    },
    onRefresh() {
      this.refreshing = false;
      this.finished = false;
      this.loading = true;
      this.BtnOk();
    },
    changego() {
      this.$router.go(-1);
    },
    onLoad() {
       if (this.refreshing) {
        this.list = [];
        this.refreshing = false;
        this.BtnOk();
      }
    },
    changeDetail(item) {
      // 传详情iD
      this.$router.push({
        path: "/betDetail",
        query: {item:JSON.stringify(item) },
      });
    },
  },
  mounted() {
     this.onRefresh();
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
.slide-btn {
  padding: 0.24rem;
}
.bg-icon-filtrate {
  width: 0.4rem;
  height: 0.4rem;
  background-image: url(../assets/image/shoudian.png);
}
.bg-icon {
  width: 0.5rem;
  height: 0.5rem;
  margin-left: 0.1rem;
  background-repeat: no-repeat;
  background-position: center center;
  background-size: cover;
}
.right {
  right: 0.3rem !important;
}
.mescroll {
  width: 100%;
  height: 100%;
  overflow-y: auto;
  flex: 1;
}
.capital-list {
  padding: 0.2rem;
}
.mescroll-empty {
  width: 100%;
  padding-top: 20px;
  text-align: center;
}
.empty-tip {
  margin-top: 6px;
  font-size: 14px;
  color: gray;
}
.empty-btn {
  max-width: 50%;
  margin: 20px auto;
  padding: 10px;
  border: 1px solid #65aadd;
  border-radius: 6px;
  background-color: white;
  color: #65aadd;
  background-color: #7d58c6 !important;
  border: 1px solid #3d5179 !important;
  color: #fff !important;
}
.drawer-content {
  z-index: 1;
  position: absolute;
  top: 0.88rem;
  bottom: 0;
  right: 0;
  width: 100%;
  background-color: #374c7c;
  -webkit-transform: translate3d(100%, 0, 0);
  transform: translate3d(100%, 0, 0);
  transition: all 0.45s cubic-bezier(0.23, 1, 0.32, 1);
  visibility: hidden;
  box-shadow: 0 8px 10px -5px rgb(0 0 0 / 20%), 0 16px 24px 2px rgb(0 0 0 / 14%),
    0 6px 30px 5px rgb(0 0 0 / 12%);
}
.drawer-content.is-open {
  visibility: visible;
  -webkit-transform: translate3d(0, 0, 0);
  transform: translate3d(0, 0, 0);
}
.my-drawer-content {
  padding: 0.5rem;
  padding-bottom: 0.72rem;
  max-height: 100%;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  text-align: left;
  ul {
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
    li {
      width: 47%;
      text-align: center;
      margin-bottom: 0.3rem;
      background-color: #5078b2;
      border-radius: 6px;
      padding: 6px 0;
      font-size: 0.3rem;
      border: 1px solid #5078b2;
    }
  }
}
.title {
  margin-bottom: 0.3rem;
  font-size: 0.4rem;
}
.opt-time {
  color: #bfbfbf;
}
.active {
  border: 1px solid #fe5050 !important;
  color: #fe5050 !important;
}
.my-drawer-btns {
  position: absolute;
  width: 100%;
  bottom: 0;
  z-index: 1;
}
.bg-red {
  width: 50%;
  color: #fff;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
.bg-fff {
  color: #fff;
  width: 50%;
  background: rgb(191, 191, 191);
  border-color: rgb(191, 191, 191);
}
.capital-list-item:first-child {
  margin-top: 0;
}
.capital-list .capital-list-item {
  background-color: #1a243f;
  margin-top: 0.4rem;
  border-radius: 3px;
  padding: 0.3rem 0.4rem;
}
.capital-list-item > div {
  display: flex;
  text-align: left;
  .time {
    height: 0.5rem;
    line-height: 0.5rem;
    border-top-right-radius: 0.2rem;
    border-bottom-right-radius: 0.2rem;
    background-color: #30385d;
    color: #fff;
    font-size: 0.35rem;
    margin-left: -0.2rem;
    padding: 0 0.16rem;
    display: inline-block;
  }
}
.item-middle {
  padding: 0.25rem 0;
  font-size: 0.35rem;
}
.c_fe5050 {
  color: #fe5050;
}
.capital-list .capital-list-item > div span:first-child {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
}
.capital-list-item > div {
  display: flex;
}
.item-bottom {
  font-size: 0.35rem;
}
.c_fe5050 {
  color: #fe5050;
}
</style>