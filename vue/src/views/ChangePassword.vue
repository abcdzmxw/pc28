<template>
  <div class="changePassword">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('xiugaidenglu')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <div class="form">
          <van-field v-model="after_code" :placeholder="$t('pljiumima')">
            <template #label>
              <span class="addtitle">{{$t('jiumima')}}</span>
            </template>
          </van-field>
          <van-field v-model="new_code"  :placeholder="$t('plxinmima')">
            <template #label>
              <span class="addtitle">{{$t('xinmima')}}</span>
            </template>
          </van-field>
          <van-field v-model="new_codeOk"  :placeholder="$t('plquerenmima')">
            <template #label>
              <span class="addtitle">{{$t('querenmima')}}</span>
            </template>
          </van-field>
        </div>
        <div class="finish-btn">
          <van-button
            type="primary"
            round
            block
            class="Btnlogin"
            @click="changePWD"
            >{{$t('wancheng')}}</van-button
          >
        </div>
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
import userApi from "@/api/user";
export default {
  name: "ChangePassword",
  components: { AlertPop },
  data() {
    return {
      new_code: "",
      new_codeOk: "",
      after_code: "",
      PopAlert: false,
      TipsTxt: this.$t('Tips.szcg'),
    };
  },
  methods: {
    showAletfalse() {
      this.PopAlert = false;
      this.$router.go(-1);
    },
    changego() {
      this.$router.go(-1);
    },
    changePWD() {
      if (!this.after_code) {
        this.$toast(this.$t('pljiumima'));
      } else if (!this.new_code) {
        this.$toast(this.$t('plxinmima'));
      } else if (!this.new_codeOk) {
        this.$toast(this.$t('plquerenmima'));
      } else if (this.new_code != this.new_codeOk) {
        this.$toast(this.$t('Tips.buyizhi'));
      } else {
        this.$toast.loading({
          message: this.$t('jiazaizhong'),
          forbidClick: true,
          duration: 300,
        });

        userApi
          .newPass(this.new_code, this.after_code)
          .then((data) => {
            console.log(data.data);
            this.PopAlert = true;
          })
          .catch((err) => {
            this.$toast.fail(err.msg);
          });
      }
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
.tips {
  padding: 0.1rem 0.2rem;
  margin-top: 0.4rem;
  font-size: 0.24rem;
  text-align: left;
}
.c_a0a0a0 {
  color: #a0a0a0;
}
.addtitle {
  color: #fff;
}

.van-cell::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #3d5179;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.finish-btn {
  margin-top: auto;
  margin-bottom: 0.4rem;
  padding: 0.2rem;
}
.Btnlogin {
  color: #fff;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
</style>