<template>
  <div class="system">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
           <van-icon name="arrow-left" color="#fff" size='20px'/>
          </div>
          <div class="middle fs-18">{{$t('xitongshezhi')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
        <div class="g-content">
          <div class="shock-wrap">
            <van-cell center >
              <template #title>
                <span>{{$t('yinglizhendong')}}</span>
              </template>
              <template #right-icon>
                <van-switch v-model="checked" size="24px"  active-color="#07c160"  />
              </template>
            </van-cell>
            
          </div>
        </div>
      </header>
    </div>
  </div>
</template>
<script>
export default {
  name: "System",
  components: {},
  data() {
    return {
      checked: false,
    };
  },
  methods: {
        changego(){
          this.$router.go(-1)
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
.shock-wrap {
  margin-top: 0.3rem;
  font-size: 0.32rem;
  font-weight: bold;
  background-color: #1a243f;
}
.van-cell {
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  box-sizing: border-box;
  width: 100%;
  padding: 10px 16px;
  overflow: hidden;
  color: unset;
  font-size: 14px;
  line-height: 24px;
  background-color: unset;
      text-align: left;
    color: #fff;
}

.van-cell__title{
    text-align: left;
}
</style>