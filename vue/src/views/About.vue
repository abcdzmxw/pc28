<template>
  <div class="about">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('guanyuwomen')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
        <div class="g-content">
          <div class="logo-box">
            <img src="@/assets/image/logo.png" class="logo" alt="" />
            <p class="version">{{$t('dangqianbanben')}}：21.04.02</p>
          </div>
          <p class="copyright">{{$t('banquansuoyou')}}©MXC</p>
        </div>
      </header>
    </div>
  </div>
</template>
<script>
export default {
  name: "About",
  components: {},
  data() {
    return {};
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
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
.logo-box {
  margin-top: 3rem;
}
.logo {
  width: 1.8rem;
  height: 1.8rem;
}
.version {
  margin-top: 0.4rem;
  font-size: 0.4rem;
}
.copyright {
  position: absolute;
  width: 100%;
  bottom: 0.6rem;
  font-size: 0.4rem;
}
</style>