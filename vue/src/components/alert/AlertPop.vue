<template>
  <div class="alertpop">
    <!-- 弹出框---游客模式弹出 -->
    <van-popup v-model:show="show" v-if="alertType==1" class="morealert">
      <p class="title fs-16">{{ $t('tishi') }}</p>
      <p class="text c_a0a0a0">
     {{Tips}}
      </p>
      <div class="btnWrap">
        <div class="btn-confrim" @click="changEmit">{{ $t('zhidao') }}</div>
      </div>
    </van-popup>
    <!-- 弹出框-转出失败 -->
    <van-popup v-model:show="showerr" v-if="alertType==2" class="morealert">
      <img src="@/assets/image/shibai.png" class="icon" alt="" />
      <p class="title fs-16">{{ $t('zhuanchusb') }}</p>
      <p class="text c_a0a0a0">{{ $t('zhuanchusbs') }}</p>
      <div class="btnWrap">
        <div class="btn-confrim" @click="changEmit">{{ $t('zhidao') }}</div>
      </div>
    </van-popup>
    <!-- 安全吗设置成功 -->
    <van-popup v-model:show="showsucc" v-if="alertType==3" class="morealert">
      <img src="@/assets/image/chenggong.png" class="icon" alt="" />
      <p class="title fs-16">{{Tips}}</p>
      <div class="btnWrap">
        <div class="btn-confrim" @click="changEmit">{{ $t('zhidao') }}</div>
      </div>
    </van-popup>
  </div>
</template>
<script>
export default {
  name: "Alertpop",
  components: {},
  props: ["alertType","Tips"],
  data() {
    return {
      show: true,
      showerr: true,
      showsucc: true,
    };
  },
  watch: {
    // alertType(newVal) {},
  },
  methods: {
    changEmit(){
      this.$emit('showAletfalse',false)
    }
  },
  mounted() {
  
  },
};
</script>
<style lang="less" scoped>
</style>