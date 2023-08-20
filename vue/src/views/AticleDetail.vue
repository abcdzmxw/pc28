<template>
  <div class="aticleDetail">
    <header>
      <div class="g-header" style="background: rgb(14, 21, 48)">
        <div class="left" @click="changego">
          <van-icon name="arrow-left" color="#fff" size="20px" />
        </div>
        <div class="middle fs-18">{{item.title?item.title:'新闻页'}}</div>
        <div class="right">
          <div class="btn-save fs-16"></div>
        </div>
      </div>
      <div class="blockHeight"></div>
    </header>
    <div class="g-conten flex-column" v-html="item.content"></div>
  </div>
</template>

<script>
export default {
  name: "AticleDetail",
  components: {},
  data() {
    return {
        item:null
    };
  },
  methods: {
    changego() {
      this.$router.go(-1);
    },
  },
  mounted() {},
  created() {
    let news = this.$route.query.news;
    this.item=JSON.parse(news)
    console.log();
  },
};
</script>
<style lang="less" scoped>
.moreNews {
  height: 100%;
}
</style>