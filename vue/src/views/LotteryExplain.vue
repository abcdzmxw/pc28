<template>
  <div class="lotteryExplain">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('chanpingshuoming')}}</div>
          <div class="right"></div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <div class="notice-main">
          <div class="noticetitle">
            <p>SINGAPORE {{$t('chanpingshuoming')}}</p>
          </div>
          <div class="notice-content">
              <div>
                  <p>{{$t('chanpin1')}}</p>
                  <p>{{$t('chanpin2')}}</p>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "LotteryExplain",
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
.g-content {
  flex: 1;
  overflow: scroll;
  padding: 0.6rem 0.4rem;
}
.notice-main {
  background-color: #1a243f;
  border-radius: 5px;
  border: 1px solid #3d5179;
  margin: 0 auto;
  height: 100%;
  position: relative;
}
.noticetitle {
  position: absolute;
  color: #4bb3e8;
  left: 50%;
  top: 0;
  font-size: 0.5rem;
  transform: translate(-50%, -50%);
  width: 95%;
  text-align: center;
}
.noticetitle p {
  display: inline-block;
  padding: 0 0.06rem;
  background-color: #30385d;
  text-decoration: underline;
}
.notice-content{
    padding: 0.5rem 0.4rem;
    height: 100%;
    font-size: 0.35rem;
    overflow: scroll;
    p{
        text-align: left;
    }
}
.notice-content p:not(:first-child) {
    margin-top: 0.3rem;
}
</style>