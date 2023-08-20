<template>
  <div class="register">
    <header>
      <div class="g-header">
        <div class="left">
          <div class="close-btn" @click="changeHome">
            <i class="icon-x"></i>
          </div>
        </div>
        <div class="middle fs-18">
          <div class="title">{{ $t("zhuce") }}</div>
        </div>
      </div>
    </header>
    <div class="g-content flex-column">
      <div class="register-main">
        <van-cell-group class="login-body van-cell-group">
          <van-field
            :placeholder="$t('plusername')"
            v-model="username"
            clearable
          >
            <template #left-icon>
              <img
                src="@/assets/image/user.png"
                class="left-form-icon"
                alt=""
              />
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
        </van-cell-group>
        <div class="register-bottom">
          <div>
            <van-button
              type="primary"
              round
              block
              class="Btnlogin"
              @click="ChangeRegister"
              >{{ $t("wanchengzhuce") }}</van-button
            >
            <div class="has-acoount">
              <span>{{ $t("yiyouzhanghao") }}?</span>
              <span class="btn-tologin" @click="changeLogin">{{
                $t("qingdenglu")
              }}</span>
            </div>
          </div>

          <p>{{ $t("dangqianbanben") }}：21.04.02</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import loginApi from "@/api/login";
import storeAction from "@/store/typed-actions";
export default {
  name: "Register",
  components: {},
  data() {
    return {
      username: "",
      password: "",
      inviter_id: "",
    };
  },
  methods: {
    changeLogin() {
      this.$router.push({
        path: "/login",
      });
    },
    changeHome() {
      this.$router.push({
        path: "/",
      });
    },
    ChangeRegister() {
      if (!this.username) {
        this.$toast(this.$t('plusername'));
      } else if (!this.password) {
        this.$toast(this.$t('plpassword'));
      } else {
        loginApi
          .register(this.username, this.password, "1", this.inviter_id)
          .then((data) => {
            console.log(data.data, "注册成功");
            storeAction.loginDone(data.data);
          })
          .catch((err) => {
            this.$toast.clear();
            this.$toast.fail(err.msg);
          });
      }
    },
  },
  mounted() {
    // http://localhost:8080/register?inviter_id=37
    console.log(this.$route.query.inviter_id);
    this.inviter_id = this.$route.query.inviter_id;
  },
};
</script>
<style lang="less" scoped>
.register {
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  flex-direction: column;
  min-height: 100%;
}
.g-header {
  position: absolute;
  width: 100%;
  height: 1.18rem;
  text-align: center;
  z-index: 1;
  color: #fff;
  background: rgb(14, 21, 48);
}
.left {
  z-index: 11;
  position: absolute;
  height: 100%;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.close-btn {
  padding: 0.3rem;
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
.middle {
  z-index: 10;
  max-width: 80%;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}
.fs-18 {
  font-size: 0.43rem;
}
.title {
  width: 100%;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.register-main {
  flex: 1;
  padding: 2rem 0.8rem 0.2rem;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  color: #a0a0a0;
  text-align: center;
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
  border-bottom: 0px solid #3d5179 !important;
  -webkit-transform: scaleY(0.5);
  transform: scaleY(0.5);
}
.left-form-icon {
  width: 20px;
}
.c_a0a0a0 {
  color: #a0a0a0;
}
.register-bottom {
  margin-top: 4.2rem;
  flex: 1;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
}
.Btnlogin {
  color: #fff;
  background-color: #7d58c6;
  border: 1px solid #7d58c6;
}
.has-acoount {
  margin-top: 0.2rem;
}
.btn-tologin {
  margin-left: 0.3rem;
  color: #4bb3e8;
}
</style>
