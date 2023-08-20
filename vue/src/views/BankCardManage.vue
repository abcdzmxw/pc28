<template>
  <div class="bankCardManage">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">
            {{ type == 0 ? $t('yinhangkaguanli') : $t('xuniqianbaoguanli')}}
          </div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <!-- 银行卡 -->
        <ul class="card-list" v-if="type ==0&&bank">
          <li class="card-item">
            <p>{{bank.acc_bank}}</p>
            <p class="cardNo">{{bank.acc_name}}</p>
          </li>
        </ul>
        <!-- 虚拟钱包的卡 -->
        <ul class="card-list" v-if="type == 1&&bank">
          <li class="card-item">
        <p>{{bank.acc}}</p>
            <p class="cardNo">{{bank.acc_name}}</p>
          </li>
        </ul>
        <!-- 没有银行卡就显示btn -->
        <div class="add-card-btn">
          <div class="div" @click="changeAddcard(type)">
            <i></i>
            <span>{{ type == 0?$t('tianjiayinhangka') : $t('tianjiaxuniqianbao') }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import userApi from "@/api/user";
export default {
  name: "BankCardManage",
  components: {},
  data() {
    return {
      type: null,
      bank:null
    };
  },
  methods: {
    changeAddcard(type) {
      if (type == 0) {
        this.$router.push({
          path: "/addBankCard",
        });
      } else {
        this.$router.push({
          path: "/addVirtualWallet",
        });
      }
    },
    changego() {
      this.$router.go(-1);
    },
  },
  mounted() {},
  created() {
    this.type = this.$route.query.type;
    userApi.payInfo(this.type).then((data) => {
      this.bank = data.data;
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
.card-list {
  padding: 0.3rem;
  text-align: left;
}
.card-item {
  border-radius: 0.06rem;
  padding: 0.3rem;
  background: #1a243f;
  font-size: 0.38rem;
}
.cardNo {
  margin-top: 0.1rem;
}
</style>