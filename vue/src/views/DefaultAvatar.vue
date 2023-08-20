<template>
  <div class="language">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left">
                  <div class="left" @click="changego">
           <van-icon name="arrow-left" color="#fff" size='20px'/>
          </div>
          </div>
          <div class="middle fs-18">请选择系统头像</div>
          <div class="right">
            <div class="fs-16">确认</div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <ul class="avatar-list clear">
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
            <img src="@/assets/image/xuanze.png" class="icon-radio" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
          <li class="avatar-item pos-r">
            <img src="@/assets/image/auth.png" class="avatar" alt="" />
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "Language",
  components: {},
  data() {
    return {};
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
.avatar-list {
}
.avatar-item {
  width: 25%;
  float: left;
  text-align: center;
  margin-top: 0.5rem;
}
.pos-r {
  position: relative;
}
.avatar {
  display: inline-block;
  width: 1.3rem;
  height: 1.3rem;
  border-radius: 50%;
  -o-object-fit: cover;
  object-fit: cover;
}
.icon-radio {
  position: absolute;
  width: 0.6rem;
  bottom: 0;
  right: 0.08rem;
}
</style>