<template>
  <div class="shareFriend">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{ $t("tuijianfenxiang") }}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content bg_init">
        <div class="qrcode-body">
          <div style="padding: 0.2rem; background: #fff">
            <div id="qrcode" ref="qrcode"></div>
          </div>
          <div class="button-box">
            <van-button type="primary" size="small" block round class="btncopy"
              @click="copy($event)"  >{{$t('fuzhilanjie')}}</van-button
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import QRCode from "qrcodejs2";
import Clipboard from 'clipboard'
export default {
  name: "ShareFriend",
  components: {},
  data() {
    return {};
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    copy(e){
          let url =
      window.location.protocol +
      "//" +
      window.location.host +
      "/register?inviter_id=" +
      this.userId;
      console.log(url)
            const clipboard = new Clipboard(e.target, { text: () => url });
      clipboard.on("success", () => {
        this.$toast(this.$t('fzjqb'));
        clipboard.off("error");
        clipboard.off("success");
        clipboard.destroy();
      });
    },
  },
  computed: {
    userId() {
      console.log(this.$store.state.currentUserId);
      return this.$store.state.currentUserId;
    },
  },
  mounted() {
    let url =
      window.location.protocol +
      "//" +
      window.location.host +
      "/register?inviter_id=" +
      this.userId;
    this.qr = new QRCode("qrcode", {
      width: 200,
      height: 200, // 高度
      text: url, // 二维码内容
      // render: 'canvas' // 设置渲染方式（有两种方式 table和canvas，默认是canvas）
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H,
    });
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
.bg_init {
  background-color: inherit;
}
.qrcode-body {
  width: 7rem;
  min-height: 7rem;
  margin: 0 auto;
  margin-top: 1rem;
  padding: 0.4rem 0;
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
  border-radius: 0.1rem;
  background: #30385d;
}
#qrcode {
  display: inline-block;
  img{
    width: 100%;
  height:100%
  }
}
.button-box {
  margin-top: 0.3rem;
  width: 65%;
}
.btncopy {
  color: rgb(255, 255, 255);
  background: rgb(125, 88, 198);
  border-color: rgb(125, 88, 198);
}
</style>