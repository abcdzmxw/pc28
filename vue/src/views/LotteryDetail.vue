<template>
  <div class="betRecord">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
          <van-icon name="arrow-left" color="#fff" size="20px" />
        </div>
        <div class="middle fs-18">{{ name }}</div>
        <div class="right">
          <div class="slide-btn" @click="show = !show">
            <div class="bg-icon bg-icon-filtrate"></div>
          </div>
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="mescroll g-content">
      <div>
        <ul class="digital-result">
          <li
            class="digital-item"
            v-for="(item, index) in issueList"
            :key="index"
          >
            <div class="result-wrap">
              <div class="title">
                <span class="issue fs-12">{{ $t("di") }}{{ item.issue }}{{ $t("lun") }}</span>
              </div>
              <div class="result">
                <div class="ball ball-orange">{{ item.site_1 }}</div>
                <span class="fs-16">+</span>
                <div class="ball ball-orange">{{ item.site_2 }}</div>
                <span class="fs-16">+</span>
                <div class="ball ball-orange">{{ item.site_3 }}</div>
                <span class="fs-16">=</span>
                <div class="ball ball-orange">{{ item.code }}</div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import homeApi from "@/api/home";
export default {
  name: "BetRecord",
  components: {},
  data() {
    return {
      game_id: null,
      issueList: null,
      name: "",
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
    showIssueChange() {
      this.$toast.loading({
        message: this.$t('jiazaizhong'),
        forbidClick: true,
        duration: 0,
      });
      homeApi
        .issueList(this.game_id)
        .then((data) => {
          console.log(data.data);
          this.issueList = data.data;
          this.$toast.clear();
        })
        .catch((err) => {
          this.$toast.clear();
          this.$toast.fail(err.msg);
        });
    },
  },
  mounted() {
    this.game_id = this.$route.query.game_id;
    this.name = this.$route.query.name;
    this.showIssueChange();
  },
};
</script>
<style lang="less" scoped>
.betRecord {
  height: 100%;
  padding-bottom: 50px;
}
.digital-result {
  padding: 0.2rem;
  padding: 0 0.2rem 0.2rem;
}
.digital-item {
  background-color: #1a243f;
  margin-bottom: 0.3rem;
  padding: 0.3rem;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-shadow: 0px 1px 1.5px #5078b2;
  box-shadow: 0px 1px 1.5px #5078b2;
  border-radius: 5px;
}
.result-wrap {
  .title {
    font-size: 0.34rem;
    margin-bottom: 0.1rem;
    text-align: left;
  }
}
.result {
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.ball-orange {
  background: linear-gradient(to bottom, #ffb658, #ff6045);
}
.ball {
  display: inline-block;
  border-radius: 50%;
  margin: 0.04rem;
  width: 0.6rem;
  height: 0.6rem;
  text-align: center;
  line-height: 0.6rem;
  color: #fff;
  background-position: center center;
  background-size: cover;
  font-size: 0.35rem;
  overflow: hidden;
  position: relative;
  z-index: 1;
}
</style>