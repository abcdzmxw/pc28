<template>
  <div class="g-footer">
    <div class="footer-item" @click="changehome">
      <div class="icon sprite-icon_goucai_unselected">
        <img src="@/assets/image/home.png" v-if="active != 0" alt="" />
        <img src="@/assets/image/homes.png" v-if="active == 0" alt="" />
      </div>
      <div class="text">{{ $t('shouye') }}</div>
    </div>
    <div class="footer-item" @click="changeLottery">
      <div class="icon sprite-icon_goucai_unselected">
        <img src="@/assets/image/jiaoyi.png" v-if="active != 1" alt="" />
        <img src="@/assets/image/jiaoyis.png" v-if="active == 1" alt="" />
      </div>
      <div class="text">{{ $t('jiaoyidating') }}</div>
    </div>
    <div class="footer-item" @click="changeyucun">
      <div class="center">
        <div class="icon sprite-icon_goucai_unselected iconcenter">
          <img src="@/assets/image/yucunkuan.png" v-if="active != 2" alt="" />
          <img src="@/assets/image/yucunkuans.png" v-if="active == 2" alt="" />
        </div>
        <div class="text">{{ $t('yucun') }}</div>
      </div>
    </div>
    <div class="footer-item" @click="changefind">
      <div class="icon sprite-icon_goucai_unselected">
        <img src="@/assets/image/zoushi.png" v-if="active != 3" alt="" />
        <img src="@/assets/image/zoushis.png" v-if="active == 3" alt="" />
      </div>
      <div class="text">{{ $t('zoushi') }}</div>
    </div>
    <div class="footer-item" @click="changePersonal">
      <div class="icon sprite-icon_goucai_unselected">
        <img src="@/assets/image/wode.png" v-if="active != 4" alt="" />
        <img src="@/assets/image/wodes.png" v-if="active == 4" alt="" />
      </div>
      <div class="text">{{ $t('wode') }}</div>
    </div>
  </div>
  <div id="kefu" @click="See('http://106.12.199.100:928/addons/kefu')"></div>
</template>

