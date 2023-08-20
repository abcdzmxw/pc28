<template>
  <div class="baseinfo">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(70, 105, 154)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{ $t("jibenziliao") }}</div>
          <div class="right">
            <div class="btn-save fs-16" @click="submit" v-if="!usercrud">保存</div>
          </div>
        </div>
        <div class="blockHeight"></div>
        <div class="g-content">
          <div class="head-avatar">
            <img src="@/assets/image/auth.png" alt="" />
            <div class="change-btn" @click="show = true">更改头像</div>
          </div>
          <van-cell-group>
            <van-field :placeholder="username" readonly input-align="right">
              <template #label>
                <span class="c_c8c9cc">{{ $t("dengluzhanghao") }}</span>
              </template>
              <span></span>
            </van-field>
            <van-field
              v-model="value"
              :placeholder="username"
              input-align="right"
              is-link
              readonly
            >
              <template #label>
                <span class="c_fff">{{ $t("nicheng") }}</span>
              </template>
            </van-field>
            <van-field
              v-model="usercrud"
              placeholder="请输入身份证号码"
              input-align="right"
              is-link
              v-if="usercrud"
              readonly
            >
              <template #label>
                <span class="c_fff"> 身份证号码</span>
              </template>
            </van-field>
            <van-field
              v-model="crud"
              placeholder="请输入身份证号码"
              input-align="right"
              is-link
              v-else
            >
              <template #label>
                <span class="c_fff"> 身份证号码</span>
              </template>
            </van-field>
          </van-cell-group>
        </div>
      </header>
      <!-- 动作面板 -->

      <van-action-sheet
        v-model:show="show"
        close-on-click-action
        style="background: none"
      >
        <div class="system-panle">
          <ul>
            <li class="boderBottom">从相册选择</li>
            <li class="boderBottom">拍照</li>
            <li class="boderBottom" @click="ChangeAuth">系统默认图片</li>
          </ul>
          <div class="cancel-btn" @click="show = false">取消</div>
          <div class="cancel-btn" @click="show = false">取消</div>
        </div>
      </van-action-sheet>
    </div>
  </div>
</template>
<script>
import storeAction from "@/store/typed-actions";
import userApi from "@/api/user";
export default {
  name: "Baseinfo",
  components: {},
  data() {
    return {
      show: false,
      username: null,
      crud: "",
    };
  },
  computed: {
    usercrud() {
      console.log(this.$store.state.usercrud, "12");
      return this.$store.state.usercrud;
    },
  },
  methods: {
    ChangeAuth() {
      this.$router.push({
        path: "/defaultAvatar",
      });
    },
    changego() {
      this.$router.go(-1);
    },
    submit(){
            this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      userApi
        .profile(this.crud)
        .then((data) => {
          console.log(data.data);
          this.$toast.clear();
                  storeAction.setsessionStorage("idcard", this.crud);
          storeAction.setCurrentUserusercrud(this.crud);
             this.$router.go(-1);
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    }
  },
  created() {
    this.username = this.$route.query.name;
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
.head-avatar {
  background: #46699a;
  padding: 0.3rem 0;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-bottom: 0.3rem;
  img {
    width: 1.35rem;
    height: 1.35rem;
    border-radius: 50%;
    -o-object-fit: cover;
    object-fit: cover;
  }
}
.change-btn {
  margin: 0.2rem 0;
  border-radius: 0.5rem;
  padding: 0.04rem 0.3rem;
  height: 0.7rem;
  line-height: 0.7rem;
  background-color: #fff;
  color: #4b77ff;
  font-size: 0.35rem;
}
.van-cell-group {
  background-color: unset;
}
.van-cell-group::after {
  border: 0 solid #3d5179;
  position: absolute;
  box-sizing: border-box;
  content: " ";
  pointer-events: none;
  top: -50%;
  right: -50%;
  bottom: -50%;
  left: -50%;
  -webkit-transform: scale(0.5);
  transform: scale(0.5);
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
.c_c8c9cc {
  color: #c8c9cc;
  font-size: 0.4rem;
}
.van-cell--clickable:active {
  background-color: #30385d;
}
.c_fff {
  color: #fff;
  font-size: 0.4rem;
}
.van-action-sheet {
  max-height: 80%;
  color: #fff;
}
.system-panle {
  padding: 0 0.2rem 0.2rem;
  text-align: center;
  ul {
    color: #89c4ff;
    border-radius: 5px;
    background-color: #374c7c;
    font-size: 0.35rem;
    li {
      padding: 0.4rem 0;
    }
    li:active {
      background: #ededee;
    }
  }
}
.boderBottom {
  position: relative;
}
.van-action-sheet {
  background: none;
}
.cancel-btn {
  border-radius: 5px;
  margin-top: 0.1rem;
  padding: 0.4rem 0;
  background-color: #374c7c;
  color: #4bb3e8;
  font-size: 0.35rem;
}
.boderBottom:after {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  right: 0;
  height: 1px;
  border-bottom: 1px solid #3d5179;
  color: #3d5179;
  -webkit-transform-origin: 0 100%;
  transform-origin: 0 100%;
  transform: scaleY(0.5);
}
</style>