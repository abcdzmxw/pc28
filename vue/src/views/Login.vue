<template>
  <div class="login">
    <div class="close-btn" @click="changeRouteHome">
      <i class="icon-x"></i>
    </div>
    <div class="g-content">
      <div class="login-head">
        <img src="@/assets/image/logo.png" class="app-logo" alt="" />
      </div>
      <van-cell-group class="login-body van-cell-group">
        <van-field :placeholder="$t('plusername')" v-model="username" clearable>
          <template #left-icon>
            <img src="@/assets/image/user.png" class="left-form-icon" alt="" />
          </template>
        </van-field>
        <van-field
          clearable
          :placeholder="$t('plpassword')"
          type="password"
          v-model="password"
        >
          <template #left-icon>
            <img src="@/assets/image/pwd.png" class="left-form-icon" alt="" />
          </template>
        </van-field>
        <van-cell>
          <template #title>
            <van-checkbox v-model="checked"
              ><span class="c_a0a0a0">{{ $t("jizhumima") }}</span>
            </van-checkbox>
          </template>
          <template #value>
            <span class="c_a0a0a0" @click="changePWDho"
              >{{ $t("wangjimima") }}?</span
            >
          </template>
        </van-cell>
        <van-cell>
          <van-button
            type="primary"
            round
            block
            class="Btnlogin"
            @click="changeHome"
            >{{ $t("denglu") }}</van-button
          >
        </van-cell>
        <van-cell>
          <template #title>
            <div class="content open-visitor">
              <van-button
                type="primary"
                round
                size="normal"
                class="TxtBtn"
                @click="changeTourist"
                >{{ $t("youkedenglu") }}</van-button
              >
              <van-button
                type="primary"
                round
                size="normal"
                class="TxtBtn"
                @click="changeRegister"
                >{{ $t("zhucezhanghao") }}</van-button
              >
            </div>
          </template>
        </van-cell>
      </van-cell-group>
    </div>
    <p class="app-version">{{ $t("dangqianbanben") }}：21.04.02</p>
    <alert-pop
      :alertType="3"
      :Tips="TipsTxt"
      v-if="PopAlert"
      @showAletfalse="showAletfalse"
    ></alert-pop>
  </div>
</template>