<script>
// import state from "@/store/state";
export default {
  data() {
    return {
      active: 0,
    };
  },
  computed: {
  },
  watch: {
    // active(newVal) {
    //   this.active = newVal;
    // },
  },
  methods: {
    See(e) {
      window.location.href = e
    },
    changehome() {
      this.$router.push({
        path: "/",
      });
    },
    changePersonal() {
      this.$router.push({
        path: "/personal",
      });
    },
    changefind() {
      this.$router.push({
        path: "/find",
      });
    },
    changeyucun() {
      this.$router.push({
        path: "/recharge",
      });
    },
    changeLottery() {
      this.$router.push({
        path: "/lottery",
      });
    },
  },
  mounted() {
    switch (this.$route.path) {
      case "/personal":
        this.active = 4;
        break;
      case "/find":
        this.active = 3;
        break;
      case "/room":
        this.active = 2;
        break;
      // 交易页面
      case "/lottery":
        this.active = 1;
        break;
      default:
        this.active = 0;
        break;
    }
    console.log(this.$route.path, this.active);
  },
};
</script>
<style lang="less" scoped>
.g-footer {
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 1;
  background-color: #040925;
  border-top: 1px solid #3d5179;
  -webkit-transition: 0.3s linear;
  transition: 0.3s linear;
  height: 1.32rem;
  height: calc(1.32rem + constant(safe-area-inset-bottom));
  height: calc(1.32rem + env(safe-area-inset-bottom));
}
.footer-item {
  flex: 1;
  word-break: break-all;
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
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
}
.sprite-icon_goucai_unselected {
  width: 0.6rem;
  height: 0.6rem;
}
// .icon {
//   background-image: url(../../assets/image/home.png);
// }
.text {
  margin-top: 0.04rem;
  font-size: 0.24rem;
}
.center {
  position: absolute;
  width: 2rem;
  height: 2rem;
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
  border-radius: 50%;
  border: 1px solid #3d5179;
  bottom: 0px;
  bottom: calc(0px + constant(safe-area-inset-bottom));
  bottom: calc(0px + env(safe-area-inset-bottom));
  background-color: #040925;
}
.iconcenter {
  width: 1rem;
  height: 1rem;
}
#kefu {
    position: fixed;
    right: 0.1rem;
    bottom: 2.2rem;
    z-index: 999;
    width: 50px;
    height: 50px;
    -webkit-animation: wobble 250ms  infinite;
    animation: wobble 10s  infinite;
    background-image: url('data:image/svg+xml;%20charset=utf8,%3Csvg%20t%3D%221575450105478%22%20class%3D%22icon%22%20viewBox%3D%220%200%201220%201024%22%20version%3D%221.1%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20p-id%3D%222883%22%20width%3D%2248%22%20height%3D%2248%22%3E%3Cpath%20d%3D%22M609.524%20103.522c-222.89%200-403.712%20178.472-403.712%20398.78%200%20220.31%20180.823%20398.782%20403.712%20398.782%20222.889%200%20403.712-178.473%20403.712-398.781%200-220.309-180.823-398.781-403.712-398.781v48.762c196.1%200%20354.95%20156.785%20354.95%20350.019s-158.85%20350.019-354.95%20350.019-354.95-156.785-354.95-350.02c0-193.233%20158.85-350.018%20354.95-350.018v-48.762z%22%20fill%3D%22%231296db%22%20p-id%3D%222884%22%3E%3C%2Fpath%3E%3Cpath%20d%3D%22M786.578%20916.34c166.45-69.217%20278.408-231.055%20278.408-414.035%200-248.026-203.847-449.219-455.457-449.219-251.619%200-455.457%20201.188-455.457%20449.22%200%2055.397%2010.152%20109.367%2029.718%20159.975%204.855%2012.56-1.39%2026.677-13.949%2031.533-12.56%204.855-26.677-1.39-31.532-13.949a490.396%20490.396%200%200%201-3.042-8.078c-1.85%200.077-3.711%200.116-5.581%200.116C58.06%20671.903%200%20614.597%200%20543.903c0-65.005%2049.09-118.69%20112.68-126.91C153.65%20182.56%20360.56%204.324%20609.528%204.324c248.962%200%20455.877%20178.24%20496.85%20412.67%2063.583%208.225%20112.669%2061.907%20112.669%20126.909%200%2070.694-58.06%20128-129.686%20128-1.89%200-3.771-0.04-5.642-0.119-47.536%20129.702-148.34%20235.841-279.493%20290.027-1.161%2033.464-29.012%2060.24-63.2%2060.24-34.925%200-63.237-27.944-63.237-62.416%200-34.471%2028.312-62.415%2063.237-62.415%2017.892%200%2034.048%207.333%2045.551%2019.12z%22%20fill%3D%22%231296db%22%20p-id%3D%222885%22%3E%3C%2Fpath%3E%3Cpath%20d%3D%22M609.528%20611.405c-58.933%200-112.056-10.644-158.472-28.342-16.123-6.147-30.211-12.702-42.138-19.208-6.926-3.777-11.447-6.59-13.437-7.972-19.24-13.373-44.428%205.446-37.059%2027.688%2035.296%20106.527%20136.054%20179.913%20251.106%20179.913%20115.05%200%20215.796-73.384%20251.092-179.913%207.37-22.243-17.82-41.062-37.06-27.687-1.99%201.383-6.51%204.195-13.434%207.972-11.926%206.505-26.012%2013.06-42.133%2019.207-46.413%2017.698-99.533%2028.342-158.465%2028.342z%22%20fill%3D%22%231296db%22%20p-id%3D%222886%22%3E%3C%2Fpath%3E%3C%2Fsvg%3E');
  }
</style>