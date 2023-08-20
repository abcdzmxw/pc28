<template>
  <div class="addVirtualWallet">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('tianjiaxuniqianbao')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <div class="form">
          <van-field v-model="acc" :placeholder="$t('plqbdz')">
            <template #label>
              <span class="addtitle">{{$t('nixiqianbaodizhi')}}</span>
            </template>
          </van-field>
          <van-field v-model="acc_name" :placeholder="$t('plqblx')">
            <template #label>
              <span class="addtitle">{{$t('xuniqianbaoleixing')}}</span>
            </template>
          </van-field>
          <div class="tips c_a0a0a0">
           {{$t('xnqbTps')}}
          </div>
        </div>
        <div class="finish-btn">
          <van-button type="primary" round block class="Btnlogin" @click="btnSubmit">完成</van-button
          >
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import userApi from "@/api/user";
export default {
  name: "AddVirtualWallet",
  components: {},
  data() {
    return {
      acc: "",
      acc_name: "",
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    btnSubmit() {
      if (!this.acc) {
        this.$toast(this.$t('plqbdz'));
      } else if (!this.acc_name) {
        this.$toast(this.$t('plqblx'));
      } else {
        let that = this;
        userApi.setPayInfo(this.acc, this.acc_name, "", "", 1).then((data) => {
          console.log(data.msg);
          this.$toast.loading({
            message: this.$t('Tips.tjcg'),
            forbidClick: true,
            duration: 500,
            onClose() {
              that.$router.go(-1);
            },
          });
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