<script>
// @ is an alias to /src
import loginApi from "@/api/login";
import storeAction from "@/store/typed-actions";
import AlertPop from "@/components/alert/AlertPop.vue";
export default {
  name: "Login",
  components: {
    AlertPop,
  },
  data() {
    return {
      checked: true,
      username: "",
      password: "",
      PopAlert: false,
      TipsTxt: this.$t('Tips.wjmm'),
    };
  },
  methods: {
    changeRegister() {
      this.$router.push({
        path: "/register",
      });
    },
    changeHome() {
      if (!this.username) {
        this.$toast(this.$t('plusername'));
      } else if (!this.password) {
        this.$toast(this.$t('plpassword'));
      } else {
        if (this.checked) {
          this.setCookie(this.username, this.password, 14);
        } else {
          this.setCookie("", "", -1);
        }
        loginApi
          .login(this.username, this.password)
          .then((data) => {
            console.log(data.data, this.$t('Tips.dlcg'));
            storeAction.loginDone(data.data);
          })
          .catch((err) => {
            this.$toast.clear();
            this.$toast.fail(err.msg);
          });
      }
    },

    // 游客注册
    changeTourist() {
      if (this.checked) {
        let username =
          this.$t("youke") + Math.floor(new Date(new Date()).getTime() / 1000);
        loginApi
          .register(username, this.randomCoding(), "2", "")
          .then((data) => {
            console.log(data.data, "游客注册成功");
            storeAction.loginDone(data.data);
          })
          .catch((err) => {
            this.$toast.clear();
            this.$toast.fail(err.msg);
          });
        this.setCookie(username, "", 14);
      } else {
        this.setCookie("", "", -1);
      }
    },
    // 忘记密码
    changePWDho() {
      this.PopAlert = true;
    },
    showAletfalse() {
      this.PopAlert = false;
    },
    changeRouteHome() {
      let that = this;
      storeAction.clearCurrentUserId();
      storeAction.clearToken();
      storeAction.delsessionStorage("id");
      storeAction.delsessionStorage("userId");
      storeAction.delsessionStorage("money");
      storeAction.delsessionStorage("idcard");
      storeAction.delsessionStorage("group_id");
      storeAction.setCurrentUserMoney(null);
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 500,
        onClose() {
          that.$router.push(`/`);
        },
      });
    },
    // 随机生成字母
    randomCoding() {
      //创建26个字母数组
      let arr = [
        "A",
        "B",
        "C",
        "D",
        "E",
        "F",
        "G",
        "H",
        "I",
        "J",
        "K",
        "L",
        "M",
        "N",
        "O",
        "P",
        "Q",
        "R",
        "S",
        "T",
        "U",
        "V",
        "W",
        "X",
        "Y",
        "Z",
      ];
      let idvalue = "";
      for (let i = 0; i < 6; i++) {
        idvalue += arr[Math.floor(Math.random() * 26)];
      }
      console.log(idvalue, "idvalue");
      return idvalue;
    },
    setCookie(name, pwd, exdays) {
      var exdate = new Date(); // 获取时间
      exdate.setTime(exdate.getTime() + 24 * 60 * 60 * 1000 * exdays); // 保存的天数
      // 字符串拼接cookie
      window.document.cookie =
        "userName" + "=" + name + ";path=/;expires=" + exdate.toGMTString();
      window.document.cookie =
        "userPwd" + "=" + pwd + ";path=/;expires=" + exdate.toGMTString();
    },
    // 读取cookie 将用户名和密码回显到input框中
    getCookie() {
      if (document.cookie.length > 0) {
        var arr = document.cookie.split("; "); // 这里显示的格式需要切割一下自己可输出看下
        for (var i = 0; i < arr.length; i++) {
          var arr2 = arr[i].split("="); // 再次切割
          // 判断查找相对应的值
          if (arr2[0] === "userName") {
            this.username = arr2[1]; // 保存到保存数据的地方
            // 其中unescape() 方法是将字符串进行编码，escape()方法是将字符串进行解码。
          } else if (arr2[0] === "userPwd") {
            this.password = arr2[1];
          }
        }
      }
    },
  },
  created() {
    this.getCookie();
  },
};
</script>
<style lang="less" scoped>
.login {
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  flex-direction: column;
}
.close-btn {
  position: absolute;
  padding: 0.3rem;
  z-index: 1;
}
.icon-x {
  display: inline-block;
  width: 0.5rem;
  height: 0.07rem;
  background: #fff;
  line-height: 0;
  font-size: 0;
  vertical-align: middle;
  transform: rotate(45deg);
}
.icon-x::after {
  content: "";
  display: block;
  width: 0.5rem;
  height: 0.07rem;
  background: #fff;
  transform: rotate(-90deg);
}
.g-content {
  flex: 1;
  //   overflow: scroll;
}
.login-head {
  position: relative;
  height: 4.9rem;
}
.app-logo {
  width: 1.9rem;
  height: 1.9rem;
  border-radius: 0.1rem;
  -o-object-fit: cover;
  object-fit: cover;
  position: absolute;
  left: 50%;
  bottom: 0;
  -webkit-transform: translate(-50%, 50%);
  transform: translate(-50%, 50%);
}
.login-body {
  margin-top: 1.7rem;
  padding: 0 0.68rem;
}
.van-cell-group {
  background: unset;
  position: unset;
  input {
  }
}

.van-cell {
  position: relative;
  display: flex;
  box-sizing: border-box;
  width: 100%;
  padding: 10px 16px;
  overflow: hidden;
  color: unset;
  font-size: 14px;
  line-height: 24px;
  background-color: unset;
}
.van-field::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 1px solid #3d5179 !important;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.left-form-icon {
  width: 20px;
}
.c_a0a0a0 {
  color: #a0a0a0;
}
.Btnlogin {
  color: #fff;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
.TxtBtn {
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
.content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.open-visitor {
  justify-content: space-around;
}
.app-version {
  text-align: center;
  margin-top: 0.4rem;
  color: #a0a0a0;
}
.van-cell::after {
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  right: 16px;
  bottom: 0;
  left: 16px;
  border-bottom: 0px solid #3d5179;
}
</style>
