<template>
  <div class="addVirtualWallet">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{$t('shezhianquanma')}}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
      </header>
      <div class="g-content">
        <!-- 第一次设置安全码显示这个 -->
        <template v-if="setCode == 'false'">
          <div class="form-wrapper is-not-set">
            <div class="lock-bg">
              <div class="paycode">
                <van-password-input
                  :value="new_code"
                  :focused="showKeyboard"
                  @focus="showKeyboard = true"
                />
                <van-number-keyboard
                  v-model="new_code"
                  :show="showKeyboard"
                  @blur="showKeyboard = false"
                />
              </div>
              <p class="c_a0a0a0" style="font-size: 0.4rem">
              {{$t('anquanmaTps')}}
              </p>
            </div>
            <div class="tips">
              <img src="@/assets/image/tishi.png" alt="" />
              <span class="c_a0a0a0"
                >{{$t('anquanmaTpsTow')}}</span
              >
            </div>
          </div>
        </template>
        <!-- 后续修改安全码显示这个 -->
        <div class="form" v-if="setCode == 'true'">
          <van-field
            v-model="after_code"
            type="password"
         :placeholder="$t('planquanma')"
          >
            <template #label>
              <span class="addtitle">{{$t('yuananquanma')}}</span>
            </template>
          </van-field>
          <van-field
            v-model="new_code"
            maxlength="6"
            type="password"
            :placeholder="$t('plxinanquanma')"
          >
            <template #label>
              <span class="addtitle">{{$t('xinanquanma')}}</span>
            </template>
          </van-field>
        </div>
        <div class="finish-btn" v-if="setCode == 'true'">
          <van-button
            type="primary"
            round
            block
            class="Btnlogin"
            @click="setSafety"
            >{{$t('queren')}}</van-button
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
  name: "AddVirtualWallet",
  components: {
    AlertPop,
  },
  data() {
    return {
      PopAlert: false,
      setCode: null,
      showKeyboard: true,
      value: "",
      new_code: "",
      after_code: "",
      TipsTxt: this.$t('Tips.szcg'),
    };
  },
  watch: {
    new_code(value) {
      if (value.length === 6 && this.setCode == "false") {
        // 调用接口
        this.showKeyboard = false;
        this.$toast.loading({
          message: this.$t('jiazaizhong'),
          forbidClick: true,
          duration: 300,
        });

        userApi
          .setSafety(this.new_code, this.after_code)
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
  methods: {
    showAletfalse() {
      this.PopAlert = false;
      this.$router.go(-1);
    },
    changego() {
      this.$router.go(-1);
    },
    setSafety() {
      if (!this.after_code) {
        this.$toast(this.$t('planquanma'));
      } else if (!this.new_code && this.setCode) {
        this.$toast("plxinanquanma");
      } else {
        this.$toast.loading({
          message: this.$t('jiazaizhong'),
          forbidClick: true,
          duration: 0,
        });

        userApi
          .setSafety(this.new_code, this.after_code)
          .then((data) => {
            console.log(data.data);
            this.$toast.clear();
            this.PopAlert = true;
          })
          .catch((err) => {
            this.$toast.clear();
            this.$toast.fail(err.msg);
          });
      }
    },
  },
  mounted() {},
  created() {
    this.setCode = this.$route.query.isTrue;
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
.lock-bg {
  position: relative;
  text-align: center;
  width: 9rem;
  height: 6rem;
  margin: 0 auto;
  margin-top: 1.8rem;
  background: url(../assets/image/anquanma.png) no-repeat center center;
  background-size: 100% 100%;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: reverse;
  -ms-flex-direction: column-reverse;
  flex-direction: column-reverse;
  padding: 0.6rem 0.2rem;
}
.paycode {
  margin-top: 0.2rem;
}
.van-number-keyboard {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 100;
  width: 100%;
  padding-bottom: constant(safe-area-inset-bottom);
  padding-bottom: env(safe-area-inset-bottom);
  background-color: #1a243f;
  -webkit-user-select: none;
  user-select: none;
}
.tips {
  width: 8.4rem;
  display: flex;
  margin: 0.4rem auto 1rem;
  align-items: flex-start;
  font-size: 0.35rem;
  img {
    width: 0.4rem;
    margin-right: 0.12rem;
  }
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