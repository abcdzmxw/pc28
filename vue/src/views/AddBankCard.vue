<template>
  <div class="addBankCard">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('tianjiayinhangka')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <div class="tips c_a0a0a0">{{$t('yhkTps')}}</div>
        <div class="form">
          <van-field v-model="acc" :placeholder="$t('plchikaren')">
            <template #label>
              <span class="addtitle">{{$t('chiakren')}}</span>
            </template>
          </van-field>
          <van-field v-model="acc_name" type='number' :placeholder="$t('plkahao')">
            <template #label>
              <span class="addtitle">{{$t('yinhangkakahao')}}</span>
            </template>
          </van-field>
          <van-field v-model="acc_bank" :placeholder="$t('plyinhangmingcheng')">
            <template #label>
              <span class="addtitle">{{$t('yinhangmingcheng')}}</span>
            </template>
          </van-field>
          <!-- <van-field v-model="value" placeholder=this.$t('plusername')>
            <template #label>
              <span class="addtitle">开户地点</span>
            </template>
          </van-field>
                <van-field v-model="value" placeholder=this.$t('plusername')>
            <template #label>
              <span class="addtitle">开户支行</span>
            </template>
          </van-field> -->
          <van-cell>
            <van-button
              type="primary"
              round
              block
              class="Btnlogin"
              @click="btnSubmit"
              >完成</van-button
            >
          </van-cell>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import userApi from "@/api/user";
export default {
  name: "addBankCard",
  components: {},
  data() {
    return {
      acc: "",
      acc_name: "",
      acc_bank: "",
      mark: "",
      type: 0,
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    btnSubmit() {
      if (!this.acc) {
        this.$toast(this.$t('plchikaren'));
      } else if (!this.acc_name) {
        this.$toast(this.$t('plkahao'));
      } else if (!this.acc_bank) {
        this.$toast(this.$t('plyinhangmingcheng'));
      } else {
        let that=this
        userApi.setPayInfo(this.acc,this.acc_name,this.acc_bank,'',this.type).then((data) => {
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
  padding: 0.3rem 0.4rem;
  text-align: left;
  font-size: 0.38rem;
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
.Btnlogin {
  color: #fff;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
</style>