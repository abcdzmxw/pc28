<template>
  <div class="actionCenter">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('huodongzhongxin')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <ul class="active-list">
          <li class="active-item">
            <img src="@/assets/image/1.jpg" alt="" />
          </li>
          <li class="active-item">
            <img src="@/assets/image/2.jpg" alt="" />
          </li>
          <li class="active-item">
            <img src="@/assets/image/3.jpg" alt="" />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "ActionCenter",
  components: {},
  data() {
    return {};
  },
  methods: {
    changego() {
      this.$router.go(-1);
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
.active-list {
  padding: 0.2rem;
}
.active-item:not(:last-of-type) {
  margin-bottom: 0.2rem;
}
</style>