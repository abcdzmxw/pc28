<template>
  <div class="setting">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{ type == 1 ?  $t('shezhi')  :  $t('zhanghuxinxni') }}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <ul class="clear" v-if="type == 1">
          <li @click="changeline">
            <div class="square">
              <img src="@/assets/image/seting.png" class="" alt="" />
              <p>{{ $t('genggaixianlu') }}</p>
            </div>
          </li>
          <li @click="changeabout">
            <div class="square">
              <img src="@/assets/image/guanyu.png" class="" alt="" />
              <p>{{ $t('guanyuwomen') }}</p>
            </div>
          </li>
          <li @click="changeSystem">
            <div class="square">
              <img src="@/assets/image/xitong.png" class="" alt="" />
              <p>{{ $t('xitongshezhi') }}</p>
            </div>
          </li>
          <li @click="changeLanguage">
            <div class="square">
              <img src="@/assets/image/yuyan.png" class="" alt="" />
              <p>{{ $t('yuyanshezhi') }}</p>
            </div>
          </li>
        </ul>
        <!-- 账户信息的 -->
        <ul class="clear" v-if="type == 2">
          <li @click="changebane(0)">
            <div class="square">
              <img src="@/assets/image/yhk.png" class="" alt="" />
              <p>{{ $t('bangdingyinhangka') }}</p>
            </div>
          </li>
          <li @click="changebane(1)">
            <div class="square">
              <img src="@/assets/image/qianbao.png" class="" alt="" />
              <p>{{ $t('bangdingxuniqianbao') }}</p>
            </div>
          </li>
          <li @click="changeSetSecurityCode">
            <div class="square">
              <img src="@/assets/image/anquan.png" class="" alt="" />
              <p>{{ $t('anquanma') }}</p>
            </div>
          </li>
          <li @click="changeChangePassword">
            <div class="square">
              <img src="@/assets/image/denglu.png" class="" alt="" />
              <p>{{ $t('xiugailenglumima') }}</p>
            </div>
          </li>
        </ul>
        <!-- 退出登录 -->
        <div class="button-box" @click="changelogin">
          <van-button type="primary" block round>{{ $t('tuichudenglu') }}</van-button>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import userApi from "@/api/user";
export default {
  name: "Setting",
  components: {},
  data() {
    return {
      type: null,
    };
  },
  methods: {
    changeline() {
      this.$router.push({
        path: "/changeline",
      });
    },
    changeabout() {
      this.$router.push({
        path: "/about",
      });
    },
    changeSystem() {
      this.$router.push({
        path: "/system",
      });
    },
    changeLanguage() {
      this.$router.push({
        path: "/language",
      });
    },
    changebane(type) {
      this.$router.push({
        path: "/bankCardManage",
        query: {
          type: type,
        },
      });
    },
    changeSetSecurityCode() {
      userApi.safetyInfo().then((data) => {
        console.log(data.data.isTrue);
        let isTrue = data.data.isTrue;
        this.$router.push({
          path: "/setSecurityCode",
          query: {
            isTrue: isTrue,
          },
        });
      });
    },
    changeChangePassword() {
      this.$router.push({
        path: "/changePassword",
      });
    },
    changego() {
      this.$router.go(-1);
    },
    changelogin() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      this.$router.push({
        path: "/login",
      });
      this.$toast.clear();
    },
  },
  mounted() {
    this.type = this.$route.query.type;
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
.iconfont {
  display: block;
  height: 0.88rem;
  line-height: 0.88rem;
  font-size: 0.32rem;
}
.g-content {
  flex: 1;
  overflow-y: scroll;
}
.clear {
  padding: 0.08rem;
}
.square {
  height: 2.5rem;
  text-align: center;
  margin: 0 auto;
  width: 100%;
  background-color: #1a243f;
  border-radius: 2px;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}
ul li {
  padding: 0.08rem;
  width: 33.33%;
  float: left;
  p {
    font-size: 0.35rem;
    margin-top: 0.2rem;
  }
}
ul li .square img {
  margin: 0 auto;
  width: 0.9rem;
  height: 0.9rem;
}
.button-box {
  width: 80%;
  position: absolute;
  left: 50%;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
  bottom: 0.8rem;
  button {
    color: #fff;
    background-color: #7d58c6;
    border: 1px solid #7d58c6;
  }
}
</style>