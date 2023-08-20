<template>
  <div class="notice">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
     <div class="left" @click="changego">
           <van-icon name="arrow-left" color="#fff" size='20px'/>
          </div>
          <div class="middle fs-18">{{$t('xiaoxigonggao')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <van-tabs v-model="active" animated swipeable>
          <van-tab>
            <template #title>{{$t('zhanneixiaoxi')}} </template>
            <!-- 内容 -->
            <div class="warpnotice">
              <div style="margin-top: 3.2rem">{{$t('wuTps')}}</div>
            </div>
          </van-tab>
          <van-tab>
            <template #title>{{$t('pingtaigonggao')}} </template>
            <!-- 内容 -->
            <div class="warpnotice">
              <div style="margin-top: 3.2rem">{{$t('wuTps')}}</div>
            </div>
          </van-tab>
        </van-tabs>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Notice",
  components: {},
  data() {
    return {
      active: 1,
    };
  },
  methods: {
        changego(){
          this.$router.go(-1)
    },
  },
  mounted() {},
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
.warpnotice {
  height: 100%;
}
</style>