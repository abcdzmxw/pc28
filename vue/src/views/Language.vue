<template>
  <div class="language">
    <div class="settingwarp">
      <header>
        <div class="g-header" style="background: rgb(14, 21, 48)">
          <div class="left" @click="changego">
            <van-icon name="arrow-left" color="#fff" size="20px" />
          </div>
          <div class="middle fs-18">{{ $t("yuyanshezhi") }}</div>
          <div class="right">
            <div class="bg-icon bg-icon-setting"></div>
          </div>
        </div>
        <div class="blockHeight"></div>
        <div class="g-content">
          <ul class="lang-box">
            <li
              class="lang"
              :class="{ active: show == 0 }"
              @click="(show = 0), languageSet(0)"
            >
              <span>{{ $t("gensuixitong") }}</span>
              <img
                src="@/assets/image/gou.png"
                class="icon"
                alt=""
                v-if="show == 0"
              />
            </li>
			<li
              class="lang"
              :class="{ active: show == 3 }"
              @click="(show = 3), languageSet(3)"
            >
              <span>English</span>
              <img
                src="@/assets/image/gou.png"
                class="icon"
                alt=""
                v-if="show == 3"
              />
			</li>
            <li
              class="lang"
              :class="{ active: show == 1 }"
              @click="(show = 1), languageSet(1)"
            >
              <span>中文简体</span>
              <img
                src="@/assets/image/gou.png"
                class="icon"
                alt=""
                v-if="show == 1"
              />
            </li>
            <li
              class="lang"
              :class="{ active: show == 2 }"
              @click="(show = 2), languageSet(2)"
            >
              <span>中文繁体</span>
              <img
                src="@/assets/image/gou.png"
                class="icon"
                alt=""
                v-if="show == 2"
              />
            </li>
          </ul>
        </div>
      </header>
    </div>
  </div>
</template>
<script>
export default {
  name: "Language",
  components: {},
  data() {
    return {
      checked: false,
      show: 0,
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    languageSet(type) {
      if (type == 0) {
        localStorage.setItem("locale", "zh");
        localStorage.setItem("localetype", type);
        this.show = 0;
      } else if (type == 1) {
        localStorage.setItem("locale", "zh");
        localStorage.setItem("localetype", type);
        this.show = 1;
      } else if (type == 2) {
        localStorage.setItem("locale", "tw");
        localStorage.setItem("localetype", type);
		this.show = 2;
      } else if (type == 3) {
        localStorage.setItem("locale", "en");
        localStorage.setItem("localetype", type);
		this.show = 3;
      }
      location.reload();
    },
  },
  created() {
    if (localStorage.getItem("localetype") == 1) {
      this.show = 1;
    } else if (localStorage.getItem("localetype") == 2) {
      this.show = 2;
    } else if (localStorage.getItem("localetype") == 3) {
      this.show = 3;
    } else [(this.show = 0)];
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
.lang-box {
}
.lang {
  margin-left: 0.4rem;
  padding-right: 0.4rem;
  display: flex;
  height: 1.18rem;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #fff;
  font-size: 0.4rem;
  border-bottom: 1px solid #3d5179;
}

.active {
  color: green;
}
</style>