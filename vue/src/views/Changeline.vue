<template>
  <div class="changeline">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('genggaixianlu')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content bg_init">
        <img src="@/assets/image/xianlu.png" class="select-bg" alt="" />
        <div class="line-box">
          <div class="line-item">
            <div class="line-num">
              <span>{{$t('xianlu')}}1</span>
              <span v-if="activeList == 0">({{$t('dangaqianxianlu')}})</span>
            </div>
            <div class="button" @click="activechange(0)">{{$t('kaishitouzi')}}</div>
          </div>
          <div class="line-item">
            <div class="line-num">
              <span>{{$t('xianlu')}}2</span>
              <span v-if="activeList == 1">({{$t('dangaqianxianlu')}})</span>
              <span class="c_green">({{$t('zuiyou')}})</span>
            </div>
            <div class="button" @click="activechange(1)">{{$t('kaishitouzi')}}</div>
          </div>
        </div>
        <p class="prompt">{{$t('kaiTps')}}</p>
      </div>
    </div>
    <alert-pop
      :alertType="3"
      :Tips="TipsTxt"
      v-if="PopAlert"
      @showAletfalse="showAletfalse"
    ></alert-pop>
  </div>
</template>
<script>
import AlertPop from "@/components/alert/AlertPop.vue";
import storeAction from "@/store/typed-actions";
export default {
  name: "Changeline",
  components: { AlertPop },
  data() {
    return {
      TipsTxt: "",
      PopAlert: false,
    };
  },
  computed: {
    activeList() {
      console.log(this.$store.state.activeList);
      return this.$store.state.activeList;
    },
  },
  methods: {
    activechange(active) {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 1000,
      });
      storeAction.setCurrentUseractiveList(active);
      storeAction.setsessionStorage("activeList", active);
      this.TipsTxt = active == 0 ? "已切换到-线路1" : "已切换到-线路2";
      this.PopAlert = true;
      console.log(active);
    },
    showAletfalse() {
      this.PopAlert = false;
      this.$router.go(-1);
    },
    changego() {
      this.$router.go(-1);
    },
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
  flex: 1;
  overflow: scroll;
  text-align: center;
  padding: 0 0.2rem;
}
.bg_init {
  background-color: inherit;
}
.select-bg {
  margin-top: 0.6rem;
  margin-bottom: 0.2rem;
}
.line-box {
}
.line-item {
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-top: 0.3rem;
}
.line-num {
  background-color: #1a243f;
  border: 2px solid #3d5179;
  border-radius: 5px;
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  padding: 0.33rem 0;
  font-size: 0.35rem;
}
.button {
  color: #fff;
  margin-left: 0.2rem;
  padding: 0.35rem 0.4rem;
  border-radius: 5px;
  background: #7d58c6;
  font-size: 0.35rem;
}
.c_green {
  color: green;
}
.prompt {
  position: absolute;
  left: 0.2rem;
  right: 0.2rem;
  bottom: 0.5rem;
  font-size: 0.4rem;
}
</style>